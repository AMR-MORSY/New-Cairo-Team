<?php


use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SearchingForSite;
use App\Livewire\Users\Actions\RolePermissions;
use App\Livewire\Users\Actions\ShowSingleUser;
use App\Livewire\Users\Actions\ShowUsers;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('users')->group(function () {

 
    Route::get('index', ShowUsers::class)->name('users.index');
     Route::get('show/{user}', ShowSingleUser::class)->name('user.show');
   
     

   
});
