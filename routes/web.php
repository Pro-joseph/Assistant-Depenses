<?php

use App\Http\Controllers\DepenseController;
use App\Http\Controllers\RecuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('recus', RecuController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('depenses', DepenseController::class)->only(['index']);
});

require __DIR__.'/auth.php';
