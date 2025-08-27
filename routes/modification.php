
<?php

use App\Livewire\Modifications\Actions\CreateModification;
use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SearchingForSite;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('modification')->group(function () {

   
    Route::get('create', CreateModification::class)->name('modification.create');
  
});
