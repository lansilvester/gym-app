<?php

use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InventoryTransactionController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PtBookingController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')
        ->middleware('password.confirm');
    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions');
    Route::delete('/sessions/{sessionId}', [SessionController::class, 'destroy'])->name('sessions.destroy');
});

// Admin routes — only staff roles can access
Route::middleware(['auth', 'role:super_admin|admin|cashier|trainer'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('members', MemberController::class);
    Route::resource('packages', PackageController::class)->except(['show'])->middleware('role:super_admin|admin');
    Route::resource('subscriptions', SubscriptionController::class)->except(['show']);
    Route::resource('trainers', TrainerController::class);

    Route::prefix('checkins')->name('checkins.')->group(function () {
        Route::get('/', [CheckInController::class, 'index'])->name('index');
        Route::post('/', [CheckInController::class, 'store'])->name('store');
        Route::post('{checkIn}/checkout', [CheckInController::class, 'checkOut'])->name('checkout');
    });

    Route::prefix('pt-bookings')->name('pt-bookings.')->group(function () {
        Route::get('/', [PtBookingController::class, 'index'])->name('index');
        Route::post('/', [PtBookingController::class, 'store'])->name('store');
        Route::patch('{ptBooking}/status', [PtBookingController::class, 'updateStatus'])->name('status.update');
    });

    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('payments', PaymentController::class)->only(['index', 'store']);

    Route::resource('inventory', InventoryController::class);
    Route::prefix('inventory-transactions')->name('inventory-transactions.')->group(function () {
        Route::get('/', [InventoryTransactionController::class, 'index'])->name('index');
        Route::post('/', [InventoryTransactionController::class, 'store'])->name('store');
    });

    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('{schedule}', [MaintenanceController::class, 'show'])->name('show');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::patch('{schedule}/status', [MaintenanceController::class, 'updateStatus'])->name('status.update');
        Route::post('{schedule}/log', [MaintenanceController::class, 'logMaintenance'])->name('log');
    });

    Route::resource('roles', RoleController::class)->except(['show'])->middleware('role:super_admin');
});

require __DIR__.'/auth.php';
