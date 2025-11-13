<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Izinkan admin/resepsionis
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'identity' => 'required|string|size:16',
            'notes' => 'nullable|string',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'total_guests' => 'required|integer|min:1',
            'deposit' => 'required|numeric|min:0', // Saat update, deposit mungkin sudah ada
            'payment_method' => 'required|in:cash,transfer,card',
        ];
    }
}