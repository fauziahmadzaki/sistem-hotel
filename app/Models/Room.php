<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_name',
        'room_code',
        'room_description',
        'room_capacity',
        'room_price',
        'room_status',
    ];

    /**
     * Relasi ke Tipe Kamar.
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Relasi ke Reservasi.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}