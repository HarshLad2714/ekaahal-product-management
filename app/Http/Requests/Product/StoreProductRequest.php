<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'min:3', 'max:255'],
            'description'       => ['required', 'string', 'min:10', 'max:50000'],
            'price'             => ['required', 'numeric', 'min:0', 'max:99999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'date_available'    => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                => 'Please enter a product title.',
            'title.string'                  => 'Product title must be valid text.',
            'title.min'                     => 'Product title must be at least 3 characters.',
            'title.max'                     => 'Product title cannot exceed 255 characters.',
            'description.required'          => 'Please enter a product description.',
            'description.string'            => 'Product description must be valid text.',
            'description.min'               => 'Product description must be at least 10 characters.',
            'description.max'               => 'Product description cannot exceed 50,000 characters.',
            'price.required'                => 'Please enter a product price.',
            'price.numeric'                 => 'Price must be a valid number.',
            'price.min'                     => 'Price cannot be less than 0.',
            'price.max'                     => 'Price cannot exceed 99,999,999.99.',
            'price.regex'                   => 'Price must be a valid amount with up to two decimal places.',
            'date_available.required'       => 'Please select an availability date.',
            'date_available.date'           => 'Availability date must be a valid date.',
            'date_available.after_or_equal' => 'The availability date must be today or a future date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'             => 'product title',
            'description'       => 'product description',
            'price'             => 'price',
            'date_available'    => 'availability date',
        ];
    }
}
