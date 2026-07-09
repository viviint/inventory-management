<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Product::class);
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('condition') || empty($this->condition)) {
            $this->merge(['condition' => 'good']);
        }
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'code'        => ['required', 'string', 'max:100', 'unique:products,code'],
            'name'        => ['required', 'string', 'max:255'],
            'stock'       => ['required', 'integer', 'min:0'],
            'location'    => ['required', 'string', 'max:255'],
            'condition'   => ['required', 'in:good,damaged,lost'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique'       => 'This product code is already in use.',
            'category_id.exists'=> 'The selected category does not exist.',
            'stock.min'         => 'Stock cannot be negative.',
            'condition.in'      => 'Condition must be good, damaged, or lost.',
        ];
    }
}
