<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms', 'facilities')->latest()->paginate(10);
        return view('dashboard.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        $facilities = Facility::orderBy('facility_name')->get();
        return view('dashboard.room-types.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_type_name' => 'required|string|max:255|unique:room_types,room_type_name',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id', 
        ]);

        DB::beginTransaction();
        try {
            $roomType = RoomType::create([
                'room_type_name' => $validated['room_type_name'],
                'description' => $validated['description'],
            ]);

            if (!empty($validated['facilities'])) {
                $roomType->facilities()->sync($validated['facilities']);
            }

            DB::commit();

            return redirect()->route('dashboard.room-types.index')->with('success', 'Tipe kamar baru berhasil ditambahkan.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(RoomType $roomType)
    {
     
        $roomType->load('facilities');
        $facilities = Facility::orderBy('facility_name')->get();
        
        $attachedFacilities = $roomType->facilities->pluck('id')->toArray();

        return view('dashboard.room-types.edit', compact('roomType', 'facilities', 'attachedFacilities'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'room_type_name' => 'required|string|max:255|unique:room_types,room_type_name,' . $roomType->id,
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        DB::beginTransaction();
        try {
            $roomType->update([
                'room_type_name' => $validated['room_type_name'],
                'description' => $validated['description'],
            ]);


            $roomType->facilities()->sync($validated['facilities'] ?? []);

            DB::commit();

            return redirect()->route('dashboard.room-types.index')->with('success', 'Tipe kamar berhasil diperbarui.');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(RoomType $roomType)
    {
        // (PENTING) Cek apakah ada kamar yang masih menggunakan tipe ini
        if ($roomType->rooms()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus! Tipe kamar ini masih digunakan oleh kamar lain.');
        }

        // Hapus relasi pivot, lalu hapus tipe kamar
        $roomType->facilities()->detach();
        $roomType->delete();

        return redirect()->route('dashboard.room-types.index')->with('success', 'Tipe kamar berhasil dihapus.');
    }
}