<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingDetail extends Model
{
    protected $fillable = [
        'borrowing_id',
        'product_id',
        'quantity',
        'condition_before',
        'condition_after',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'returned_at' => 'datetime',
            'quantity'    => 'integer',
        ];
    }

    /**
     * The parent borrowing transaction.
     */
    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    /**
     * The product that was borrowed.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Determine whether this detail line has already been returned.
     */
    public function isReturned(): bool
    {
        return $this->returned_at !== null;
    }
}
