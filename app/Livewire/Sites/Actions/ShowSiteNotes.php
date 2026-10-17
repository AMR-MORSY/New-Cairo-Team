<?php

namespace App\Livewire\Sites\Actions;

use Livewire\Component;
use App\Models\Site\Site;
use App\Models\Site\SiteNote;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

class ShowSiteNotes extends Component implements HasActions, HasSchemas
{
    use WithPagination;

     use InteractsWithActions;
    use InteractsWithSchemas;

    public $site;


    public string $page="site.notes";
    public function mount(Site $site)
    {
        $this->authorize('viewSiteProperties', $site);
        $this->site = $site;
    }

    /**
     * Filament builds this with no arguments. The note id passed by
     * mountAction('delete', ['notice_id' => ...]) arrives as $arguments.
     */
    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->modalHeading(__('Delete this Note?'))
            ->modalDescription(__('This will permanently delete the note. It cannot be undone.'))
            ->modalSubmitActionLabel(__('Yes, delete it'))
            ->modalIcon('heroicon-o-trash')
            ->modalIconColor('warning')
            ->color('danger')
            // a closure: ->action($this->deleteModification()) would run the
            // delete while the page was still rendering
            ->action(fn(array $arguments) => $this->deleteNote((int) $arguments['notice_id']));
    }

    public function deleteNote(int $notice_id)
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
        $notices = $this->site->notices()->with('siteNoticeType')->paginate(2); //////pagination can not be in mount
        return view('livewire.sites.actions.show-site-notes', ['notices' => $notices]);
    }
}
