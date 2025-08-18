
<?php

use App\Http\Controllers\Site\SiteController;
use App\Livewire\CreatePost;
use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\SiteDetails;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('site')->group(function () {

    Route::get('{site:site_code?}', ShowSite::class)->name('site.show');
    Route::get('update/{site:site_code?}', UpdateSite::class)->name('site.update');
     Route::get('create', CreateSite::class)->name('site.create');
    Route::get('post/create',CreatePost::class)->name('post.create');
});
