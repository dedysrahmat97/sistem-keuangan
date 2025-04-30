<?php

use App\Livewire\Pages\HomeIndex;
use App\Livewire\Pages\NeracaIndex;
use App\Livewire\Pages\LabaRugiIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.dashboard.auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/buku-besar', HomeIndex::class)->name('home');
    Route::get('/neraca', NeracaIndex::class)->name('neraca');
    Route::get('/laba-rugi', LabaRugiIndex::class)->name('laba-rugi');
    
});

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';