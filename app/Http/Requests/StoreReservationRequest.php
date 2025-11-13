<?php

namespace App\Http\Requests;

use App\Models\Room; // Ditambahkan
use Illuminate\Validation\Validator; // Ditambahkan
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        // Ubah ke true, atau tambahkan logika otorisasi (misal: cek role admin)
        return true; 
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            
            // Diperbarui: Menggunakan regex dari file Anda
            'phone_number' => [
                'required',
                'string',
                'max:15', // Diberi sedikit ruang untuk +62
                'regex:/^(08|\+628)\d{8,12}$/' 
            ],

            'identity' => 'required|string|size:16', // Sesuai migrasi (NIK KTP)
            'notes' => 'nullable|string',
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
            'total_guests' => 'required|integer|min:1',
            'deposit' => 'required|numeric|min:300000', // Aturan Bisnis: Minimal deposit
            'payment_method' => 'required|in:cash,transfer,card',
        ];
    }

    /**
     * Custom message untuk validasi.
     */
    public function messages(): array
    {
        return [
            'room_id.required' => 'Silakan pilih kamar.',
            'room_id.exists' => 'Kamar yang dipilih tidak valid.',
            'identity.size' => 'Nomor Identitas (NIK) harus 16 digit.',
            'deposit.min' => 'Deposit jaminan minimal adalah Rp 300.000.',
            'checkout_date.after' => 'Tanggal checkout harus setelah tanggal checkin.',

            // Ditambahkan: Pesan untuk regex telepon
            'phone_number.regex' => 'Format nomor telepon tidak valid. Gunakan 08... atau +628...',
        ];
    }

    /**
     * Ditambahkan: Logika validasi kustom dari file Anda
     * untuk mengecek kapasitas kamar.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();

            $roomId = $data['room_id'] ?? null;
            $totalGuests = $data['total_guests'] ?? null;

            if ($roomId && $totalGuests) {
                $room = Room::find($roomId);
                if ($room) {
                    if ($totalGuests > $room->room_capacity) {
                        $validator->errors()->add(
                            'total_guests',
                            "Jumlah tamu ({$totalGuests}) melebihi kapasitas kamar ({$room->room_capacity})."
                        );
                    }
                }
            }
        });
    }
}