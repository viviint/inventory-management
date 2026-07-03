<x-layouts.app :title="'Create Category'">

    <div class="page-header">
        <div>
            <h2 class="page-title">Create Category</h2>
            <p class="page-subtitle">Add a new product category</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <div class="card max-w-lg">
        <div class="card-body">
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="form-label">Category Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           class="{{ $errors->has('name') ? 'form-input-error' : 'form-input' }}"
                           placeholder="e.g. Electronics" autofocus>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="{{ $errors->has('description') ? 'form-input-error' : 'form-input' }}"
                              placeholder="Optional description...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary">Create Category</button>
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
