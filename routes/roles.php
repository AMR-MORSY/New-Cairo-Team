<?php


use App\Livewire\Users\Actions\Roles;
use Illuminate\Support\Facades\Route;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\SearchingForSite;
use App\Livewire\Users\Actions\ShowUsers;
use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Users\Actions\Permissions;
use App\Livewire\Users\Actions\ShowSingleUser;
use App\Livewire\Users\Actions\RolePermissions;

Route::middleware(['auth'])->prefix('roles')->group(function () {


    Route::get('index', Roles::class)->name('role.index');
    Route::get("permissions/{role}", RolePermissions::class)->name("role.permissions");
});
