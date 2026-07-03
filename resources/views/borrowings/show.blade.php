<x-layouts.app :title="'Borrowing #' . $borrowing->id">

    <div class="page-header">
        <div>
            <h2 class="page-title">Borrowing #{{ $borrowing->id }}</h2>
            <p class="page-subtitle">{{ $borrowing->borrower_name }}</p>
        </div>
        <a href="{{ route('borrowings.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Borrowing Summary --}}
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Summary</h3>
                    @if ($borrowing->status === 'borrowed')
                        <span class="badge-warning">Borrowed</span>
                    @else
                        <span class="badge-success">Returned</span>
                    @endif
                </div>
                <div class="card-body space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Borrower</span>
                        <span class="font-medium">{{ $borrowing->borrower_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created By</span>
                        <span class="font-medium">{{ $borrowing->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Borrow Date</span>
                        <span class="font-medium">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Expected Return</span>
                        <span class="font-medium">{{ $borrowing->return_date?->format('d M Y') ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Items</span>
                        <span class="font-medium">{{ $borrowing->details->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Borrowing Detail Lines --}}
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Items</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($borrowing->details as $detail)
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <p class="font-medium text-gray-900">{{ $detail->product->name }}</p>
                                    <span class="font-mono text-xs text-indigo-600">{{ $detail->product->code }}</span>
                                    @if ($detail->isReturned())
                                        <span class="badge-success">Returned</span>
                                    @else
                                        <span class="badge-warning">Pending</span>
                                    @endif
                                </div>
                                <div class="mt-2 grid grid-cols-3 gap-4 text-sm text-gray-500">
                                    <div>
                                        <span class="text-xs uppercase tracking-wider">Qty</span>
                                        <p class="font-medium text-gray-800 mt-0.5">{{ $detail->quantity }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs uppercase tracking-wider">Condition Before</span>
                                        <p class="font-medium text-gray-800 mt-0.5 capitalize">{{ $detail->condition_before }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs uppercase tracking-wider">Condition After</span>
                                        <p class="font-medium text-gray-800 mt-0.5 capitalize">{{ $detail->condition_after ?? '—' }}</p>
                                    </div>
                                </div>
                                @if ($detail->isReturned())
                                <p class="mt-2 text-xs text-gray-400">
                                    Returned on {{ $detail->returned_at->format('d M Y, H:i') }}
                                </p>
                                @endif
                            </div>

                            {{-- Return action --}}
                            @can('update', $borrowing)
                            @if (! $detail->isReturned())
                            <div class="flex-shrink-0">
                                <button type="button"
                                        onclick="openReturnModal({{ $detail->id }}, '{{ addslashes($detail->product->name) }}', {{ $borrowing->id }})"
                                        class="btn-primary btn-sm">
                                    Return Item
                                </button>
                            </div>
                            @endif
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Return Item Modal --}}
    @can('update', $borrowing)
    <div id="return-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Return Item</h3>
            <p class="text-sm text-gray-500 mb-5" id="modal-product-name"></p>

            <form method="POST" id="return-form">
                @csrf
                <div class="mb-5">
                    <label for="condition_after" class="form-label">Condition After Return <span class="text-red-500">*</span></label>
                    <select id="condition_after" name="condition_after" class="form-select">
                        <option value="good">Good</option>
                        <option value="damaged">Damaged</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 justify-center">Confirm Return</button>
                    <button type="button" onclick="closeReturnModal()" class="btn-secondary flex-1 justify-center">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReturnModal(detailId, productName, borrowingId) {
            document.getElementById('modal-product-name').textContent = productName;
            document.getElementById('return-form').action =
                `/borrowings/${borrowingId}/return/${detailId}`;
            document.getElementById('return-modal').classList.remove('hidden');
        }
        function closeReturnModal() {
            document.getElementById('return-modal').classList.add('hidden');
        }
        // Close on backdrop click
        document.getElementById('return-modal').addEventListener('click', function(e) {
            if (e.target === this) closeReturnModal();
        });
    </script>
    @endcan

</x-layouts.app>
