<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowingRequest;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Display a paginated list of all borrowings, filterable by status.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Borrowing::class);

        $borrowings = Borrowing::with(['user', 'details.product'])
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->filled('search'), fn ($q) =>
                $q->where('borrower_name', 'like', '%' . $request->search . '%')
            )
            ->orderByDesc('borrow_date')
            ->paginate(10)
            ->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing transaction.
     */
    public function create(): View
    {
        $this->authorize('create', Borrowing::class);

        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('borrowings.create', compact('products'));
    }

    /**
     * Store a new borrowing transaction.
     * Stock validation and decrement wrapped in a DB transaction.
     */
    public function store(StoreBorrowingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            // Create the borrowing header
            $borrowing = Borrowing::create([
                'user_id'       => $request->user()->id,
                'borrower_name' => $validated['borrower_name'],
                'borrow_date'   => $validated['borrow_date'],
                'return_date'   => $validated['return_date'] ?? null,
                'status'        => 'borrowed',
            ]);

            foreach ($validated['items'] as $item) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                // Business rule: stock must be available
                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        "Insufficient stock for product \"{$product->name}\". " .
                        "Available: {$product->stock}, Requested: {$item['quantity']}."
                    );
                }

                // Decrement stock
                $product->decrement('stock', $item['quantity']);

                // Create detail line
                BorrowingDetail::create([
                    'borrowing_id'    => $borrowing->id,
                    'product_id'      => $product->id,
                    'quantity'        => $item['quantity'],
                    'condition_before'=> $item['condition_before'],
                    'condition_after' => null,
                    'returned_at'     => null,
                ]);
            }
        });

        return redirect()
            ->route('borrowings.index')
            ->with('success', 'Borrowing transaction created successfully.');
    }

    /**
     * Display the specified borrowing with all its detail lines.
     */
    public function show(Borrowing $borrowing): View
    {
        $this->authorize('view', $borrowing);

        $borrowing->load(['user', 'details.product.category']);

        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Process the return of a single borrowing detail line.
     * Stock is restored and detail marked as returned inside a DB transaction.
     */
    public function returnItem(Request $request, Borrowing $borrowing, BorrowingDetail $detail): RedirectResponse
    {
        $this->authorize('update', $borrowing);

        // Validate ownership: detail must belong to this borrowing
        if ($detail->borrowing_id !== $borrowing->id) {
            abort(404);
        }

        if ($detail->isReturned()) {
            return redirect()
                ->route('borrowings.show', $borrowing)
                ->with('error', 'This item has already been returned.');
        }

        $request->validate([
            'condition_after' => ['required', 'in:good,damaged,lost'],
        ]);

        DB::transaction(function () use ($request, $borrowing, $detail) {
            // Restore stock
            $detail->product()->lockForUpdate()->first()->increment('stock', $detail->quantity);

            // Mark the detail as returned
            $detail->update([
                'condition_after' => $request->condition_after,
                'returned_at'     => now(),
            ]);

            // If all items are returned, close the parent borrowing
            if ($borrowing->allItemsReturned()) {
                $borrowing->update([
                    'status'      => 'returned',
                    'return_date' => now()->toDateString(),
                ]);
            }
        });

        return redirect()
            ->route('borrowings.show', $borrowing)
            ->with('success', 'Item returned successfully. Stock has been restored.');
    }
}
