<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteNote;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class ShowSiteNotes extends Component
{
    use WithPagination;

    public $site;


    public function mount(Site $site)
    {
        $this->authorize('viewSiteProperties', $site);
        $this->site = $site;
    }

    public function deleteNote($notice_id)
    {
        $this->authorize('update', $this->site);

        $notice = SiteNote::findOrFail($notice_id);

        if ($notice->site_code != $this->site->site_code) {
            abort(403);
        }
        $notice->delete();
        return redirect()->route('site.notes', ['site' => $this->site->site_code]);
    }

    public function render()
    {
        $notices = $this->site->notices()->paginate(2); //////pagination can not be in mount
        return view('livewire.sites.actions.show-site-notes', ['notices' => $notices]);
    }
}
