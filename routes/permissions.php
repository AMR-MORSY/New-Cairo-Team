<?php


use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SearchingForSite;
use App\Livewire\Users\Actions\Permissions;
use App\Livewire\Users\Actions\ShowSingleUser;
use App\Livewire\Users\Actions\ShowUsers;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('permissions')->group(function () {

 
    Route::get('index', Permissions::class)->name('permission.index');
    
     

   
});
