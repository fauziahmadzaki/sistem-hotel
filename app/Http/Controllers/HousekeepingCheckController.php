<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\HousekeepingCheck;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HousekeepingCheckController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = HousekeepingCheck::with('reservation', 'reservation.room')
            ->where('status', 'needs_to_be_done');

        if ($user->role == 'housekeeper') {
            $query->where('housekeeper_id', $user->id);
        }
        $pendingChecks = $query->latest()->paginate(10, ['*'], 'check_page'); // Paginasi terpisah

        $pendingCleanings = Room::with('roomType')
            ->where('room_status', 'maintenance')
            ->latest()
            ->paginate(10, ['*'], 'cleaning_page'); // Paginasi terpisah

        return view('dashboard.housekeeping.index', compact('pendingChecks', 'pendingCleanings'));
    }

    public function completed()
    {
        $user = Auth::user();
        
        $query = HousekeepingCheck::with('reservation', 'reservation.room', 'housekeeper')
            ->where('status', 'done');

        if ($user->role == 'housekeeper') {
            $query->where('housekeeper_id', $user->id);
        }
        
        $completedChecks = $query->latest()->paginate(15);
        
        return view('dashboard.housekeeping.complete', compact('completedChecks'));
    }

    public function showCheckForm(HousekeepingCheck $check)
    {
        if (auth()->user()->role == 'housekeeper' && $check->housekeeper_id != auth()->id()) {
            abort(403, 'Anda tidak ditugaskan untuk tugas ini.');
        }
        
        $reservation = $check->reservation;
        
        return view('dashboard.housekeeping.check', compact('check', 'reservation'));
    }

    public function processCheck(Request $request, HousekeepingCheck $check)
    {
        if (auth()->user()->role == 'housekeeper' && $check->housekeeper_id != auth()->id()) {
            abort(403, 'Anda tidak ditugaskan untuk tugas ini.');
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $check->update([
            'status' => 'done',
            'notes' => $validated['notes'],
        ]);


        return redirect()->route('dashboard.housekeeping.index')->with('success', 'Pengecekan kamar telah selesai dan laporan dikirim.');
    }

    public function markAsCleaned(Room $room)
    {
        if ($room->room_status !== 'maintenance') {
            return back()->with('error', 'Kamar ini tidak sedang dalam status maintenance.');
        }

        $room->update(['room_status' => 'available']);

        return redirect()->route('dashboard.housekeeping.index')->with('success', "Kamar {$room->room_name} telah selesai dibersihkan dan kembali 'Available'.");
    }
}