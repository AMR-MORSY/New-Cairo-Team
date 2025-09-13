
<?php


use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SearchingForSite;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','team.member','team.permission:view_sites'])->prefix('site')->group(function () {

    Route::post('/search', SearchingForSite::class)->name('site.search');
    Route::get('show/{site:site_code}', ShowSite::class)->name('site.show');
    Route::get('update/{site:site_code}', UpdateSite::class)->name('site.update');
    Route::get('create', CreateSite::class)->name('site.create');
});
