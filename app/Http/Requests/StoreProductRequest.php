<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_product');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0', 'gt:base_price'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier harus dipilih',
            'supplier_id.exists' => 'Supplier tidak ditemukan',
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'name.required' => 'Nama produk wajib diisi',
            'name.unique' => 'Nama produk sudah terdaftar',
            'base_price.required' => 'Harga modal wajib diisi',
            'base_price.min' => 'Harga modal tidak boleh negatif',
            'sell_price.required' => 'Harga jual wajib diisi',
            'sell_price.gt' => 'Harga jual harus lebih tinggi dari harga modal',
            'image.image' => 'File harus berupa gambar',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
