<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // (PENTING) Tambahkan ini

class UpdateRoomRequest extends FormRequest
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
        // Ambil ID kamar dari parameter route
        $roomId = $this->route('room')->id;

        return [
            'room_type_id' => 'required|exists:room_types,id',
            'room_name' => 'required|string|max:255',
            'room_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms')->ignore($roomId), // Abaikan unik untuk room ini sendiri
            ],
            'room_description' => 'nullable|string',
            'room_capacity' => 'required|integer|min:1',
            'room_price' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'room_type_id.required' => 'Tipe kamar wajib dipilih.',
            'room_type_id.exists' => 'Tipe kamar tidak valid.',
            'room_name.required' => 'Nama kamar wajib diisi.',
            'room_code.required' => 'Kode kamar wajib diisi.',
            'room_code.unique' => 'Kode kamar ini sudah digunakan.',
            'room_capacity.required' => 'Kapasitas kamar wajib diisi.',
            'room_capacity.min' => 'Kapasitas minimal 1 orang.',
            'room_price.required' => 'Harga kamar wajib diisi.',
            'room_price.min' => 'Harga kamar tidak boleh negatif.',
        ];
    }
}