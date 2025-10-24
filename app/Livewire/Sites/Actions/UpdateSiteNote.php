<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\SiteNoteForm;
use App\Models\Site\SiteNote;
use Livewire\Attributes\Title;
use Livewire\Component;
use Toaster;

class UpdateSiteNote extends Component
{
    #[Title('update Site Note')]

    public SiteNoteForm $form;

    public $siteNote;

    public $site;

    public function mount(SiteNote $siteNote)
    {
        $this->siteNote=$siteNote;

        $this->site=$siteNote->site;

        $this->form->setSiteNote($siteNote);
    }

    public function updateSiteNotice()
    {
        $validated=$this->form->validate();

        $this->siteNote->update($validated);

        Toaster::success('Updated Successfully');

        return redirect()->route('site.notes',['site'=>$this->site->site_code]);

    }
    public function render()
    {
        return view('livewire.sites.actions.update-site-note');
    }
}
