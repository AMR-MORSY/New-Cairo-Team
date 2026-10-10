<?php

namespace App\Policies;

use App\Models\Modification\Modification;
use App\Models\Modification\Quotation;
use App\Models\Site\Site;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Scope comes from users.team / users.zone compared against the modification's
 * own team / zone. modification_admin reaches every team.
 */
class ModificationPolicy
{
    /**
     * Searching is limited to the user's own team; modification admins see all.
     */
    public function modificationSearch(User $user): Response
    {
        if ($user->isModificationAdmin() || $user->team !== null) {
            return Response::allow();
        }

        return Response::deny('You do not have the right permission to search modifications.');
    }

     /**
     * Any team member can view modification details of his team.
     */
    public function viewModificationDetails(User $user, Modification $modification): Response
    {
        if ($user->isModificationAdmin() || $user->team === $modification->team) {
            return Response::allow();
        }

        return Response::deny('The modification is not in your area to see its details.');
    }

    public function create(User $user, Site $site): response
    {
         if ($this->mayCreate($user, $site)) {
            return Response::allow();
        }

        return Response::deny('You can not create modification in this area.');
    }

    /**
     * The action owner, the zone manager for its zone, the team manager for
     * its team, or a modification admin.
     */
    public function updateModification(User $user, Modification $modification): Response
    {
        if ($this->mayUpdate($user, $modification)) {
            return Response::allow();
        }

        return Response::deny('You can not update modification in this area.');
    }

    public function updateQuotation(User $user, Quotation $quotation): Response
    {
        $modification = $quotation->modification;

        if ($modification && $this->mayUpdate($user, $modification)) {
            return Response::allow();
        }

        return Response::deny('You can not update modification in this area.');
    }

    private function mayCreate(User $user, Site $site): bool
    {
        return $user->isModificationAdmin()
            ||  $user->isSiteEngineer($site->team)
             || $user->isZoneManager($site->zone)
            || $user->isTeamManager($site->team);
    }
    private function mayUpdate(User $user, Modification $modification): bool
    {
        return $user->isModificationAdmin()
            || $user->id === $modification->action_owner
            || $user->isZoneManager($modification->zone)
            || $user->isTeamManager($modification->team);
    }

    public function delete(User $user, Modification $modification): bool
    {
        return false;
    }

    public function restore(User $user, Modification $modification): bool
    {
        return false;
    }

    public function forceDelete(User $user, Modification $modification): bool
    {
        return false;
    }
}
