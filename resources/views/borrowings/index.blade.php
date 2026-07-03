<x-layouts.app :title="'Borrowings'">

    <div class="page-header">
        <div>
            <h2 class="page-title">Borrowings</h2>
            <p class="page-subtitle">Track and manage all borrowing transactions</p>
        </div>
        @can('create', App\Models\Borrowing::class)
        <a href="{{ route('borrowings.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Borrowing
        </a>
        @endcan
    </div>

    <div class="card">
        {{-- Filters --}}
        <div class="card-header flex-wrap gap-3">
            <form method="GET" action="{{ route('borrowings.index') }}" class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="search-borrowings" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by borrower name..."
                           class="form-input pl-9 w-64">
                </div>
                <select id="filter-status" name="status" class="form-select w-40">
                    <option value="">All Status</option>
                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
                <button type="submit" class="btn-secondary">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('borrowings.index') }}" class="btn-secondary">Clear</a>
                @endif
            </form>
            <span class="text-sm text-gray-500">{{ $borrowings->total() }} {{ Str::plural('record', $borrowings->total()) }}</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Borrower</th>
                        <th>Items</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowings as $borrowing)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ $borrowing->id }}</td>
                        <td class="font-medium text-gray-900">{{ $borrowing->borrower_name }}</td>
                        <td>
                            <span class="badge-info">{{ $borrowing->details->count() }} item(s)</span>
                        </td>
                        <td class="text-sm">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                        <td class="text-sm">{{ $borrowing->return_date?->format('d M Y') ?? '—' }}</td>
                        <td>
                            @if ($borrowing->status === 'borrowed')
                                <span class="badge-warning">Borrowed</span>
                            @else
                                <span class="badge-success">Returned</span>
                            @endif
                        </td>
                        <td class="text-gray-500 text-sm">{{ $borrowing->user->name }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('borrowings.show', $borrowing) }}" class="btn btn-secondary btn-sm">View</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400">No borrowing records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($borrowings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $borrowings->links() }}
        </div>
        @endif
    </div>

</x-layouts.app>
