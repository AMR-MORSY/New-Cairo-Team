<?php

namespace App\Livewire\Forms;

use Auth;
use Carbon\Carbon;
use Livewire\Form;
use App\Models\Site\Site;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

use App\Enums\Teams;
use App\Enums\Zones;
use Illuminate\Validation\Rules\Enum;
use App\Rules\CommaSeparatedNumber;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Modification;
use App\Models\Modification\PurchaseOrder;
use App\Models\Modification\Subcontractor;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\ModificationStatus;
use App\Models\Modification\ModificationReservation;
use Livewire\Attributes\Locked;

class ModificationForm extends Form
{
    #[Locked]
    public $id;

    /**
     * Which flow this form serves: 'create' or 'update'.
     *
     * rules() used to ask request()->route('modification.create'), but that
     * reads a route *parameter* of that name - the routes declare {site} and
     * {modification} - so it was always null and both branches were dead.
     * The route name is no help either: a Livewire action POSTs to
     * /livewire/update, not to the page route. The two entry points set this
     * themselves instead.
     */
    #[Locked]
    public string $context = 'create';
    #[Locked]
    public $site_code = '';
    public $subcontractor_id = '';
    public $pending = "";
    public $est_cost;
    public $final_cost;
    public $request_date = '';
    public $requester_id = '';
    public $project_id = '';
    public $status = '';
    public string|null $cw_date = null;
    public string |null $d6_date = null;
    public $description = "";
    public $reported = 0;
    public $reported_at = '';
    #[Locked]
    public $zone = '';
    #[Locked]
    public $team = '';
    #[Locked]
    public $action_owner = '';
    public array $action_id = [];
    #[Locked]
    public $wo_code = '';

    public $expires_at = '';
    public $reservation_status = '';
    public $is_expired;
    public $activate = "";


    public function setModificationDefaultAttributes($site)
    {
        $this->context = 'create';

        $this->site_code = $site->site_code;

        $this->action_owner = Auth::user()->id;

        $this->zone = $site->zone?->value;

        $this->team = $site->team?->value;
    }

    public function updateFormAttributes()
    {
        return [
            "site_code",
            "action_id",
            "description",
            "pending",
            "request_date",
            "cw_date",
            "d6_date",
            "status",
            "requester_id",
            "est_cost",
            'final_cost',
            "reported",
            "reported_at",
            'team',
            'zone',
            'action_owner',
            'project_id',
            'subcontractor_id',

        ];
    }
    /** Values that only make sense together; run before validating. */
    public function normalize(): void
    {
        if (! $this->reported) {   // covers 0, '0', false, ''
            $this->reported_at = null;
        }
    }

    /**
     * Active entries only - except, on update, the ones this modification
     * already uses, so a retired subcontractor doesn't block saving it.
     */
    private function activeLookup(string $table, array $keep = []): \Illuminate\Validation\Rules\Exists
    {
        return Rule::exists($table, 'id')->where(
            fn($q) => $q->where('is_active', true)->when($keep, fn($q) => $q->orWhereIn('id', $keep))
        );
    }


    public function setModificationDetails(Modification $modification)
    {
        $this->context = 'update';

        $this->id = $modification->id;

        $this->site_code = $modification->site_code;
        $this->subcontractor_id = $modification->subcontractor_id;
        $this->pending = $modification->pending;
        $this->est_cost = $modification->est_cost;
        $this->final_cost = $modification->final_cost;
        $this->request_date = $modification->request_date;
        $this->requester_id = $modification->requester_id;
        $this->project_id = $modification->project_id;
        $this->status = $modification->status?->value;
        $this->cw_date = $modification->cw_date;
        $this->d6_date = $modification->d6_date;
        $this->description = $modification->description;
        $this->reported = $modification->reported;
        $this->reported_at = $modification->reported_at?->toDateString();
        $this->zone = $modification->zone?->value;
        $this->team = $modification->team?->value;
        $this->action_owner = $modification->action_owner;
        $this->action_id = $modification->actions->pluck('id')->all();
        $this->wo_code = $modification->wo_code;

        ////////////////////these attributes concerned with New modifications system

        // $this->expires_at = $modification->reservation->expires_at_for_user;
        // $this->reservation_status = $modification->reservation->status;
        // $this->is_expired = $modification->reservation->is_expired;
    }


