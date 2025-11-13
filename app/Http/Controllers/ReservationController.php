<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Transaction; 
use App\Models\HousekeepingCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{

    public function index()
    {
        $reservations = Reservation::with('room', 'user')
            ->whereIn('status', ['checkin', 'pending'])
            ->latest()
            ->paginate(10);
            
        return view('dashboard.reservations.index', compact('reservations'));
    }

    public function completed()
    {
        $reservations = Reservation::with('room', 'user')
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.reservations.completed', compact('reservations'));
    }

    public function cancelled()
    {
        $reservations = Reservation::with('room', 'user')
            ->where('status', 'cancelled')
            ->latest()
            ->paginate(10);
            
        return view('dashboard.reservations.cancelled', compact('reservations'));
    }

    public function create()
    {
        $availableRooms = Room::where('room_status', 'available')->get();
        
        return view('dashboard.reservations.create', compact('availableRooms'));
    }

    public function store(StoreReservationRequest $request)
    {
       
        $validatedData = $request->validated();

        // if ($validatedData['deposit'] < 300000) {
        //     throw ValidationException::withMessages([
        //         'deposit' => 'Deposit minimal adalah Rp 300.000.',
        //     ]);
        // }

        $room = Room::findOrFail($validatedData['room_id']);

        if ($room->room_status !== 'available') {
            throw ValidationException::withMessages([
                'room_id' => 'Kamar ini sudah tidak tersedia. Silakan pilih kamar lain.',
            ]);
        }
        
        DB::beginTransaction();
        try {
            $reservation = Reservation::create($validatedData + [
                'user_id' => auth()->id() ?? null, // Tambahkan user_id jika ada
                'status' => 'checkin' // Sesuai default migrasi
            ]);

            $room->update(['room_status' => 'booked']);

            Transaction::create([
                'type' => 'income',
                'category' => 'deposit',
                'amount' => $reservation->deposit,
                'description' => "Deposit (Jaminan) untuk Reservasi #{$reservation->id} (Tamu: {$reservation->name})",
            ]);

            DB::commit();

            return redirect()->route('dashboard.reservations.index')->with('success', 'Reservasi berhasil dibuat dan kamar telah dibooking.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Reservation $reservation)
    {
        $availableRooms = Room::where('room_status', 'available')->get();
        $allRooms = $availableRooms->push($reservation->room)->unique('id');

        return view('dashboard.reservations.edit', compact('reservation', 'allRooms'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            $newRoomId = $validatedData['room_id'];
            $oldRoomId = $reservation->room_id;

            if ($newRoomId != $oldRoomId) {
                Room::find($oldRoomId)->update(['room_status' => 'available']);
                Room::find($newRoomId)->update(['room_status' => 'booked']);
            }

            $reservation->update($validatedData);

            DB::commit();
            return redirect()->route('dashboard.reservations.index')->with('success', 'Data reservasi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function showCheckForm(Reservation $reservation)
    {
        $checkinDate = new \DateTime($reservation->checkin_date);
        $checkoutDate = new \DateTime($reservation->checkout_date);
        $durationInDays = $checkoutDate->diff($checkinDate)->days;
        if ($durationInDays == 0) $durationInDays = 1;

        $roomCost = $reservation->room->room_price * $durationInDays;
        
        $subTotal = $roomCost;
        
        $totalDue = ($subTotal + $reservation->fines) - $reservation->deposit;

        $checkStatus = $reservation->housekeepingCheck; // Menggunakan relasi
        $housekeepers = User::where('role', 'housekeeper')->get();

        return view('dashboard.reservations.checkout', compact(
            'reservation', 
            'roomCost', 
            'subTotal', 
            'totalDue',
            'durationInDays',
            'checkStatus',
            'housekeepers'
        ));
    }

    public function requestHousekeepingCheck(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'housekeeper_id' => 'required|exists:users,id'
        ], [
            'housekeeper_id.required' => 'Silakan pilih housekeeper yang akan bertugas.'
        ]);

        $existingCheck = HousekeepingCheck::where('reservation_id', $reservation->id)->first();
        if ($existingCheck) {
            return back()->with('error', 'Permintaan pengecekan untuk reservasi ini sudah dibuat.');
        }

        DB::beginTransaction();
        try {
            HousekeepingCheck::create([
                'reservation_id' => $reservation->id,
                'housekeeper_id' => $validated['housekeeper_id'],
                'status' => 'needs_to_be_done',
            ]);

            $reservation->update(['status' => 'pending']);

            DB::commit();

            return back()->with('success', 'Permintaan pengecekan telah dikirim. Status reservasi diubah ke Pending.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function processCheckout(Request $request, Reservation $reservation)
    {
        $check = $reservation->housekeepingCheck;
        if (!$check || $check->status !== 'done') {
            return back()->with('error', 'Checkout Gagal! Kamar belum diperiksa atau status pengecekan belum selesai.');
        }

        $validated = $request->validate([
            'fines' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,card',
            'notes' => 'nullable|string',
        ]);

        $fines = $validated['fines'] ?? 0;

        $checkinDate = new \DateTime($reservation->checkin_date);
        $checkoutDate = new \DateTime($reservation->checkout_date);
        $durationInDays = $checkoutDate->diff($checkinDate)->days;
        if ($durationInDays == 0) $durationInDays = 1;
        
        $roomCost = $reservation->room->room_price * $durationInDays;
        
        $totalPaymentAmount = $roomCost + $fines;

        DB::beginTransaction();
        try {
            Transaction::create([
                'type' => 'income',
                'category' => 'rental',
                'amount' => $roomCost,
                'description' => "Pembayaran Kamar ({$durationInDays} hari) - Reservasi #{$reservation->id} (Tamu: {$reservation->name})",
            ]);

            if ($fines > 0) {
                Transaction::create([
                    'type' => 'income',
                    'category' => 'other', // Anda bisa ubah ke 'maintenance' jika perlu
                    'amount' => $fines,
                    'description' => "Denda Pelanggaran - Reservasi #{$reservation->id} (Tamu: {$reservation->name})",
                ]);
            }

            if ($reservation->deposit > 0) {
                Transaction::create([
                    'type' => 'expense',
                    'category' => 'deposit',
                    'amount' => $reservation->deposit,
                    'description' => "Pengembalian Deposit (Checkout) - Reservasi #{$reservation->id} (Tamu: {$reservation->name})",
                ]);
            }

            $reservation->update([
                'fines' => $fines,
                'payment_amount' => $totalPaymentAmount, 
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? $reservation->notes,
                'status' => 'completed',
                
            ]);

            $reservation->room->update(['room_status' => 'maintenance']);

            DB::commit();

            return redirect()->route('dashboard.reservations.completed')->with('success', 'Checkout berhasil. Status kamar diubah ke Maintenance.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage());
        }
    }

        public function show(Reservation $reservation)
    {
        
        $checkinDate = new \DateTime($reservation->checkin_date);
        $checkoutDate = new \DateTime($reservation->checkout_date);
        $durationInDays = $checkoutDate->diff($checkinDate)->days;
        if ($durationInDays == 0) $durationInDays = 1;


        $roomCost = $reservation->room->room_price * $durationInDays;

        $totalTagihan = $reservation->payment_amount ?? ($roomCost + $reservation->fines);
        $balanceDue = $totalTagihan - $reservation->deposit;
        $checkStatus = $reservation->housekeepingCheck;

        return view('dashboard.reservations.show', compact(
            'reservation', 
            'durationInDays',
            'roomCost',
            'totalTagihan',
            'balanceDue',
            'checkStatus'
        ));
    }

    public function invoice(Reservation $reservation)
    {
        // Logika perhitungan sama dengan show()
        $checkinDate = new \DateTime($reservation->checkin_date);
        $checkoutDate = new \DateTime($reservation->checkout_date);
        $durationInDays = $checkoutDate->diff($checkinDate)->days;
        if ($durationInDays == 0) $durationInDays = 1;

        $roomCost = $reservation->room->room_price * $durationInDays;
        
        // Jika status 'completed', ambil dari payment_amount
        if ($reservation->status == 'completed') {
            $totalTagihan = $reservation->payment_amount; // Ini sudah termasuk denda
        } else {
            // Jika belum checkout, hitung manual (denda mungkin masih 0)
            $totalTagihan = $roomCost + $reservation->fines;
        }
        
        $balanceDue = $totalTagihan - $reservation->deposit;

        return view('dashboard.reservations.invoice', compact(
            'reservation', 
            'durationInDays',
            'roomCost',
            'totalTagihan',
            'balanceDue'
        ));
    }

    public function cancel(Reservation $reservation)
    {
       
        if (!in_array($reservation->status, ['pending', 'checkin'])) {
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
       
            $reservation->update(['status' => 'cancelled']);

            $reservation->room->update(['room_status' => 'available']);

            if ($reservation->deposit > 0) {
                Transaction::create([
                    'type' => 'expense',
                    'category' => 'deposit',
                    'amount' => $reservation->deposit,
                    'description' => "Pengembalian Deposit (Dibatalkan) - Reservasi #{$reservation->id} (Tamu: {$reservation->name})",
                ]);
            }
            
            DB::commit();

            return redirect()->route('dashboard.reservations.cancelled')->with('success', 'Reservasi berhasil dibatalkan. Kamar kembali tersedia.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}