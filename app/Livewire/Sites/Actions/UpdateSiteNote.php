<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\SiteNoteForm;
use App\Models\Site\Site;
use App\Models\Site\SiteNoticeType;
use App\Models\Site\SiteNote;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Toaster;

#[Title('Update Site Note')]
class UpdateSiteNote extends Component
{
    public SiteNoteForm $form;

    public SiteNote $siteNote;

    public Site $site;

    public Collection $siteNoticeTypes;

    public string $page="site.note.update";

    public function mount(SiteNote $siteNote, SiteNoticeType $siteNoticeType)
    {
         $this->authorize('update', $siteNote->site);
        $this->siteNote=$siteNote;



        $this->siteNoticeTypes=$siteNoticeType->activeOr($this->siteNote->site_notice_type_id)->get();

      

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
