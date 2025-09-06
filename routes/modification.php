
<?php

use App\Livewire\Modifications\Actions\CreateModification;
use App\Livewire\Modifications\Actions\DeleteModification;
use App\Livewire\Modifications\Actions\ModificationDetails;
use App\Livewire\Modifications\Actions\QuotationCreate;
use App\Livewire\Modifications\Actions\QuotationDetails;
use App\Livewire\Modifications\Actions\QuotationUpdate;
use App\Livewire\Modifications\Actions\SiteModifications;
use App\Livewire\Modifications\Actions\SubmitQuotation;
use App\Livewire\Modifications\Actions\UpdateModification;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('modification')->group(function () {


    Route::get('create/{site:site_code}', CreateModification::class)->name('modification.create');
    Route::get('show/{site:site_code}', SiteModifications::class)->name('site.modifications');
    Route::get('details/{modification}', ModificationDetails::class)->name('modification.details');
    Route::get('update/{modification}', UpdateModification::class)->name('modification.update');
    Route::get('quotation/{modification}', QuotationDetails::class)->name('quotation.details');
    Route::get('create/quotation/{modification}', QuotationCreate::class)->name('quotation.create');
    Route::get('update/quotation/{quotation}', QuotationUpdate::class)->name('quotation.update');
});
