<x-layouts.app :title="'Categories'">

    <div class="page-header">
        <div>
            <h2 class="page-title">Categories</h2>
            <p class="page-subtitle">Manage product categories</p>
        </div>
        @can('create', App\Models\Category::class)
        <a href="{{ route('categories.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </a>
        @endcan
    </div>

    <div class="card">
        {{-- Search bar --}}
        <div class="card-header">
            <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-3 w-full max-w-md">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-categories" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search categories..."
                           class="form-input pl-9">
                </div>
                <button type="submit" class="btn-secondary">Search</button>
                @if(request('search'))
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Clear</a>
                @endif
            </form>
            <span class="text-sm text-gray-500">{{ $categories->total() }} {{ Str::plural('category', $categories->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ $category->id }}</td>
                        <td class="font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="text-gray-500 max-w-xs truncate">{{ $category->description ?: '—' }}</td>
                        <td>
                            <span class="badge-info">{{ $category->products_count }}</span>
                        </td>
                        <td class="text-gray-400 text-xs">{{ $category->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $category)
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm">
                                    Edit
                                </a>
                                @endcan
                                @can('delete', $category)
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                      onsubmit="return confirm('Delete category \'{{ $category->name }}\'? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-400">
                            No categories found.
                            @if(request('search'))
                                Try a different search term.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>
