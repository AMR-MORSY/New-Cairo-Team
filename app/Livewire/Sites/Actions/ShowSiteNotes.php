<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteNote;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use WireUi\Traits\WireUiActions;

class ShowSiteNotes extends Component
{
    use WithPagination;

    use WireUiActions;

    public $site;


    public function mount(Site $site)
    {
        $this->site = $site;
    }

    public function delete($notice_id)
    {


        $this->authorize('update', $this->site);
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Delete this modification?',
            'acceptLabel' => 'Yes, delete it',
            'method' => 'deleteNote',
            'params' => $notice_id,
        ]);
    }


    public function deleteNote($notice_id)
    {
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
