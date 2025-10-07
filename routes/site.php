
<?php

use App\Livewire\Sites\Actions\CreateBattery;
use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowBattery;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SearchingForSite;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('site')->group(function () {

    Route::post('/search', SearchingForSite::class)->name('site.search');
    Route::get('show/{site:site_code}', ShowSite::class)->name('site.show');
    Route::get('update/{site:site_code}', UpdateSite::class)->name('site.update');
    Route::get('create', CreateSite::class)->name('site.create');
    Route::get('batteries/{site:site_code}', ShowBattery::class)->name('battery.show');
    Route::get('create/battery/{site:site_code}', CreateBattery::class)->name('battery.create');
});
