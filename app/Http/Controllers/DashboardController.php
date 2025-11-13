<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\View\View;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Reservation; 
use Illuminate\Support\Carbon; 

class DashboardController extends Controller
{
 
  public function superadmin(): View
    {
        
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        
        $totalReservationsCompleted = Reservation::where('status', 'completed')->count();
        $totalGuestsServed = Reservation::where('status', 'completed')->sum('total_guests');

        
        $totalRooms = Room::count();
        $totalStaff = User::whereIn('role', ['admin', 'housekeeper'])->count(); 

        return view('dashboard.index', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'totalReservationsCompleted',
            'totalGuestsServed',
            'totalRooms',
            'totalStaff'
        ));
    }


 public function admin(): View
    {
        $currentCheckins = Reservation::with('room')
            ->where('status', 'checkin')
            ->latest()
            ->get();
        $pendingCheckouts = Reservation::with('room')
            ->where('status', 'pending')
            ->latest()
            ->get();
        $stats = [
            'active_guests' => Reservation::where('status', 'checkin')->count(),
            'available_rooms' => \App\Models\Room::where('room_status', 'available')->count(),
            'dirty_rooms' => \App\Models\Room::where('room_status', 'maintenance')->count(),
        ];

        return view('dashboard.admin', compact( // Tetap menggunakan view 'dashboard.index'
            'currentCheckins', // DIPERBARUI
            'pendingCheckouts',
            'stats'
        ));
    }
}