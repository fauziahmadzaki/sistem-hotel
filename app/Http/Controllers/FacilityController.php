<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::withCount('roomTypes')->latest()->paginate(10);
        return view('dashboard.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('dashboard.facilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string|max:255|unique:facilities,facility_name',
        ]);

        Facility::create($validated);

        return redirect()->route('dashboard.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        return view('dashboard.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string|max:255|unique:facilities,facility_name,' . $facility->id,
        ]);

        $facility->update($validated);

        return redirect()->route('dashboard.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        // Hapus relasi di pivot table, lalu hapus fasilitas
        $facility->roomTypes()->detach();
        $facility->delete();

        return redirect()->route('dashboard.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}