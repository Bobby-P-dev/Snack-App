<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Customer bisa membuat order
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
            'pickup_date' => ['required', 'date', 'after:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.type' => ['required', 'in:kustom_box,satuan'],
            'items.*.box_group_id' => ['nullable', 'integer'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama pemesan wajib diisi',
            'customer_phone.required' => 'Nomor telepon wajib diisi',
            'customer_phone.regex' => 'Format nomor telepon tidak valid (gunakan format: 0812xxx atau +62812xxx)',
            'pickup_date.required' => 'Tanggal pengambilan wajib dipilih',
            'pickup_date.after' => 'Tanggal pengambilan harus lebih dari hari ini',
            'items.required' => 'Minimal 1 produk harus dipilih',
            'items.min' => 'Minimal 1 produk harus dipilih',
            'items.*.product_id.required' => 'Produk harus dipilih',
            'items.*.product_id.exists' => 'Produk tidak ditemukan',
            'items.*.quantity.required' => 'Jumlah produk wajib diisi',
            'items.*.quantity.min' => 'Jumlah produk minimal 1',
            'items.*.type.required' => 'Tipe pesanan wajib dipilih',
            'items.*.type.in' => 'Tipe pesanan tidak valid',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->pickup_date) {
            $this->merge([
                'pickup_date' => Carbon::parse($this->pickup_date)->format('Y-m-d H:i:00'),
            ]);
        }
    }
}
