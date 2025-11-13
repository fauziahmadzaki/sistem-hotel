<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::latest();

        $query->when($request->type, function ($q, $type) {
            return $q->where('type', $type);
        });

        $query->when($request->category, function ($q, $category) {
            return $q->where('category', $category);
        });

        $query->when($request->start_date, function ($q, $date) {
            return $q->whereDate('created_at', '>=', $date);
        });

        $query->when($request->end_date, function ($q, $date) {
            return $q->whereDate('created_at', '<=', $date);
        });

        $filteredQuery = $query->clone();
        $transactions = $query->paginate(15)->withQueryString();

        $totalIncome = $filteredQuery->clone()->where('type', 'income')->sum('amount');
        $totalExpense = $filteredQuery->clone()->where('type', 'expense')->sum('amount');
        $netTotal = $totalIncome - $totalExpense;

        return view('dashboard.transactions.index', compact(
            'transactions', 
            'totalIncome', 
            'totalExpense', 
            'netTotal'
        ));
    }

    public function report(Request $request)
    {
 
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
 
        $startTime = $request->input('start_time', '00:00');
        $endTime = $request->input('end_time', '23:59');

        $startDateTime = $date->copy()->setTimeFromTimeString($startTime);
        $endDateTime = $date->copy()->setTimeFromTimeString($endTime);
 
        $transactions = Transaction::whereBetween('created_at', [$startDateTime, $endDateTime])->get();

        $rentalIncome = $transactions->where('type', 'income')->where('category', 'rental')->sum('amount');
        $depositIncome = $transactions->where('type', 'income')->where('category', 'deposit')->sum('amount');
        $finesIncome = $transactions->where('type', 'income')->where('category', 'other')->sum('amount'); // Asumsi denda = other
        $maintenanceIncome = $transactions->where('type', 'income')->where('category', 'maintenance')->sum('amount');
        $otherIncome = $transactions->where('type', 'income')->whereNotIn('category', ['rental', 'deposit', 'other', 'maintenance'])->sum('amount');


        $depositExpense = $transactions->where('type', 'expense')->where('category', 'deposit')->sum('amount');
        $maintenanceExpense = $transactions->where('type', 'expense')->where('category', 'maintenance')->sum('amount');
        $otherExpense = $transactions->where('type', 'expense')->whereNotIn('category', ['deposit', 'maintenance'])->sum('amount');

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('dashboard.transactions.report', compact(
            'date',
            'startTime', 
            'endTime', 
            'rentalIncome', 'depositIncome', 'finesIncome', 'maintenanceIncome', 'otherIncome',
            'depositExpense', 'maintenanceExpense', 'otherExpense',
            'totalIncome', 'totalExpense', 'netProfit'
        ));
    }

    public function create()
    {
        return view('dashboard.transactions.create');
    }


    public function store(StoreTransactionRequest $request)
    {
        Transaction::create($request->validated());
        return redirect()->route('dashboard.transactions.index')->with('success', 'Transaksi baru berhasil dicatat.');
    }

    public function edit(Transaction $transaction)
    {
       
        if (in_array($transaction->category, ['rental', 'deposit'])) {
             return back()->with('error', 'Transaksi ' . $transaction->category . ' tidak dapat diubah secara manual.');
        }
        return view('dashboard.transactions.edit', compact('transaction'));
    }

    
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return redirect()->route('dashboard.transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
      
        if (in_array($transaction->category, ['rental', 'deposit'])) {
            return back()->with('error', 'Transaksi ' . $transaction->category . ' tidak dapat dihapus secara manual.');
        }
        $transaction->delete();
        return redirect()->route('dashboard.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}