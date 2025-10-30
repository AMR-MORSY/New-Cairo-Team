<?php

namespace App\Policies;

use App\Models\Modification\Modification;
use App\Models\Modification\Quotation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function modificationSearch(User $user): Response
    {
        // $userTeamId = getPermissionsTeamId(); ///////as middleware check team already applied on the route before
        $userTeamId = $user->teams()->first()->id;
        if ($user->isModificationAdmin()) { //////////the user with modification admin can view any modification in any area 
            return Response::allow();
        }
       
        elseif (in_array($userTeamId,[1,2,3],true) ) {//////////////////////users in HGLI,AGLI, and NGLI teams can search area's modifications
            return Response::allow();
        }
        return Response::deny('You do not have the right permission to search modifications.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewModificationDetails(User $user, Modification $modification): Response
    {
        // $userTeamId = getPermissionsTeamId();
          $userTeamId = $user->teams()->first()->id;
        if ($user->isModificationAdmin()) { //////////the user with modification admin can view any modification in any area 
            return Response::allow();
        }
        if ($userTeamId) { ////////////////////here the user is assigned to an area and will check to view the modification of that area 
            return   $userTeamId == $modification->area->id ? Response::allow() : Response::deny('The modification is not in your area to see its details.');
        }

        return Response::deny('The modification is not in your area to see its details.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateModification(User $user, Modification $modification): Response   ///// action_owner or zone manager or team manager or modification admin users can update the modification created in their zone/area 
    {

        return $user->id == $modification->action_owner || $user->isTeamManager($modification->area) || $user->isZoneManager($modification->zone)  ? Response::allow() : Response::deny('You can not update modification in this area.');
    }

    public function updateQuotation(User $user, Quotation $quotation) ///// action_owner or zone manager or team manager or modification admin users can update the modification created in their zone/area 
    {
        return $user->id == $quotation->modification->action_owner || $user->isTeamManager($quotation->modification->area) || $user->isZoneManager($quotation->modification->zone)  ? Response::allow() : Response::deny('You can not update modification in this area.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Modification $modification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Modification $modification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Modification $modification): bool
    {
        return false;
    }
}
