<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        $id = $this->route('book')?->id;
        return [
            'isbn' => 'required|string|max:17|unique:books,isbn,'.($id ?? 'NULL').',id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'published_date' => 'nullable|date',
            'genre' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }
}
