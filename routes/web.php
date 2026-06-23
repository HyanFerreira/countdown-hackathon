<?php

use App\Livewire\Admin\AdminPanel;
use App\Livewire\Countdown\PublicCountdown;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicCountdown::class)->name('countdown.public');
Route::get('/admin', AdminPanel::class)->name('admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
