<?php

namespace App\Livewire\Modifications\Actions;

use Toaster;
use App\Enums\Zones;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;
use App\Models\Modification\Action;
use App\Models\Modification\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\Modification\Requester;
use App\Models\Modification\Modification;
use App\Models\Modification\Subcontractor;
use App\Enums\ModificationStatus;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

#[Title('Modification Search')]
class ModificationSearch extends Component
{
    /** Category => the modifications column its option is matched against. Actions go through a pivot, so they are handled apart. */
    private const CATEGORY_COLUMNS = [
        'status' => 'status',
        'requester' => 'requester_id',
        'subcontractor' => 'subcontractor_id',
        'project' => 'project_id',
        'reported' => 'reported',
    ];

    public array $categories = [];

    public Collection| array $options = [];
    public  $category = '';
    public  $option = '';

    public $team;

    public $date_from = '';
    public $date_to = '';
    public $selected_date = '';
    public $dates = [];
    public Collection $modifications;

    public $work_order='';

    public function mount()
    {
        $this->authorize('modificationSearch', Modification::class);
        $this->categories = [
            ['id' => 'status', 'name' => 'Status'],
            ['id' => 'requester', 'name' => 'Requester'],
            ['id' => 'subcontractor', 'name' => 'Subcontractor'],
            ['id' => 'project', 'name' => 'Project'],
            ['id' => 'actions', 'name' => 'Actions'],
            ['id' => 'reported', 'name' => 'Reported'],
        ];


        $this->team = Auth::user()?->team?->value;

        $this->dates = [

            ['id' => 'request_date', 'name' => "Request Date"],
            ['id' => 'd6_date', 'name' => 'D6 date'],
            ['id' => 'cw_date', 'name' => 'CW date']
        ];
    }

    private function clearSearchFields()
    {
        $this->category = '';
        $this->option = '';
        $this->options = [];
        $this->date_from = '';
        $this->date_to = '';
        $this->selected_date = '';
        $this->work_order='';
    }
    private function getCategoryOptions($value)
    {
        $this->options = match ($value) {
            'status' => ModificationStatus::options(),
            'requester' => Requester::all(),
            'subcontractor' => Subcontractor::all(),
            'project' => Project::all(),
            'actions' => Action::all(),
            'reported' => [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => "Yes"]],
            default => [],
        };
    }

    /**
     * Modifications the current user may search: every team for a
     * modification admin, otherwise only their own team. Null when the user
     * has neither, so callers return no results.
     */
    private function searchableModifications(): ?Builder
    {
        if (Auth::user()->isModificationAdmin()) {
            return Modification::query();
        }

        return $this->team ? Modification::where('team', $this->team) : null;
    }

    /** Hand the results to the table as ids; it loads them with their relations. */
    private function showResults(Collection $modifications)
    {
        $this->modifications = $modifications;

        $this->clearSearchFields();

        $this->dispatch('modificationsFound', ids: $modifications->pluck('id')->all());
    }

    public function updatedCategory($value)
    {
        $this->getCategoryOptions($value);
    }
    public function searchByCategory()
    {
        $validated =  $this->validate([
            // status options are the enum's string values; the rest are table ids
            'option' => ['required', $this->category === 'status' ? Rule::enum(ModificationStatus::class) : 'integer'],
            'category' => ['required', Rule::in(array_column($this->categories, 'id'))]
        ]);

        $query = $this->searchableModifications();

        if (!$query) {
            return $this->showResults(collect());
        }

        if ($validated['category'] === 'actions') {
            // actions are many-to-many through action_modification, not a column
            $query->whereHas('actions', fn($q) => $q->whereKey($validated['option']));
        } else {
            $query->where(self::CATEGORY_COLUMNS[$validated['category']], $validated['option']);
        }

        $this->showResults($query->get());
    }



    public function date()
    {
        $validated = $this->validate([
            'date_from' => 'required|date',
            'selected_date' => 'required|in:request_date,d6_date,cw_date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        $query = $this->searchableModifications();

        if (!$query) {
            return $this->showResults(collect());
        }

        $column = $validated['selected_date'];

        // whereDate compares the day only, so a value stored with a time part still matches its To day
        $query->whereDate($column, '>=', $validated['date_from'])
            ->when($validated['date_to'] ?? null, fn($q, $to) => $q->whereDate($column, '<=', $to));

        $this->showResults($query->get());
    }

    public function searchWO()
    {
        // prefixes come from the zones so a new zone needs no change here
        $zones = implode('|', array_column(Zones::cases(), 'value'));

         $validated = $this->validate([
            'work_order' => ['required', "regex:/^($zones)-\d{3,8}$/i"],
        ]);

        // wo_codes are stored upper case; the regex accepts either
        $workOrder = strtoupper($validated['work_order']);

        // looked up without the team scope, so "not found" and "not your team" read differently
        $modification = Modification::where('wo_code', $workOrder)->first();
        $found = collect();

        if (!$modification) {
            Toaster::error('No modification found with this work order.')->duration(7000);
        } elseif (!Auth::user()->isModificationAdmin() && $modification->team?->value !== $this->team) {
            Toaster::error('You do not belong to this team to view this modification action. ')->duration(7000);
        } else {
            $found = collect([$modification]);
        }

        $this->showResults($found);
    }
    public function render()
    {
        return view('livewire.modifications.actions.modification-search');
    }
}
