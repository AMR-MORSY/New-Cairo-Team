<?php

namespace App\Livewire\Forms;

use Auth;
use Carbon\Carbon;
use Livewire\Form;
use App\Models\Site\Site;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

use App\Rules\CommaSeparatedNumber;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Modification;
use App\Models\Modification\PurchaseOrder;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Modification\ModificationStatus;
use App\Models\Modification\ModificationReservation;

class ModificationForm extends Form
{

    public $id;
    public $site_code = '';
    public $subcontractor_id = '';
    public $pending = "";
    public $est_cost;
    public $final_cost;
    public $request_date = '';
    public $requester_id = '';
    public $project_id = '';
    public $modification_status_id = '';
    public string|null $cw_date = null;
    public string |null $d6_date = null;
    public $description = "";
    public $reported = 0;
    public $reported_at = '';
    public $zone_id = '';
    public $team_id = '';
    public $action_owner = '';
    public $action_id = '';
    public $wo_code = '';

    public $expires_at = '';
    public $reservation_status = '';
    public $is_expired;
    public $activate = "";


    public function setModificationDefaultAttributes($site)
    {
        $this->site_code = $site->site_code;

        $this->action_owner = Auth::user()->id;

        $this->zone_id = $site->zone_id;

        $this->team_id = $site->team_id;
    }

    public function updateFormAttributes()
    {
        return [
            "site_code" ,       
            "action_id" , 
            "description" , 
            "pending" ,
            "request_date" ,
            "cw_date" ,
            "d6_date",
            "modification_status_id" ,
            "requester_id" ,  
            "est_cost" ,
            "reported" ,
            "reported_at",
            'team_id' ,
            'zone_id' ,
            'action_owner'

        ];
    }

    public function setModificationDetails(Modification $modification)
    {
        $this->id = $modification->id;

        $this->site_code = $modification->site_code;
        $this->subcontractor_id = $modification->subcontractor->name;
        $this->pending = $modification->pending;
        $this->est_cost = $modification->est_cost;
        $this->final_cost = $modification->final_cost;
        $this->request_date = $modification->request_date;
        $this->requester_id = $modification->requester_id;
        $this->project_id = $modification->project->name;
        $this->modification_status_id = $modification->modification_status_id;
        $this->cw_date = $modification->cw_date;
        $this->d6_date = $modification->d6_date;
        $this->description = $modification->description;
        $this->reported = $modification->reported;
        $this->reported_at = $modification->reported_at;
        $this->zone_id = $modification->zone_id;
        $this->team_id = $modification->team_id;
        $this->action_owner = $modification->action_owner;
        $this->action_id = $modification->action_id;
        $this->wo_code = $modification->wo_code;

        $this->expires_at = $modification->reservation->expires_at_for_user;
        $this->reservation_status = $modification->reservation->status;
        $this->is_expired = $modification->reservation->is_expired;
    }


    public function rules()
    {
        $rules = [
            "site_code" => ['required', 'exists:sites,site_code'],
            // "subcontractor_id" => ["required", "exists:subcontractors,id"],
            "action_id" => ["required", "array"],
            "action_id.*" => ['required', 'exists:actions,id'],
            "description" => ["nullable", "string", 'regex:/^[a-zA-Z0-9\-_!@#$%^&*(),.?":{}\n\t|<> ]+$/'], //////regex for special chars, chars,numbers,spaces,underscore,dash
            "pending" => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-_!@#$%^&*(),.?":{}\n\t|<> ]+$/'],
            "request_date" => "required|date",
            "cw_date" => [" nullable", "date", "required_if:modification_status_id,1,3", "after_or_equal:request_date"],
            "d6_date" => [" nullable", "date", "required_if:modification_status_id,1", "after_or_equal:request_date", "after_or_equal:cw_date"],
            "modification_status_id" => ["required", 'exists:modification_status,id',  function ($attribute, $value, $fail) {

                $cwDate = $this->cw_date;
                $d6Date = $this->d6_date;

                // Both dates are null - must be "2"
                if ((is_null($cwDate) || $cwDate == '') && (is_null($d6Date) || $d6Date == '')) {
                    if ($value != 2) {
                        $fail('The modification status must be "in progress" when both cw_date and d6_date are null.');
                    }
                }
                // At least one date is not null - must be "1" or "3"
                else {
                    if (!in_array($value, [1, 3])) {
                        $fail('The modification status must be "Done" or "waiting D6" when either cw_date or d6_date is provided.');
                    }
                }
            }],
            "requester_id" => ["required", 'exists:requesters,id'],
            // "project_id" => ["required", 'exists:projects,id'],
            "est_cost" => ["required_if:modification_status_id,1,2,3", new CommaSeparatedNumber],
            "reported" => ["required",  Rule::in([1, 0])],
            "reported_at" => ["nullable", "date", "required_if:reported,1"],
            'team_id' => ['required', 'exists:teams,id'],
            'zone_id' => ['required', 'exists:zones,id'],
            'action_owner' => ['required', 'exists:users,id']

        ];

        if (request()->route('modification.update')) {

            $rules['id'] = ["required", "exists:modification,id"];
        } elseif (request()->route('modification.create')) {

            $rules["subcontractor_id"] = ["required", "exists:subcontractors,id"];
            $rules["project_id"] = ["required", 'exists:projects,id'];
        }

        return $rules;
    }

