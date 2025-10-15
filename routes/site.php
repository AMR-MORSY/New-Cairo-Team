
<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Sites\Actions\ShowSite;
use App\Livewire\Sites\Actions\SiteData;
use App\Livewire\Sites\SearchingForSite;
use App\Livewire\Sites\Actions\CreateSite;
use App\Livewire\Sites\Actions\UpdateSite;
use App\Livewire\Sites\Actions\ShowBattery;
use App\Livewire\Sites\Actions\CreateBattery;
use App\Livewire\Sites\Actions\SiteBatteries;
use App\Livewire\Sites\Actions\SitePowerData;
use App\Livewire\Sites\Actions\UpdateBattery;
use App\Livewire\Sites\Actions\CreateSiteData;
use App\Livewire\Sites\Actions\UpdateSiteData;
use App\Livewire\Sites\Actions\CreatePowerData;
use App\Livewire\Sites\Actions\SiteNodals;
use App\Livewire\Sites\Actions\UpdatePowerData;

Route::middleware(['auth'])->prefix('site')->group(function () {

  Route::post('/search', SearchingForSite::class)->name('site.search');
  Route::get('show/{site:site_code}', ShowSite::class)->name('site.show');
  Route::get('update/{site:site_code}', UpdateSite::class)->name('site.update');
  Route::get('create', CreateSite::class)->name('site.create');
  Route::get('nodals', SiteNodals::class)->name('site.nodals');
 
  Route::get('batteries/{site:site_code}', SiteBatteries::class)->name('site.batteries');
  Route::get('create/battery/{site:site_code}', CreateBattery::class)->name('battery.create');
  Route::get('show/battery/{battery}', ShowBattery::class)->name('battery.show');
  Route::get('update/battery/{battery}', UpdateBattery::class)->name('battery.update');

  Route::get('powerData/{site:site_code}', SitePowerData::class)->name('site.powerData');
  Route::get('create/powerData/{site:site_code}', CreatePowerData::class)->name('powerData.create');
  Route::get('update/powerData/{powerData}', UpdatePowerData::class)->name('powerData.update');

  Route::get('siteData/{site:site_code}', SiteData::class)->name('site.siteData');
  Route::get('create/siteData/{site:site_code}', CreateSiteData::class)->name('siteData.create');
  Route::get('update/siteData/{powerData}', UpdateSiteData::class)->name('siteData.update');
});
