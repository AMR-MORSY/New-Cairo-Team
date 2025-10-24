<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteNote;
use Livewire\Attributes\Title;
use App\Livewire\Forms\SiteNoteForm;
use Toaster;

class CreateSiteNote extends Component
{
    #[Title('create Site Note')]
    public $site;

    public SiteNoteForm $form;

    public function mount(Site $site)
    {
        $this->site = $site;

        $this->form->setSiteCode($site);
    }

    public function newSiteNotice()
    {


        $validated = $this->form->validate();



        $siteNote = SiteNote::create($validated);


        Toaster::success('Created Successfully');

        return redirect()->route('site.notes', ['site' => $this->site->site_code]);
    }
    public function render()
    {
        return view('livewire.sites.actions.create-site-note');
    }
}
