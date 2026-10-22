<?php

namespace App\Livewire\Sites\Actions;

use App\Enums\Teams;
use App\Enums\Zones;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Site\Site;
use Masmerise\Toaster\Toast;
use Masmerise\Toaster\Toaster;
use App\Livewire\Forms\SiteForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

#[Title('create Site')]
class CreateSite extends Component
{
    public SiteForm $form;

    public array $zones;
    public array $teams;

   

    public string $page = "site.create";
    public function mount()
    {

        $this->authorize('create', Site::class);
       
      
        $this->zones = Zones::cases();
        $this->teams = Teams::cases();
    }

    public function create()
    {
        // the rules cover exactly the columns a new site is created with
        $site = Site::create($this->form->validatedForSave());

        Toaster::success('Inserted Successfully');

        return redirect()->route('site.show', ['site' => $site->site_code]);
    }

    
    public function render()
    {
        return view('livewire.sites.actions.create-site');
    }
}
