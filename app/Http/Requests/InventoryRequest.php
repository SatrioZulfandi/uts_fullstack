<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk validasi data Inventory saat store dan update.
 */
class InventoryRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi sudah ditangani oleh middleware admin
    }

    /**
     * Aturan validasi untuk request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:workspace,equipment',
            'status'      => 'required|in:available,maintenance,borrowed',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Pesan validasi kustom.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama inventaris wajib diisi.',
            'type.required' => 'Tipe inventaris wajib dipilih.',
            'type.in'       => 'Tipe harus berupa workspace atau equipment.',
            'status.in'     => 'Status harus berupa available, maintenance, atau borrowed.',
        ];
    }
}
