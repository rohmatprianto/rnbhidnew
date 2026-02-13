<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', ['title' => 'Dashboard']);
    })->name('dashboard');

    Route::get('/my-account/profile', function (Request $request) {
        return redirect()->route('users.show', $request->user());
    })->name('my.profile.show');

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
});

require __DIR__ . '/auth.php';
