<x-layouts.app :title="'Edit Product'">

    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Product</h2>
            <p class="page-subtitle">Update product — {{ $product->code }}</p>
        </div>
        <a href="{{ route('products.show', $product) }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="code" class="form-label">Product Code <span class="text-red-500">*</span></label>
                        <input id="code" type="text" name="code" value="{{ old('code', $product->code) }}"
                               class="{{ $errors->has('code') ? 'form-input-error' : 'form-input' }}">
                        @error('code') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="form-label">Category <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id"
                                class="{{ $errors->has('category_id') ? 'form-input-error' : 'form-select' }}">
                            <option value="">— Select Category —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="name" class="form-label">Product Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="{{ $errors->has('name') ? 'form-input-error' : 'form-input' }}">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="stock" class="form-label">Stock <span class="text-red-500">*</span></label>
                        <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
                               class="{{ $errors->has('stock') ? 'form-input-error' : 'form-input' }}">
                        @error('stock') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="condition" class="form-label">Condition <span class="text-red-500">*</span></label>
                        <select id="condition" name="condition"
                                class="{{ $errors->has('condition') ? 'form-input-error' : 'form-select' }}">
                            <option value="good"    {{ old('condition', $product->condition) === 'good' ? 'selected' : '' }}>Good</option>
                            <option value="damaged" {{ old('condition', $product->condition) === 'damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="lost"    {{ old('condition', $product->condition) === 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                        @error('condition') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="location" class="form-label">Storage Location <span class="text-red-500">*</span></label>
                        <input id="location" type="text" name="location" value="{{ old('location', $product->location) }}"
                               class="{{ $errors->has('location') ? 'form-input-error' : 'form-input' }}">
                        @error('location') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Current image preview --}}
                @if ($product->image)
                <div>
                    <p class="form-label">Current Image</p>
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                         class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                </div>
                @endif

                <div>
                    <label for="image" class="form-label">
                        {{ $product->image ? 'Replace Image' : 'Product Image' }}
                        <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <input id="image" type="file" name="image" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                    @error('image') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">Update Product</button>
                    <a href="{{ route('products.show', $product) }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
