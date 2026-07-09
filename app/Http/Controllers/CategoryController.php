<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a paginated listing of categories with optional search.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->when($request->filled('search'), fn ($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
            )
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($this->isApiOrPostman($request)) {
            return response()->json($categories, 200);
        }

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($this->isApiOrPostman($request)) {
            return response()->json([
                'message'  => 'Category created successfully.',
                'category' => $category,
            ], 201);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        if ($this->isApiOrPostman($request)) {
            return response()->json([
                'message'  => 'Category updated successfully.',
                'category' => $category->fresh(),
            ], 200);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     * Blocked if the category still has associated products (restrict FK).
     */
    public function destroy(Request $request, Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->products()->exists()) {
            if ($this->isApiOrPostman($request)) {
                return response()->json([
                    'message' => 'Cannot delete a category that has associated products. Reassign or delete the products first.',
                ], 422);
            }
            return redirect()
                ->route('categories.index')
                ->with('error', 'Cannot delete a category that has associated products. Reassign or delete the products first.');
        }

        $category->delete();

        if ($this->isApiOrPostman($request)) {
            return response()->json([
                'message' => 'Category deleted successfully.',
            ], 200);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
