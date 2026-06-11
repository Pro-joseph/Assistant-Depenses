<?php

use App\Http\Controllers\DepenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('recus.index');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('/dashboard', '/depenses')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('recus', RecuController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('depenses', DepenseController::class)->only(['index']);
});

require __DIR__.'/auth.php';
