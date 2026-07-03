<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'borrower_name',
        'borrow_date',
        'return_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
        ];
    }

    /**
     * The user (staff/admin) who created this borrowing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A borrowing has many detail lines (one per product).
     */
    public function details(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    /**
     * Check whether all borrowing detail lines have been returned.
     * If yes, the parent borrowing status should be set to 'returned'.
     */
    public function allItemsReturned(): bool
    {
        return $this->details()->whereNull('returned_at')->doesntExist();
    }
}
