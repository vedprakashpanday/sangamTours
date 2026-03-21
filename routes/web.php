<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\VendorController; // 🔥 Naya
use App\Http\Controllers\VehicleController; // 🔥 Naya (Inventory ke liye)
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OfferController;




// Home redirect
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    
    // --- 0. Dashboard ---
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- 1. Tour Packages Management ---
    Route::get('packages/restore-all', [TourPackageController::class, 'restoreAll']);
    Route::delete('packages/empty-trash', [TourPackageController::class, 'emptyTrash']);
    Route::get('packages/restore/{id}', [TourPackageController::class, 'restore']);
    Route::delete('packages/force-delete/{id}', [TourPackageController::class, 'forceDelete']);
    Route::resource('packages', TourPackageController::class);

    // --- 2. Dependency Dropdowns ---
    Route::get('get-states-by-country/{country}', [TourPackageController::class, 'getStatesByCountry']);
    Route::get('get-cities-by-state/{state}', [TourPackageController::class, 'getCitiesByState']);

    // --- 3. Locations Management ---
    Route::resource('locations', LocationController::class)->except(['create', 'show']); 
    // Note: Agar aapne manual routes likhe hain toh wahi rehne dein, resource clean rehta hai.

    // --- 4. Accommodations Management ---
    Route::get('accommodations/restore-all', [AccommodationController::class, 'restoreAll']);
    Route::delete('accommodations/empty-trash', [AccommodationController::class, 'emptyTrash']);
    Route::get('accommodations/restore/{id}', [AccommodationController::class, 'restore']);
    Route::delete('accommodations/force-delete/{id}', [AccommodationController::class, 'forceDelete']);
    Route::resource('accommodations', AccommodationController::class);

    // --- 5. Customer Management ---
    Route::get('customers/restore-all', [CustomerController::class, 'restoreAll']);
    Route::delete('customers/empty-trash', [CustomerController::class, 'emptyTrash']);
    Route::get('customers/restore/{id}', [CustomerController::class, 'restore']);
    Route::delete('customers/force-delete/{id}', [CustomerController::class, 'forceDelete']);
    Route::post('customers/update-status', [CustomerController::class, 'updateStatus']); 
    Route::resource('customers', CustomerController::class);

    // --- 6. Vendor Management (NEW) ---
    Route::get('vendors/restore-all', [VendorController::class, 'restoreAll']);
    Route::delete('vendors/empty-trash', [VendorController::class, 'emptyTrash']);
    Route::get('vendors/restore/{id}', [VendorController::class, 'restore']);
    Route::delete('vendors/force-delete/{id}', [VendorController::class, 'forceDelete']);
    Route::resource('vendors', VendorController::class);

    // --- 7. Vehicle/Inventory Management (NEW) ---
    Route::get('vehicles/restore-all', [VehicleController::class, 'restoreAll']);
    Route::delete('vehicles/empty-trash', [VehicleController::class, 'emptyTrash']);
    Route::get('vehicles/restore/{id}', [VehicleController::class, 'restore']);
    Route::delete('vehicles/force-delete/{id}', [VehicleController::class, 'forceDelete']);
    Route::resource('vehicles', VehicleController::class);

    // --- 8. Route Management ---
Route::get('routes/restore-all', [RouteController::class, 'restoreAll']);
Route::delete('routes/empty-trash', [RouteController::class, 'emptyTrash']);
Route::get('routes/restore/{id}', [RouteController::class, 'restore']);
Route::delete('routes/force-delete/{id}', [RouteController::class, 'forceDelete']);
Route::resource('routes', RouteController::class);

// --- 9. Booking Management ---
    Route::get('bookings/restore-all', [BookingController::class, 'restoreAll']);
    Route::delete('bookings/empty-trash', [BookingController::class, 'emptyTrash']);
    Route::get('bookings/restore/{id}', [BookingController::class, 'restore']);
    Route::delete('bookings/force-delete/{id}', [BookingController::class, 'forceDelete']);
    Route::resource('bookings', BookingController::class);

    // --- 10. Offers/Discounts Management ---
Route::resource('offers', OfferController::class);
Route::get('offers/get-items/{category}', [OfferController::class, 'getItemsByCategory']);
});