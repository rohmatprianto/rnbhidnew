<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\EventphotoController;
use App\Http\Controllers\EventPhotoOrderController;
use App\Http\Controllers\AdminEventPhotoBookingController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('landingpage');
})->name('home');

Route::get('/eventphoto', [EventPhotoOrderController::class, 'index'])->name('eventphoto.index');
Route::get('/eventphoto/{eventPhoto}/order', [EventPhotoOrderController::class, 'create'])->name('eventphoto.order.create');
Route::post('/eventphoto/{eventPhoto}/order', [EventPhotoOrderController::class, 'store'])->name('eventphoto.order.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['title' => 'Dashboard']);
    })->name('dashboard');

    /**
     * ADMIN ONLY
     */
    Route::middleware('can:viewAny,' . User::class)->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->middleware('can:view,user')
            ->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('can:update,user')
            ->name('users.edit');
        Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])
            ->middleware('can:update,user')
            ->name('users.update');
        Route::patch('/users/{user}/photo', [UserController::class, 'updatePhoto'])
            ->middleware('can:update,user')
            ->name('users.photo.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status.update');
    });

    Route::middleware(['auth', 'can:viewAny,' . User::class])->group(function () {
        // Admin list
        Route::get('/event', [EventphotoController::class, 'indexlist'])->name('event.eventphoto.index');
        Route::get('/event/eventphoto/{event}', [EventphotoController::class, 'show'])->name('event.eventphoto.show');

        // CRUD Admin
        Route::get('/event/create', [EventphotoController::class, 'create'])->name('event.eventphoto.create');
        Route::post('/event/eventphoto', [EventphotoController::class, 'store'])->name('event.eventphoto.store');

        Route::get('/event/eventphoto/{event}/edit', [EventphotoController::class, 'edit'])->name('event.eventphoto.edit');
        Route::put('/event/eventphoto/{event}', [EventphotoController::class, 'update'])->name('event.eventphoto.update');

        Route::delete('/event/eventphoto/{event}', [EventphotoController::class, 'destroy'])->name('event.eventphoto.destroy');

        // Optional: toggle status cepat
        Route::patch('/event/eventphoto/{event}/status', [EventphotoController::class, 'updateStatus'])->name('event.eventphoto.status');
    });

    Route::middleware(['auth', 'can:viewAny,' . User::class])->group(function () {
        // Manage Booking (Admin)
        Route::get('/eventphoto-bookings', [AdminEventPhotoBookingController::class, 'index'])->name('eventphoto.bookings.index');

        Route::get('/eventphoto-bookings/{eventPhoto}', [AdminEventPhotoBookingController::class, 'show'])->name('eventphoto.bookings.show');

        Route::get('/eventphoto-bookings/order/{order}/edit', [AdminEventPhotoBookingController::class, 'editOrder'])
        ->name('eventphoto.bookings.order.edit');

    Route::put('/eventphoto-bookings/order/{order}', [AdminEventPhotoBookingController::class, 'updateOrder'])
        ->name('eventphoto.bookings.order.update');

    Route::delete('/eventphoto-bookings/order/{order}', [AdminEventPhotoBookingController::class, 'destroyOrder'])
        ->name('eventphoto.bookings.order.destroy');

    });
});

require __DIR__ . '/auth.php';
