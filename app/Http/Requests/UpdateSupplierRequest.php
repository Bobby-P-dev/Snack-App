<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_supplier');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name,' . $this->supplier->id],
            'phone' => ['required', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/'],
            'address' => ['required', 'string', 'max:500'],
            'daily_capacity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama supplier wajib diisi',
            'name.unique' => 'Nama supplier sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'address.required' => 'Alamat wajib diisi',
            'daily_capacity.required' => 'Kapasitas harian wajib diisi',
            'daily_capacity.min' => 'Kapasitas harian minimal 1',
        ];
    }
}
