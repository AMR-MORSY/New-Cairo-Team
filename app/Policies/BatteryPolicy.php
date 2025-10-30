<?php

namespace App\Policies;

use App\Models\Site\Battery;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BatteryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Battery $battery): Response
    {
        $siteZone=$battery->site->zone;
        $teamAndZone=$user->getTeamAndZone();

        if($teamAndZone['zone']==null)
        {
               return Response::deny("You do not have the right permission to view site's notes.");


        }
        elseif($teamAndZone['zone']!=$siteZone)
        {
              return Response::deny("You do not have the right permission to view site's notes.");

        }
        else{
             return Response::allow();

        }

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
    public function update(User $user, Battery $battery): Response
    {
        if ($user->isSiteAdmin()) {
            return Response::allow();
        }
        return Response::deny('You do not have the right permission.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Battery $battery): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Battery $battery): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Battery $battery): bool
    {
        return false;
    }
}
