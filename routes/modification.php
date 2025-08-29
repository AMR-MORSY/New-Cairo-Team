
<?php

use App\Livewire\Modifications\Actions\CreateModification;
use App\Livewire\Modifications\Actions\ModificationDetails;
use App\Livewire\Modifications\Actions\ShowModification;
use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SearchingForSite;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('modification')->group(function () {


    Route::get('create/{site:site_code}', CreateModification::class)->name('modification.create');
    Route::get('show/{site:site_code}', ShowModification::class)->name('modification.show');
    Route::get('details/{modification}', ModificationDetails::class)->name('modification.details');
});
