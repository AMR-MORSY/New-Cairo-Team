
<?php

use App\Livewire\Modifications\POs;
use Illuminate\Support\Facades\Route;
use App\Models\Modification\PurchaseOrder;
use App\Livewire\Modifications\Actions\CreatePO;
use App\Livewire\Modifications\Actions\QuotationCreate;
use App\Livewire\Modifications\Actions\QuotationUpdate;
use App\Livewire\Modifications\Actions\SubmitQuotation;
use App\Livewire\Modifications\Actions\QuotationDetails;
use App\Livewire\Modifications\Actions\SiteModifications;
use App\Livewire\Modifications\Actions\CreateModification;
use App\Livewire\Modifications\Actions\DeleteModification;
use App\Livewire\Modifications\Actions\UpdateModification;
use App\Livewire\Modifications\Actions\ModificationDetails;
use App\Livewire\Modifications\Actions\ModificationSearch;
use App\Livewire\Modifications\Actions\ViewPurchaseOrderModifications;
use App\Livewire\Modifications\Actions\ViewPurchaseOrderInvoicedModifications;
use App\Livewire\Modifications\Actions\ViewPurchaseOrderInprogressModifications;
use App\Models\Modification\Modification;

Route::middleware(['auth','verified','team.member'])->prefix('modification')->group(function () {


    Route::get('create/{site:site_code}', CreateModification::class)->can('createSiteModifications', 'site')->name('modification.create');
    Route::get('show/{site:site_code}', SiteModifications::class)->can('viewSiteModifications', 'site')->name('site.modifications');
    Route::get('details/{modification}', ModificationDetails::class)->can('viewModificationDetails', 'modification')->name('modification.details');
    Route::get('update/{modification}', UpdateModification::class)->can('updateModification', 'modification')->name('modification.update');
    Route::get('quotation/{modification}', QuotationDetails::class)->can('viewModificationDetails', 'modification')->name('quotation.details');
    Route::get('create/quotation/{modification}', QuotationCreate::class)->can('updateModification', 'modification')->name('quotation.create');
    Route::get('update/quotation/{quotation}', QuotationUpdate::class)->can('updateQuotation', 'quotation')->name('quotation.update');
    Route::get('search', ModificationSearch::class)->name('modification.search');
});


Route::middleware(['auth', 'verified'])->prefix('po')->group(function () {

    Route::get('pos', POs::class)->can('viewPOs', PurchaseOrder::class)->name('pos');
    Route::get('inprogress/modifications/{purchaseOrder}', ViewPurchaseOrderInprogressModifications::class)->can('viewPurchaseOrderModifications', PurchaseOrder::class)->name('po.inprogress.modifications');
    Route::get('invoiced/modifications/{purchaseOrder}', ViewPurchaseOrderInvoicedModifications::class)->can('viewPurchaseOrderModifications', PurchaseOrder::class)->name('po.invoiced.modifications');
});
