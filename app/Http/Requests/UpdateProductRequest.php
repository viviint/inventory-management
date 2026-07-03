<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('product'));
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'code'        => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'code')->ignore($this->route('product')),
            ],
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
            'code.unique'       => 'This product code is already in use by another product.',
            'category_id.exists'=> 'The selected category does not exist.',
            'stock.min'         => 'Stock cannot be negative.',
            'condition.in'      => 'Condition must be good, damaged, or lost.',
        ];
    }
}
