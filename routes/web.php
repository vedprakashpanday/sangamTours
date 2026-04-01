<?php

use Illuminate\Support\Facades\Route;

// Controllers ko group karke import karne se spelling mistake pakdi jati hai
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\VendorTypeController; // 🔥 Ye ensure karo ki ye file exists karti hai

/*
|--------------------------------------------------------------------------
| 1. PUBLIC / USER PANEL ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('tours')->name('tours.')->group(function () {
    Route::get('/', [HomeController::class, 'allPackages'])->name('index');
    Route::get('/{slug}', [HomeController::class, 'packageDetail'])->name('detail');
});

Route::get('/search-bus', [HomeController::class, 'searchBus'])->name('bus.search');

/*
|--------------------------------------------------------------------------
| 2. ADMIN AUTH / FORGOT PASSWORD
|--------------------------------------------------------------------------
*/
Route::get('admin/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('admin/reset-password', [ForgotPasswordController::class, 'reset'])->name('admin.password.update');

/*
|--------------------------------------------------------------------------
| 3. ADMIN ROUTES GROUP
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // --- GUEST ROUTES ---
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
        
        Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    });

    // --- AUTH ROUTES ---
    Route::middleware('auth')->group(function () {
        
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Tour Packages
        Route::get('packages/restore-all', [TourPackageController::class, 'restoreAll']);
        Route::delete('packages/empty-trash', [TourPackageController::class, 'emptyTrash']);
        Route::get('packages/restore/{id}', [TourPackageController::class, 'restore']);
        Route::delete('packages/force-delete/{id}', [TourPackageController::class, 'forceDelete']);
        Route::resource('packages', TourPackageController::class);

        // Dependency Dropdowns
        Route::get('get-states-by-country/{country}', [TourPackageController::class, 'getStatesByCountry']);
        Route::get('get-cities-by-state/{state}', [TourPackageController::class, 'getCitiesByState']);

        // Locations
        Route::resource('locations', LocationController::class)->except(['create', 'show']);

        // Accommodations
        Route::get('accommodations/restore-all', [AccommodationController::class, 'restoreAll']);
        Route::delete('accommodations/empty-trash', [AccommodationController::class, 'emptyTrash']);
        Route::get('accommodations/restore/{id}', [AccommodationController::class, 'restore']);
        Route::delete('accommodations/force-delete/{id}', [AccommodationController::class, 'forceDelete']);
        Route::resource('accommodations', AccommodationController::class);

        // Customers
        Route::get('customers/restore-all', [CustomerController::class, 'restoreAll']);
        Route::delete('customers/empty-trash', [CustomerController::class, 'emptyTrash']);
        Route::get('customers/restore/{id}', [CustomerController::class, 'restore']);
        Route::delete('customers/force-delete/{id}', [CustomerController::class, 'forceDelete']);
        Route::post('customers/update-status', [CustomerController::class, 'updateStatus']); 
        Route::resource('customers', CustomerController::class);

        // Vendors
        Route::get('vendors/restore-all', [VendorController::class, 'restoreAll']);
        Route::delete('vendors/empty-trash', [VendorController::class, 'emptyTrash']);
        Route::get('vendors/restore/{id}', [VendorController::class, 'restore']);
        Route::delete('vendors/force-delete/{id}', [VendorController::class, 'forceDelete']);
        Route::resource('vendors', VendorController::class);

        // 🔥 Vendor Type (Simple Entry)
        Route::get('vendor-types', [VendorTypeController::class, 'index'])->name('vendor-types.index');
        Route::post('vendor-types', [VendorTypeController::class, 'store'])->name('vendor-types.store');
        Route::put('vendor-types/{id}', [VendorTypeController::class, 'update'])->name('vendor-types.update');
        Route::delete('vendor-types/{id}', [VendorTypeController::class, 'destroy'])->name('vendor-types.destroy');

        // Vehicles / Inventory
        Route::get('vehicles/restore-all', [VehicleController::class, 'restoreAll']);
        Route::delete('vehicles/empty-trash', [VehicleController::class, 'emptyTrash']);
        Route::get('vehicles/restore/{id}', [VehicleController::class, 'restore']);
        Route::delete('vehicles/force-delete/{id}', [VehicleController::class, 'forceDelete']);
        Route::resource('vehicles', VehicleController::class);

        // Routes
        Route::get('routes/restore-all', [RouteController::class, 'restoreAll']);
        Route::delete('routes/empty-trash', [RouteController::class, 'emptyTrash']);
        Route::get('routes/restore/{id}', [RouteController::class, 'restore']);
        Route::delete('routes/force-delete/{id}', [RouteController::class, 'forceDelete']);
        Route::resource('routes', RouteController::class);

        // Bookings
        Route::resource('bookings', BookingController::class);
        Route::get('check-route', [BookingController::class, 'checkRoute'])->name('bookings.checkRoute');
        Route::get('check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.checkAvailability');
        Route::get('bookings/print/{id}', [BookingController::class, 'printReceipt'])->name('bookings.print');
        
        // Offers
        Route::resource('offers', OfferController::class);
        Route::get('offers/get-items/{category}', [OfferController::class, 'getItemsByCategory']);

        // Schedules
        Route::resource('schedules', ScheduleController::class);
    });
});

// Chatbot Route
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');