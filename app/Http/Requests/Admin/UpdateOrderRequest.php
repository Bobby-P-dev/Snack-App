<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'pickup_date' => ['required'],
            'location' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:pending,diterima,diproses,dikemas,dikirim,selesai'],
            'payment_type' => ['required', 'in:dp,full'],
            'dp_amount' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'integer'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price_at_order' => ['required', 'numeric', 'min:0'],
            'items.*.type' => ['required', 'in:satuan,kustom_box'],
            'items.*.box_group_id' => ['nullable'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'pickup_date.required' => 'Tanggal & jam pengambilan wajib ditentukan.',
            'location.required' => 'Lokasi atau alamat pengiriman wajib diisi.',
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan tidak valid.',
            'payment_type.required' => 'Tipe pembayaran wajib dipilih.',
            'payment_type.in' => 'Tipe pembayaran harus DP atau Full.',
            'dp_amount.required' => 'Nominal DP wajib diisi.',
            'dp_amount.numeric' => 'Nominal DP harus berupa angka.',
            'dp_amount.min' => 'Nominal DP tidak boleh bernilai negatif.',
            'total_amount.required' => 'Total tagihan wajib diisi.',
            'total_amount.numeric' => 'Total tagihan harus berupa angka.',
            'total_amount.min' => 'Total tagihan tidak boleh bernilai negatif.',
            'items.required' => 'Pesanan minimal harus memiliki 1 item produk.',
            'items.min' => 'Pesanan minimal harus memiliki 1 item produk.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak ditemukan dalam sistem.',
            'items.*.quantity.required' => 'Jumlah item wajib diisi.',
            'items.*.quantity.min' => 'Jumlah item minimal 1.',
            'items.*.price_at_order.required' => 'Harga per item wajib diisi.',
            'items.*.price_at_order.min' => 'Harga per item tidak boleh negatif.',
        ];
    }
}
