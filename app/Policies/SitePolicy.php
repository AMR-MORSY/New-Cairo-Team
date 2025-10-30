<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Site\Site;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;




class SitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user,Site $site): Response
    {
        $userTeamId = $user->teams()->first()->id;
        if (!in_array($userTeamId, [1, 2, 3, 4], true)) { //////////HGLI,NGLI,AGLI, and Modification teams can view site 


         
            return Response::deny("You do not have the right permission to view site's information.");
        }
        return Response::allow();
    }

    public function viewSiteProperties(User $user,Site $site):Response
    {
        $siteZone=$site->zone;
        $teamAndZone=$user->getTeamAndZone();

        if($teamAndZone['zone']==null)
        {
               return Response::deny("You do not have the right permission to view site's properties.");


        }
        elseif($teamAndZone['zone']!=$siteZone)
        {
              return Response::deny("You do not have the right permission to view site's properties.");

        }
        else{
             return Response::allow();

        }


    }
    public function create(User $user): Response
    {
        if ($user->isSiteAdmin()) {
            return Response::allow();
        }
        return Response::deny('You do not have the right permission.');
    }

    public function viewCESites(User $user)
    {


        if ($user->hasRole('CE_maintenance_engineer')) {
            return Response::allow();
        }
        return Response::deny('You do not have the right permission to view CE sites activities.');
    }
    /**
     * Determine whether the user can view the model.
     */
    public function viewSiteModifications(User $user, Site $site): Response
    {

        // $userTeamId = getPermissionsTeamId();   /////////depending on the middleware team.member that get the team_id from the session and assign it to the user, but if route did not change, middleware will not work
         $userTeamId= $user->teams()->first()->id;
        if ($userTeamId) {
            return   $userTeamId == $site->team->id ? Response::allow() : Response::deny('The site is not in your area to view its modifications.'); /////team members only can view site modifications
        } elseif ($user->isModificationAdmin()) {
            return Response::allow();
        }
        return Response::deny('The site is not in your area to view its modifications.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function createSiteModifications(User $user, Site $site): Response /////////any team member like site engineer/zone manager can create or view any modification on any site in the area
    {

        if ($user->isZoneManager($site->zone)) ////////////////zone manager can create modifications in his zone
        {
            return Response::allow();
        } elseif ($user->isSiteEngineer($site->team)) ////////////////site Engineer can create modifications in his area
        {
            return Response::allow();
        }


        return Response::deny('The site is not in your area to modify it.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Site $site): Response
    {
        if ($user->isSiteAdmin()) {
            return Response::allow();
        }
        return Response::deny('You do not have the right permission.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Site $site): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Site $site): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Site $site): bool
    {
        return false;
    }
}
