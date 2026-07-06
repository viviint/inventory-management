<x-layouts.app :title="'Products'">

    <div class="page-header">
        <div>
            <h2 class="page-title">Products</h2>
            <p class="page-subtitle">Manage inventory products and stock levels</p>
        </div>
        @can('create', App\Models\Product::class)
        <a href="{{ route('products.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Product</span>
        </a>
        @endcan
    </div>

    <div class="card">
        {{-- Filters --}}
        <div class="card-header flex-wrap gap-3">
            <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-products" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by code, name, location..."
                           class="form-input pl-10 w-72">
                </div>
                <select id="filter-category" name="category" class="form-select w-48">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-secondary">Filter</button>
                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('products.index') }}" class="btn-ghost">Clear</a>
                @endif
            </form>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $products->total() }} {{ Str::plural('product', $products->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Location</th>
                        <th>Condition</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr>
                        <td class="font-mono text-xs text-[#24C4F4] font-semibold">{{ $product->code }}</td>
                        <td class="font-semibold text-slate-900 dark:text-white">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-[#7C3AED] dark:hover:text-[#24C4F4] transition-colors">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td class="text-slate-500 dark:text-slate-400">{{ $product->category->name }}</td>
                        <td>
                            @if ($product->stock === 0)
                                <span class="badge-out-of-stock">Out of stock</span>
                            @elseif ($product->stock <= 5)
                                <span class="badge-low-stock">{{ $product->stock }} Low</span>
                            @else
                                <span class="badge-available">{{ $product->stock }} Available</span>
                            @endif
                        </td>
                        <td class="text-slate-500 dark:text-slate-400 text-sm">{{ $product->location }}</td>
                        <td>
                            @php $condColors = ['good' => 'badge-available', 'damaged' => 'badge-low-stock', 'lost' => 'badge-out-of-stock']; @endphp
                            <span class="{{ $condColors[$product->condition] ?? 'badge-gray' }}">
                                {{ ucfirst($product->condition) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('products.show', $product) }}" class="btn-secondary btn-sm">View</a>
                                @can('update', $product)
                                <a href="{{ route('products.edit', $product) }}" class="btn-secondary btn-sm">Edit</a>
                                @endcan
                                @can('delete', $product)
                                <form method="POST" action="{{ route('products.destroy', $product) }}"
                                      onsubmit="return confirm('Delete product \'{{ $product->name }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">Delete</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400 dark:text-slate-500">
                            No products found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-800/30">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>