    public function rules()
    {
        $current = $this->context === 'update' ? Modification::with('actions')->find($this->id) : null;
        $rules = [
            "site_code" => ['required', 'exists:sites,site_code'],
             "subcontractor_id" => ['required', $this->activeLookup('subcontractors', array_filter([$current?->subcontractor_id]))],
            "action_id" => ["required", "array"],
                "action_id.*"      => ['required', $this->activeLookup('actions', $current?->actions->pluck('id')->all() ?? [])],
            "description" => ["nullable", "string", 'max:2000'], //////regex for special chars, chars,numbers,spaces,underscore,dash
            "pending" => ['nullable', 'string', 'max:2000'],
            "request_date" => "required|date",
            "cw_date" => [" nullable", "date", "required_if:status,Done,Waiting D6", "after_or_equal:request_date"],
            "d6_date" => [" nullable", "date", "required_if:status,Done", "after_or_equal:request_date", "after_or_equal:cw_date", 'prohibited_if:status,Cancelled'],
            "status" => ["required", new Enum(ModificationStatus::class), function ($attribute, $value, $fail) {
                $status = ModificationStatus::tryFrom($value);

                $noDates = blank($this->cw_date) && blank($this->d6_date);
                if ($status === null || $status->isCancelled()) {
                    return;
                }
                if ($noDates  && ! $status->isInProgress()) {

                    $fail('The modification status must be "in progress" when both  CW date and D6 date are empty.');
                } elseif (! $noDates && ! $status->isQuoted()) {
                    $fail('The modification status must be "Done" or "waiting D6" when either CW date or D6 date is provided.');
                }
            }],
            "requester_id"     => ['required', $this->activeLookup('requesters',     array_filter([$current?->requester_id]))],
            "project_id"       => ['required', $this->activeLookup('projects',       array_filter([$current?->project_id]))],
            "est_cost" => ['nullable',"required_if:status,Done,In Progress,Waiting D6", new CommaSeparatedNumber],
            "final_cost" => ['nullable',"required_if:status,Done,Waiting D6", new CommaSeparatedNumber],
            "reported" => ["required",  Rule::in([1, 0])],
            "reported_at" => ["nullable", "date", "required_if:reported,1", "after_or_equal:request_date"],

            'team' => ['required', new Enum(Teams::class)],
            'zone' => ['required', new Enum(Zones::class)],
            'action_owner' => ['required', 'exists:users,id']

        ];

        if ($this->context === 'update') {
            $rules['id'] = ["required", "exists:modifications,id"];
        } 

        return $rules;
    }

    protected function messages()
    {
        return
            [
                "est_cost.required_if" => "Estimated cost is required",
                "final_cost.required_if" => "Final cost is required",
                "subcontractor_id.required" => "The subcontractor is required",
                "requester_id.required" => "The Requester is required",
                "status.required" => "The status is required",
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
        /////////////////////////////////this part concerned with checking subcontractor's PO
        // $project = Project::find($this->project_id);
        // $projectPOName = $project->getProjectPOName();
        // $subcontractor = Subcontractor::find($this->subcontractor_id);

        // $POs = $subcontractor->getSubcontractorAvailablePOs($projectPOName);

        // if (count($POs) > 0) {
        //     $onHands = $this->checkPOOnHandAmount($POs); //////array of POs on hand amount
        //     if (count($onHands) > 0) {



        //         $po = PurchaseOrder::find($onHands[0]['id']);
        //         $estCostFloatValue = floatval(str_replace(',', '', $this->est_cost));



        //         $po->increment('in_progress', $estCostFloatValue);
        //         $po->decrement('on_hand', $estCostFloatValue);

        //         if (!$modification) {
        //             $modification = $this->createInprogressForm($onHands);

        //             return redirect()->route('modification.details', $modification->id);
        //         } elseif ($modification) {
        //             $updatedModification = $this->updateInprogressForm($onHands, $modification);
        //             return redirect()->route('modification.details', $updatedModification->id);
        //         }
        //     } else {
        //         $subcontractorName = $subcontractor->name;
        //         Toaster::error("There is no available POs with sufficient amount to cover this modification for . $subcontractorName ");
        //     }
        // } else {
        //     $subcontractorName = $subcontractor->name;
        //     Toaster::error("There is no available POs for . $subcontractorName ");
        // }


        $modification = Modification::create(
            $this->all()
        );


        $modification->actions()->attach($this->action_id);

        Toaster::success('Modification created Successfully');

        return redirect()->route('modification.details', $modification->id);
    }
}
