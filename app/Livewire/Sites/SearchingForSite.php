<?php

namespace App\Livewire\Sites;

use Livewire\Component;
use App\Models\Site\Site;
use Masmerise\Toaster\Toaster;
use Masmerise\Toaster\Toastable;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component as VoltComponent;

class SearchingForSite extends Component
{
    use Toastable;
    public $search = '';


    public function show()
    {
        $validator = Validator::make(
            // Data to validate...
            ['search' => $this->search],

            // Validation rules to apply...
            ['search' => 'required|min:3'],

        );
        $this->dispatch('openCreateUserModal');
        if ($validator->fails()) {
            $errors = $validator->errors();
            $searchError = $errors->first('search');
            if ($searchError) {
                Toaster::error($searchError);
            }

            throw new ValidationException($validator);
        }
        $validated = $validator->validated();
        $search = $validated['search'];
        $sites = Site::with(['zone','team'])->where('site_code', 'like', "%$search%")->orWhere('site_name', 'like', "%$search%")->get();

      

        if (count($sites) > 0) {
            $props = [
                'title' => "Sites",
                'data' => $sites,

            ];

            // $this->dispatch('openDynamicModal', 'tables.site.searched-sites-table', $props);
            $this->dispatch('openModal', component:'modals.search-result-sites',  arguments: ['props' => $props]);
        }
        else{
              Toaster::info('No data found');

        }




        $this->search='';
     


    }
}
