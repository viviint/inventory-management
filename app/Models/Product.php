<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'stock',
        'location',
        'condition',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
        ];
    }

    /**
     * A product belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A product can appear in many borrowing details.
     */
    public function borrowingDetails(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    /**
     * Check if the product has active (unreturned) borrowing details.
     */
    public function hasActiveBorrowings(): bool
    {
        return $this->borrowingDetails()->whereNull('returned_at')->exists();
    }

    /**
     * Check if stock is available for borrowing.
     */
    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }
}
