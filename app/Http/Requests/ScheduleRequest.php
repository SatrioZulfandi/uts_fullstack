<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk validasi data Borrowing Schedule saat store dan update.
 */
class ScheduleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'inventory_id' => 'required|exists:inventories,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:booked,checked_in,completed,cancelled',
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
            'user_id.required' => 'User wajib dipilih.',
            'user_id.exists' => 'User tidak ditemukan.',
            'inventory_id.required' => 'Inventaris wajib dipilih.',
            'inventory_id.exists' => 'Inventaris tidak ditemukan.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'start_time.after_or_equal' => 'Waktu mulai harus sama dengan atau setelah waktu sekarang.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ];
    }
}
