<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Borrowing::class);
    }

    protected function prepareForValidation(): void
    {
        // Provide sensible defaults for Postman/API simplicity
        if (!$this->has('borrow_date') || empty($this->borrow_date)) {
            $this->merge(['borrow_date' => now()->toDateString()]);
        }

        if ($this->has('items') && is_array($this->items)) {
            $items = $this->items;
            foreach ($items as $index => &$item) {
                if (is_array($item) && empty($item['condition_before'])) {
                    $item['condition_before'] = $item['condition'] ?? 'good';
                }
            }
            $this->merge(['items' => $items]);
        }
    }

    public function rules(): array
    {
        return [
            'borrower_name'              => ['required', 'string', 'max:255'],
            'borrow_date'                => ['required', 'date'],
            'return_date'                => ['nullable', 'date', 'after_or_equal:borrow_date'],
            'items'                      => ['required', 'array', 'min:1'],
            'items.*.product_id'         => ['required', 'integer', 'exists:products,id', 'distinct'],
            'items.*.quantity'           => ['required', 'integer', 'min:1'],
            'items.*.condition_before'   => ['required', 'in:good,damaged,lost'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'                 => 'You must add at least one product to the borrowing.',
            'items.*.product_id.required'    => 'Each item must have a product selected.',
            'items.*.product_id.distinct'    => 'Duplicate products are not allowed in a single borrowing. Adjust the quantity instead.',
            'items.*.quantity.min'           => 'Quantity must be at least 1.',
            'return_date.after_or_equal'     => 'Return date must be on or after the borrow date.',
        ];
    }
}
