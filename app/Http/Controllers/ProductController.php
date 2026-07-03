<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a paginated listing of products with optional search and category filter.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $categories = Category::orderBy('name')->get();

        $products = Product::with('category')
            ->when($request->filled('search'), fn ($q) =>
                $q->where(function ($inner) use ($request) {
                    $inner->where('code', 'like', '%' . $request->search . '%')
                          ->orWhere('name', 'like', '%' . $request->search . '%')
                          ->orWhere('location', 'like', '%' . $request->search . '%');
                })
            )
            ->when($request->filled('category'), fn ($q) =>
                $q->where('category_id', $request->category)
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $this->authorize('create', Product::class);

        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product detail.
     */
    public function show(Product $product): View
    {
        $this->authorize('view', $product);

        $product->load([
            'category',
            'borrowingDetails' => fn ($q) => $q->whereNull('returned_at')->with('borrowing'),
        ]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     * Blocked if the product has unreturned borrowings.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        if ($product->hasActiveBorrowings()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Cannot delete a product with active (unreturned) borrowings.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
