<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'name', 
        'phone_number', 
        'identity', 
        'notes',
        'checkin_date', 
        'checkout_date', 
        'total_guests',
        'payment_amount', 
        'deposit', 
        'fines', 
        'payment_method',
        'status',
    ];

    /**
     * Properti $casts disesuaikan dengan tipe data di migrasi (date, bukan datetime).
     */
    protected $casts = [
        'checkin_date' => 'date',
        'checkout_date' => 'date',
    ];


    /**
     * Relasi ke model Room.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function housekeepingCheck()
    {
        return $this->hasOne(HousekeepingCheck::class);
    }
}