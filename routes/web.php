<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodOrderController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\FoodCategoryController;

use Illuminate\Support\Facades\Route;

// Public Routes 
Route::get('/', [HomeController::class, 'show'])
    ->name('welcomepage');

// Room 
Route::get('/rooms', [RoomController::class, 'list'])
    ->name('rooms.list');
Route::get('/rooms/{room}', [RoomController::class, 'details'])
    ->name('rooms.details');

// About Us 
Route::get('/about-us', [AboutController::class, 'show'])
    ->name('about.us');

// Event (public list; detail route is registered at the bottom so admin /events/* routes match first)
Route::get('/events/list', [EventController::class, 'show'])->name('events.list');

// Contacts 
Route::get('/contact-us', [ContactController::class, 'show'])
    ->name('contact.us');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication
Route::middleware('auth')->group(function () {

    // Dashboard — all roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ══ Admin + Manager ══
    Route::middleware('role:admin,manager')->group(function () {

        // About Us
        Route::get('about', [AboutController::class, 'index'])->name('about.index');
        Route::put('about', [AboutController::class, 'update'])->name('about.update');

        // Rooms
        Route::resource('admin/rooms', RoomController::class)->names('admin.rooms');

        // Events
        Route::get('/events',              [EventController::class, 'index'])->name('events.index');
        Route::get('/events/create',       [EventController::class, 'create'])->name('events.create');
        Route::post('/events',             [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}',      [EventController::class, 'update'])->name('events.update');

        // Food — Categories & Items (admin + manager only)
        Route::prefix('food')->name('food.')->group(function () {
            Route::get('categories',                   [FoodCategoryController::class, 'index'])->name('categories.index');
            Route::post('categories',                  [FoodCategoryController::class, 'store'])->name('categories.store');
            Route::put('categories/{foodCategory}',    [FoodCategoryController::class, 'update'])->name('categories.update');
            Route::delete('categories/{foodCategory}', [FoodCategoryController::class, 'destroy'])->name('categories.destroy');

            Route::get('items',                [FoodItemController::class, 'index'])->name('items.index');
            Route::post('items',               [FoodItemController::class, 'store'])->name('items.store');
            Route::put('items/{foodItem}',     [FoodItemController::class, 'update'])->name('items.update');
            Route::delete('items/{foodItem}',  [FoodItemController::class, 'destroy'])->name('items.destroy');
        });
    });

    // ══ Admin + Manager + Receptionist ══
    Route::middleware('role:admin,manager,receptionist')->group(function () {

        // Bookings
        Route::resource('bookings', BookingController::class)->names('admin.bookings');
        Route::post('bookings/{booking}/checkin',  [BookingController::class, 'checkin'])->name('admin.bookings.checkin');
        Route::post('bookings/{booking}/checkout', [BookingController::class, 'checkout'])->name('admin.bookings.checkout');

        // Customers
        Route::resource('customers', CustomerController::class);

        // Food — Orders
        Route::prefix('food')->name('food.')->group(function () {
            Route::get('orders',                     [FoodOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/create',              [FoodOrderController::class, 'create'])->name('orders.create');
            Route::post('orders',                    [FoodOrderController::class, 'store'])->name('orders.store');
            Route::get('orders/{foodOrder}',         [FoodOrderController::class, 'show'])->name('orders.show');
            Route::get('orders/{foodOrder}/edit',    [FoodOrderController::class, 'edit'])->name('orders.edit');
            Route::put('orders/{foodOrder}',         [FoodOrderController::class, 'update'])->name('orders.update');
            Route::delete('orders/{foodOrder}',      [FoodOrderController::class, 'destroy'])->name('orders.destroy');
            Route::post('orders/{foodOrder}/status', [FoodOrderController::class, 'updateStatus'])->name('orders.status');
            Route::get('orders/{foodOrder}/print',   [FoodOrderController::class, 'print'])->name('orders.print');
        });

        // Contacts
        Route::get('/contacts',                  [ContactController::class, 'index'])->name('contacts.index');
        Route::post('/contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
        Route::delete('/contacts/{contact}',     [ContactController::class, 'delete'])->name('contacts.delete');
    });

    // ══ Admin + Manager + Accountant ══
    Route::middleware('role:admin,manager,accountant')->group(function () {
        Route::resource('billing', BillController::class)->parameters(['billing' => 'bill']);
        Route::get('billing/{bill}/print', [BillController::class, 'print'])->name('billing.print');
    });

    // ══ Admin only ══
    Route::middleware('role:admin')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');
    });
});

// Public event detail — registered LAST so it doesn't shadow admin /events/create etc.
Route::get('/events/{event}', [EventController::class, 'details'])->name('event.detail');

require __DIR__ . '/auth.php';
