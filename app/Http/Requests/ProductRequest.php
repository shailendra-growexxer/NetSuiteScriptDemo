<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('product')?->id;
        return [
            'sku' => 'required|string|max:64|unique:products,sku,'.($id ?? 'NULL').',id',
            'barcode' => 'nullable|string|max:64',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'uom' => 'nullable|string|max:16',
            'category' => 'nullable|string|max:128',
            'brand' => 'nullable|string|max:128',
            'is_active' => 'boolean',
            'attributes' => 'array',
        ];
    }
}
