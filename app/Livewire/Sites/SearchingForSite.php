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

    public $target;


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
        return redirect()->route('site.searched.sites',$search);
     




        $this->search='';
     


    }
}
