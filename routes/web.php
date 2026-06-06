<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\HeroSectionController;
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

Route::get('/', [HomeController::class, 'show'])
    ->name('welcomepage');

Route::get('/rooms', [RoomController::class, 'list'])
    ->name('rooms.list');

Route::get('/rooms/{id}', [RoomController::class, 'details'])
    ->name('rooms.details');

Route::get('/about-us', [AboutController::class, 'show'])
    ->name('about.us');

// Public Routes
Route::get('/events/list', [EventController::class, 'show'])->name('events.list');
Route::get('/events/{id}', [EventController::class, 'details'])->name('event.detail')
    ->where('id', '[0-9]+');

Route::get('/contact-us', [ContactController::class, 'show'])
    ->name('contact.us');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Hero Section
    // Route::get('/hero/section/list', [HeroSectionController::class, 'index'])->name('hero-section.index');
    // Route::get('/hero/create', [HeroSectionController::class, 'form'])->name('hero.create');
    // Route::get('/hero/edit/{id}', [HeroSectionController::class, 'form'])->name('hero.edit');
    // Route::post('/hero/save/{id?}', [HeroSectionController::class, 'save'])->name('hero.save');
    // Route::delete('/hero/delete/{id}', [HeroSectionController::class, 'destroy'])->name('hero.delete');

    Route::get('about', [AboutController::class, 'index'])->name('about.index');
    Route::put('about', [AboutController::class, 'update'])->name('about.update');

    Route::resource('admin/rooms', RoomController::class)
        ->names('admin.rooms');

    Route::resource('bookings', BookingController::class)
        ->names('admin.bookings');

    Route::post('bookings/{booking}/checkin', [BookingController::class, 'checkin'])
        ->name('admin.bookings.checkin');

    Route::post('bookings/{booking}/checkout', [BookingController::class, 'checkout'])
        ->name('admin.bookings.checkout');

    Route::get('/events',              [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create',       [EventController::class, 'create'])->name('events.create');
    Route::post('/events',             [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',      [EventController::class, 'update'])->name('events.update');
    // Route::delete('/events/{event}',   [EventController::class, 'destroy'])->name('events.destroy');

    Route::resource('customers', CustomerController::class);

    // Auth middleware group 
    Route::prefix('food')->name('food.')->group(function () {
        // Orders
        Route::get('orders',                    [FoodOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create',             [FoodOrderController::class, 'create'])->name('orders.create');
        Route::post('orders',                   [FoodOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{foodOrder}',        [FoodOrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{foodOrder}/edit',   [FoodOrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{foodOrder}',        [FoodOrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{foodOrder}',     [FoodOrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('orders/{foodOrder}/status', [FoodOrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('orders/{foodOrder}/print',  [FoodOrderController::class, 'print'])->name('orders.print');

        Route::get('categories',                      [FoodCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories',                     [FoodCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{foodCategory}',       [FoodCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{foodCategory}',    [FoodCategoryController::class, 'destroy'])->name('categories.destroy');

        // Menu Items
        Route::get('items',                     [FoodItemController::class, 'index'])->name('items.index');
        Route::post('items',                    [FoodItemController::class, 'store'])->name('items.store');
        Route::put('items/{foodItem}',          [FoodItemController::class, 'update'])->name('items.update');
        Route::delete('items/{foodItem}',       [FoodItemController::class, 'destroy'])->name('items.destroy');
    });

    Route::resource('billing', BillController::class)
        ->parameters(['billing' => 'bill']);
    Route::get('billing/{bill}/print', [BillController::class, 'print'])->name('billing.print');


    // ── Settings ─────────────────────────────────────────────
    Route::get('settings',  [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings',  [SettingController::class, 'update'])->name('settings.update');

    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])
        ->name('admin.users.toggle');

    Route::get('/contacts',                [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('/contacts/{contact}',   [ContactController::class, 'delete'])->name('contacts.delete');
});

// Route::get('/ping', fn() => response('ok', 200));

require __DIR__ . '/auth.php';