    protected function messages()
    {
        return
            [
                "est_cost.required_if" => "Estimated cost is required",
                "subcontractor_id.required" => "The subcontractor is required",
                "requester_id.required" => "The Requester is required",
                "modification_status_id.required" => "The status is required",
                "project_id.required" => "The project is required",
                "action_id.required" => "The action is required",
                "cw_date.required_if" => "Civil work date is required when status is waiting D6 or Done",
                "d6_date.required_if" => "D6 date is required when status is  Done"

            ];
    }


    public function checkPOOnHandAmount($POs)
    {
        $onHands = [];
        $POsWithSufficientOnHand = [];
        if (count($POs) > 0) {
            foreach ($POs as $PO) {
                $onHand = $PO->getAvailableAmount();

                $new['id'] = $PO->id;
                $new['amount'] = $onHand;
                array_push($onHands, $new);
            }

            $POsWithSufficientOnHand = array_filter($onHands, function ($onHand) {
                $estCostFloatValue = floatval(str_replace(',', '', $this->est_cost));
                return $onHand['amount'] >= $estCostFloatValue;
            });

            $onHands = [];
            if (count($POsWithSufficientOnHand) > 0) {
                $onHands[] = $POsWithSufficientOnHand[0];
            }

            return $onHands;
        }
        return $onHands;
    }

    private function updateInprogressForm($onHands, $modification)
    {
        $modification->update(
            $this->all()
        );


        $modification->actions()->sync($this->action_id);
        $expiresAt = Carbon::now()->addDays(intval(env('MODIFICATION_EXPIRATION_PERIOD', 20)));

        $modification->reservation->update([
            'modification_id' => $modification->id,
            'purchase_order_id' => $onHands[0]['id'],
            'status' => 'active',
            'amount' => $this->est_cost,
            'reserved_at' => now(),
            'expires_at' => $expiresAt,

        ]);

        return $modification;
    }

    private function createInprogressForm($onHands)
    {
        $modification = Modification::create(
            $this->all()
        );


        $modification->actions()->attach($this->action_id);
        $expiresAt = Carbon::now()->addDays(intval(env('MODIFICATION_EXPIRATION_PERIOD', 20)));
        $modificationReservation = ModificationReservation::create([
            'modification_id' => $modification->id,
            'purchase_order_id' => $onHands[0]['id'],
            'status' => 'active',
            'amount' => $this->est_cost,
            'reserved_at' => now(),
            'expires_at' => $expiresAt,

        ]);
        Toaster::success('Modification created Successfully');
        return $modification;
    }

    public function inprogressFormSubmission($modification = null)
    {
        $project = Project::find($this->project_id);
        $projectPOName = $project->getProjectPOName();
        $subcontractor = Subcontractor::find($this->subcontractor_id);

        $POs = $subcontractor->getSubcontractorAvailablePOs($projectPOName);

        if (count($POs) > 0) {
            $onHands = $this->checkPOOnHandAmount($POs); //////array of POs on hand amount
            if (count($onHands) > 0) {



                $po = PurchaseOrder::find($onHands[0]['id']);
                $estCostFloatValue = floatval(str_replace(',', '', $this->est_cost));



                $po->increment('in_progress', $estCostFloatValue);
                $po->decrement('on_hand', $estCostFloatValue);

                if (!$modification) {
                    $modification = $this->createInprogressForm($onHands);

                    return redirect()->route('modification.details', $modification->id);
                } elseif ($modification) {
                    $updatedModification = $this->updateInprogressForm($onHands, $modification);
                    return redirect()->route('modification.details', $updatedModification->id);
                }
            } else {
                $subcontractorName = $subcontractor->name;
                Toaster::error("There is no available POs with sufficient amount to cover this modification for . $subcontractorName ");
            }
        } else {
            $subcontractorName = $subcontractor->name;
            Toaster::error("There is no available POs for . $subcontractorName ");
        }
    }
}
