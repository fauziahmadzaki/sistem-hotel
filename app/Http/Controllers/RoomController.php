<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('roomType')->latest()->paginate(10);
        return view('dashboard.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $roomTypes = RoomType::orderBy('room_type_name')->get();
        if ($roomTypes->isEmpty()) {
            return redirect()->route('dashboard.room-types.create')->with('error', 'Anda harus membuat Tipe Kamar terlebih dahulu.');
        }
        return view('dashboard.rooms.create', compact('roomTypes'));
    }
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        Room::create($validated + ['room_status' => 'available']); 

        return redirect()->route('dashboard.rooms.index')->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::orderBy('room_type_name')->get();
        return view('dashboard.rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validated = $request->validated();

        $room->update($validated);

        return redirect()->route('dashboard.rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        
        if ($room->room_status !== 'available') {
            return back()->with('error', 'Tidak dapat menghapus! Kamar sedang dalam status ' . $room->room_status . '.');
        }

        if ($room->reservations()->count() > 0) {
            return back()->with('error', 'Kamar ini memiliki riwayat reservasi dan tidak dapat dihapus.');
        }

        $room->delete();

        return redirect()->route('dashboard.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}