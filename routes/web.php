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
// use App\Http\Controllers\ProfileController;
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
Route::get('/events/list',              [EventController::class, 'show'])->name('events.list');
Route::get('/events-details',      [EventController::class, 'details'])->name('event.details');
Route::get('/events/{id}',         [EventController::class, 'details'])->name('event.detail')
    ->where('id', '[0-9]+');

Route::get('/contact-us', [ContactController::class, 'show'])
    ->name('contact.us');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


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
    Route::delete('/events/{event}',   [EventController::class, 'destroy'])->name('events.destroy');

    Route::resource('customers', CustomerController::class);

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

require __DIR__ . '/auth.php';
