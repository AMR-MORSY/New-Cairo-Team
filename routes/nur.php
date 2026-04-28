<?php

use App\Livewire\NUR\InsertNur;
use App\Livewire\NUR\NUR;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'team.member'])->prefix('nur')->group(function () {

    Route::get('/', NUR::class)->name('nur');
    Route::get('/insert/{technology}', InsertNur::class)->name('nur.insert');
});
