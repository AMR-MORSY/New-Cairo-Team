<?php

namespace App\Livewire\Modifications\Actions;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use Illuminate\Support\Collection;
use App\Models\Modification\Action;
use App\Models\Modification\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\Modification\Requester;
use App\Models\Modification\Modification;
use App\Models\Modification\Subcontractor;
use App\Models\Modification\ModificationStatus;
use Toaster;

class ModificationSearch extends Component
{
    public array $categories = [];

    public Collection| array $options = [];
    public  $category = '';
    public  $option = '';

    public $team_id;

    public $date_from = '';
    public $date_to = '';
    public $selected_date = '';
    public $dates = [];
    public Collection $modifications;

    public $work_order;

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


        $this->team_id = getPermissionsTeamId();

        $this->dates = [

            ['id' => 'request_date', 'name' => "Request Date"],
            ['id' => 'd6_date', 'name' => 'D6 date'],
            ['id' => 'cw_date', 'name' => 'CW date']
        ];
    }

    private function getCategoryOptions($value)
    {

        if ($value == 'status') {
            $this->options = ModificationStatus::all();
            return;
        } elseif ($value == 'requester') {
            $this->options = Requester::all();
            return;
        } elseif ($value == 'subcontractor') {
            $this->options = Subcontractor::all();
            return;
        } elseif ($value == 'project') {
            $this->options = Project::all();
            return;
        } elseif ($value == 'actions') {
            $this->options = Action::all();
            return;
        } elseif ($value == 'reported') {
            $this->options = [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => "Yes"]];
            return;
        }
        // dd($value);
    }

    private function getModificationsByCategory($category, $option, $team_id = null)
    {
        $modifications = collect();

        if ($category == 'status') {

            if ($team_id) {
                $modifications = Modification::where('modification_status_id', $option)->where('team_id', $team_id)->get();
            } else {
                $modifications = Modification::where('modification_status_id', $option)->get();
            }
        } elseif ($category == 'requester') {

            if ($team_id) {
                $modifications = Modification::where('requester_id', $option)->where('team_id', $team_id)->get();
            } else {
                $modifications = Modification::where('requester_id', $option)->get();
            }
        } elseif ($category == 'subcontractor') {

            if ($team_id) {
                $modifications = Modification::where('subcontractor_id', $option)->where('team_id', $team_id)->get();
            } else {
                $modifications = Modification::where('subcontractor_id', $option)->get();
            }
        } elseif ($category == 'project') {

            if ($team_id) {
                $modifications = Modification::where('project_id', $option)->where('team_id', $team_id)->get();
            } else {
                $modifications = Modification::where('project_id', $option)->get();
            }
        } elseif ($category == 'action') {

            if ($team_id) {
                $modifications = Modification::where('action_id', $option)->where('team_id', $team_id)->get();
            } else {
                $modifications = Modification::where('action_id', $option)->get();
            }
        } elseif ($category == 'reported') {

            if ($team_id) {
                $modifications = Modification::where('reported', $option)->where('team_id', $team_id)->get();
            } else {
                $modifications = Modification::where('reported', $option)->get();
            }
        }
        return $modifications;
    }

    public function updatedCategory($value)
    {


        $this->getCategoryOptions($value);
    }
    public function searchByCategory()
    {
        $validated =  $this->validate([
            'option' => ['required', 'integer'],
            'category' => ['required', "in:status,requester,subcontractor,project,actions,reported"]
        ]);

        $user = Auth::user();

        setPermissionsTeamId($this->team_id);



        $this->modifications = collect();
        if ($user->isModificationAdmin()) {

            $this->modifications = $this->getModificationsByCategory($validated['category'], $validated['option']);
        } elseif ($this->team_id) {
            $this->modifications = $this->getModificationsByCategory($validated['category'], $validated['option'], $this->team_id);
        }

        // dd($this->modifications);

        $this->dispatch('modificationsFound', modifications: $this->modifications);
    }



    public function date()
    {
        $validated = $this->validate([
            'date_from' => 'required|date',
            'selected_date' => 'required|in:request_date,d6_date,cw_date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);



        $user = Auth::user();
        setPermissionsTeamId($this->team_id);
        $this->modifications = collect();
        if ($user->isModificationAdmin()) {

            if ($validated['date_to'] == '') {
                $this->modifications = Modification::where($validated['selected_date'], '>=', $validated['date_from'])->get();
            } else {
                $this->modifications = Modification::where($validated['selected_date'], '>=', $validated['date_from'])->where($validated['selected_date'], '<=', $validated['date_to'])->get();
            }
        } elseif ($this->team_id) {
            if ($validated['date_to'] == '') {
                $this->modifications = Modification::where('team_id', $this->team_id)->where($validated['selected_date'], '>=', $validated['date_from'])->get();
            } else {
                $this->modifications = Modification::where('team_id', $this->team_id)->where($validated['selected_date'], '>=', $validated['date_from'])->where($validated['selected_date'], '<=', $validated['date_to'])->get();
            }
        }

       


        // dd($this->modifications);

        $this->dispatch('modificationsFound', modifications: $this->modifications);
    }

    public function searchWO()
    {
         $validated = $this->validate([
            'work_order' => ['required', 'regex:/^(cn|CN|gz|GZ|CE|ce|cs|CS|si|SI|NU|nu|SU|su|RE|re|AL|al|DS|ds|DN|dn|NC|nc)[-]\d{3,8}$/'],
           
        ]);

         $user = Auth::user();
        setPermissionsTeamId($this->team_id);
        $this->modifications = collect();
        if ($user->isModificationAdmin()) {
            $this->modifications = Modification::where('wo_code', $validated['work_order'])->get();

        }
        elseif ($this->team_id) {
            $modification_team=Modification::where('wo_code', $validated['work_order'])->first()->team_id;
            if($modification_team==$this->team_id)
            {
                $this->modifications = Modification::where('wo_code', $validated['work_order'])->get();
            }
            else{
                Toaster::error('You do not belong to this team to view this modification action. ')->duration(7000);
            }
          
        }
           $this->dispatch('modificationsFound', modifications: $this->modifications);


    }
    public function render()
    {
        return view('livewire.modifications.actions.modification-search');
    }
}
