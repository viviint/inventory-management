<x-layouts.app :title="$product->name">

    <div class="page-header">
        <div>
            <h2 class="page-title">{{ $product->name }}</h2>
            <p class="page-subtitle font-mono text-indigo-600">{{ $product->code }}</p>
        </div>
        <div class="flex items-center gap-2">
            @can('update', $product)
            <a href="{{ route('products.edit', $product) }}" class="btn-secondary">Edit</a>
            @endcan
            <a href="{{ route('products.index') }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Product Details Card --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Product Details</h3>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Category</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $product->category->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Location</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $product->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Stock</dt>
                            <dd class="mt-0.5">
                                @if ($product->stock === 0)
                                    <span class="badge-danger">Out of stock</span>
                                @elseif ($product->stock <= 5)
                                    <span class="badge-warning">{{ $product->stock }} units</span>
                                @else
                                    <span class="badge-success">{{ $product->stock }} units</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Condition</dt>
                            <dd class="mt-0.5">
                                @php $condColors = ['good' => 'badge-success', 'damaged' => 'badge-warning', 'lost' => 'badge-danger']; @endphp
                                <span class="{{ $condColors[$product->condition] ?? 'badge-gray' }}">
                                    {{ ucfirst($product->condition) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Added</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $product->created_at->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Last Updated</dt>
                            <dd class="mt-0.5 font-medium text-gray-900">{{ $product->updated_at->diffForHumans() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Active Borrowings --}}
            @can('viewAny', App\Models\Borrowing::class)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Currently Borrowed</h3>
                    <span class="badge-info">{{ $product->borrowingDetails->count() }} active</span>
                </div>
                @if ($product->borrowingDetails->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Qty</th>
                                <th>Borrow Date</th>
                                <th>Expected Return</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->borrowingDetails as $detail)
                            <tr>
                                <td class="font-medium">{{ $detail->borrowing->borrower_name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->borrowing->borrow_date->format('d M Y') }}</td>
                                <td>
                                    {{ $detail->borrowing->return_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td>
                                    <a href="{{ route('borrowings.show', $detail->borrowing) }}" class="btn btn-secondary btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="card-body text-sm text-gray-400">No active borrowings for this product.</div>
                @endif
            </div>
            @endcan
        </div>

        {{-- Image sidebar --}}
        <div>
            <div class="card overflow-hidden">
                @if ($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                         class="w-full aspect-square object-cover">
                @else
                    <div class="w-full aspect-square bg-gray-100 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                @endif
                <div class="p-4 text-center text-xs text-gray-400">
                    {{ $product->image ? 'Product image' : 'No image uploaded' }}
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
