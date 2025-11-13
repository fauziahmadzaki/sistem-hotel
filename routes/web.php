<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HousekeepingCheckController;
use App\Http\Controllers\UserController; // (TAMBAHKAN) Import UserController

Route::redirect('/', '/login');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');
    });
    Route::middleware('auth')->group(function () {
        Route::get('/reset-password', 'showResetPassword')->name('reset-password');
        Route::post('/reset-password', 'resetPassword');
        Route::post('/logout', 'logout')->name('logout');
    });
});

Route::middleware(['auth'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        // (DIPERBARUI) Rute Dashboard Admin/Kasir (Default)
        Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.index');
        
        // (DIPERBARUI) Rute Dashboard Superadmin (Statistik)
        Route::get('/superadmin', [DashboardController::class, 'superadmin'])->name('superadmin.index')->middleware('role:superadmin');

        // Rute Profil (Sudah Benar)
        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/update', 'updateProfile')->name('update');
            Route::patch('/password', 'updatePassword')->name('password');
        });

        // Rute Reservasi (Sudah Benar)
        Route::middleware('role:admin,superadmin')->group(function () {
            Route::get('transaction-report', [TransactionController::class, 'report'])->name('transactions.report');
            Route::resource('transactions', TransactionController::class)->except(['show']);
            Route::prefix('reservations')
            ->name('reservations.')
            ->controller(ReservationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index'); 
                Route::get('/create', 'create')->name('create'); 
                Route::post('/', 'store')->name('store'); 
                Route::get('/{reservation}/edit', 'edit')->name('edit');
                Route::put('/{reservation}', 'update')->name('update'); 
                Route::get('/completed', 'completed')->name('completed');
                Route::get('/cancelled', 'cancelled')->name('cancelled'); 
                Route::get('/{reservation}/checkout', 'showCheckForm')->name('checkout.form'); 
                Route::post('/{reservation}/request-check', 'requestHousekeepingCheck')->name('request_check');
                Route::post('/{reservation}/checkout', 'processCheckout')->name('checkout.process'); 
                Route::post('/{reservation}/cancel', 'cancel')->name('cancel'); 
                Route::get('/{reservation}', 'show')->name('show'); 
                Route::get('/{reservation}/invoice', 'invoice')->name('invoice');
            });
        });
        
        // Rute Housekeeping (PERBAIKI NAMA CONTROLLER)
        Route::middleware('role:superadmin,housekeeper')->prefix('housekeeping')
            ->name('housekeeping.')
            ->controller(HousekeepingCheckController::class) // Nama controller diperbaiki
            ->group(function () {
                Route::get('/', 'index')->name('index'); 
                Route::get('/completed', 'completed')->name('completed');
                Route::get('/check/{check}', 'showCheckForm')->name('check.form');
                Route::post('/check/{check}', 'processCheck')->name('check.process'); 
                Route::post('/clean/{room}', 'markAsCleaned')->name('clean.process'); 
            });

        Route::middleware('role:superadmin')->group(function () {
            Route::resource('facilities', FacilityController::class)->except(['show']);
            Route::resource('room-types', RoomTypeController::class)->except(['show']);
            Route::resource('rooms', RoomController::class)->except(['show']);
            Route::resource('users', UserController::class)->except(['show']);
        });

        
        
       
    });