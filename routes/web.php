<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Admin Protected Routes
Route::middleware(['auth'])->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Services Page
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/{booking}', [ServicesController::class, 'show'])->name('services.show');
    Route::patch('/services/{booking}/status', [ServicesController::class, 'updateStatus'])->name('services.status');
    Route::delete('/services/{booking}', [ServicesController::class, 'destroy'])->name('services.destroy');

    // 3. Pricing Management Page
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
    Route::post('/pricing/main-module', [PricingController::class, 'storeMainModule'])->name('pricing.main-module.store');
    Route::put('/pricing/main-module/{module}', [PricingController::class, 'updateMainModule'])->name('pricing.main-module.update');
    Route::delete('/pricing/main-module/{module}', [PricingController::class, 'destroyMainModule'])->name('pricing.main-module.destroy');
    
    Route::post('/pricing/sub-module', [PricingController::class, 'storeSubModule'])->name('pricing.sub-module.store');
    Route::put('/pricing/sub-module/{subModule}', [PricingController::class, 'updateSubModule'])->name('pricing.sub-module.update');
    Route::delete('/pricing/sub-module/{subModule}', [PricingController::class, 'destroySubModule'])->name('pricing.sub-module.destroy');

    Route::post('/pricing/service-item', [PricingController::class, 'storeServiceItem'])->name('pricing.service-item.store');
    Route::put('/pricing/service-item/{serviceItem}', [PricingController::class, 'updateServiceItem'])->name('pricing.service-item.update');
    Route::delete('/pricing/service-item/{serviceItem}', [PricingController::class, 'destroyServiceItem'])->name('pricing.service-item.destroy');

    // 4. Customers Page
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // 5. Booking Flow Page (Wizard)
    Route::get('/booking/wizard', [BookingController::class, 'create'])->name('booking.wizard');
    Route::post('/booking/store-lead', [BookingController::class, 'storeLead'])->name('booking.store-lead');
    Route::post('/booking/store-quotation', [BookingController::class, 'storeQuotation'])->name('booking.store-quotation');
    Route::get('/booking/{id}/pdf', [BookingController::class, 'downloadPdf'])->name('booking.pdf');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
