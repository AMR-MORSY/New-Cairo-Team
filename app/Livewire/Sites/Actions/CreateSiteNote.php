<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteNote;
use App\Models\Site\SiteNoticeType;
use Livewire\Attributes\Title;
use App\Livewire\Forms\SiteNoteForm;
use Illuminate\Support\Collection;
use Toaster;

class CreateSiteNote extends Component
{
    #[Title('create Site Note')]
    public $site;

    public SiteNoteForm $form;

    public Collection $siteNoticeTypes;

    public string $page="create-site-note";
    public function mount(Site $site, SiteNoticeType $siteNoticeType)
    {
         $this->authorize('create', Site::class);
        $this->site = $site;

        $this->siteNoticeTypes=$siteNoticeType->active()->get();

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
