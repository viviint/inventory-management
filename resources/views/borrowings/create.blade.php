<x-layouts.app :title="'New Borrowing'">

    <div class="page-header">
        <div>
            <h2 class="page-title">New Borrowing Transaction</h2>
            <p class="page-subtitle">Create a new borrowing request</p>
        </div>
        <a href="{{ route('borrowings.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('borrowings.store') }}" id="borrowing-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Borrower Info --}}
            <div class="lg:col-span-1 space-y-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Borrower Information</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div>
                            <label for="borrower_name" class="form-label">Borrower Name <span class="text-red-500">*</span></label>
                            <input id="borrower_name" type="text" name="borrower_name" value="{{ old('borrower_name') }}"
                                   class="{{ $errors->has('borrower_name') ? 'form-input-error' : 'form-input' }}"
                                   placeholder="Full name" autofocus>
                            @error('borrower_name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="borrow_date" class="form-label">Borrow Date <span class="text-red-500">*</span></label>
                            <input id="borrow_date" type="date" name="borrow_date"
                                   value="{{ old('borrow_date', today()->toDateString()) }}"
                                   class="{{ $errors->has('borrow_date') ? 'form-input-error' : 'form-input' }}">
                            @error('borrow_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="return_date" class="form-label">
                                Expected Return Date
                                <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <input id="return_date" type="date" name="return_date"
                                   value="{{ old('return_date') }}"
                                   class="{{ $errors->has('return_date') ? 'form-input-error' : 'form-input' }}">
                            @error('return_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Confirm Borrowing
                </button>
            </div>

            {{-- Product Items --}}
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Products</h3>
                        <button type="button" id="add-item-btn" class="btn-secondary btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Product
                        </button>
                    </div>

                    @error('items')
                        <div class="px-6 pt-3 text-sm text-red-600">{{ $message }}</div>
                    @enderror

                    <div class="card-body space-y-3" id="items-container">
                        {{-- At least one row pre-rendered --}}
                        <div class="item-row grid grid-cols-12 gap-3 items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="col-span-5">
                                <label class="form-label text-xs">Product</label>
                                <select name="items[0][product_id]" class="form-select text-sm">
                                    <option value="">— Select —</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Stock: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('items.0.product_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="form-label text-xs">Qty</label>
                                <input type="number" name="items[0][quantity]" value="{{ old('items.0.quantity', 1) }}"
                                       min="1" class="form-input text-sm">
                                @error('items.0.quantity') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-4">
                                <label class="form-label text-xs">Condition Before</label>
                                <select name="items[0][condition_before]" class="form-select text-sm">
                                    <option value="good">Good</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="lost">Lost</option>
                                </select>
                            </div>
                            <div class="col-span-1 flex items-end pb-0.5">
                                <button type="button" onclick="this.closest('.item-row').remove()"
                                        class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Products JSON for JS --}}
    <script>
        const availableProducts = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'stock' => $p->stock])) !!};
        let rowIndex = 1;

        document.getElementById('add-item-btn').addEventListener('click', () => {
            const options = availableProducts.map(p =>
                `<option value="${p.id}">${p.name} (Stock: ${p.stock})</option>`
            ).join('');

            const row = document.createElement('div');
            row.className = 'item-row grid grid-cols-12 gap-3 items-start p-4 bg-gray-50 rounded-lg border border-gray-200';
            row.innerHTML = `
                <div class="col-span-5">
                    <label class="form-label text-xs">Product</label>
                    <select name="items[${rowIndex}][product_id]" class="form-select text-sm">
                        <option value="">— Select —</option>${options}
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="form-label text-xs">Qty</label>
                    <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" class="form-input text-sm">
                </div>
                <div class="col-span-4">
                    <label class="form-label text-xs">Condition Before</label>
                    <select name="items[${rowIndex}][condition_before]" class="form-select text-sm">
                        <option value="good">Good</option>
                        <option value="damaged">Damaged</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="col-span-1 flex items-end pb-0.5">
                    <button type="button" onclick="this.closest('.item-row').remove()"
                            class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.getElementById('items-container').appendChild(row);
            rowIndex++;
        });
    </script>

</x-layouts.app>
