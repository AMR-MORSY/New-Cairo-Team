<?php

namespace App\Policies;

use App\Enums\Zones;
use App\Models\Site\Site;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Scope comes from users.team / users.zone compared against the site's own
 * team / zone. Super admins bypass this entirely through the Gate::before
 * in AppServiceProvider, which runs before any policy.
 *
 * This policy speaks only about sites. Batteries, power data, site data,
 * notes and images have no policy of their own and carry no #[UsePolicy]:
 * every page that touches one authorizes against its site instead
 * ($battery->site), which is what the Livewire components already did.
 */
class SitePolicy
{
    /**
     * Any member of an operational team can see basic site information.
     *
     * site_admin and modification_admin hold no team by design, so the team
     * column alone would refuse them. They are named here rather than tested
     * through a general "global role" helper. Super admins never reach this
     * method - the Gate::before in AppServiceProvider answers first.
     */
    public function view(User $user, Site $site): Response
    {
        if ($user->isSiteAdmin() || $user->isModificationAdmin() || $user->team !== null) {
            return Response::allow();
        }

        return Response::deny("You do not have the right permission to view site's information.");
    }

    /**
     * Detailed properties are limited to the user's own zone, except for
     * site_admin, whose remit is sites in every zone. The is*() helpers
     * below compare the user's own zone / team column and admit no one else.
     */
    public function viewSiteProperties(User $user, Site $site): Response
    {
        if (
            $user->isSiteAdmin()
            || $user->isMaintenanceEngineer($site->zone)
            || $user->isZoneManager($site->zone)
            || $user->isTeamManager($site->team)
        ) {
            return Response::allow();
        }

        return Response::deny("You do not have the right permission to view site's properties.");
    }

    public function create(User $user): Response
    {
        if ($user->isSiteAdmin()) {
            return Response::allow();
        }

        return Response::deny('You do not have the right permission.');
    }

    /**
     * The Ce* pages are specific to Cairo East, so the zone stays fixed here.
     * The role no longer carries it - it comes from the user's zone column.
     */
    public function viewCESites(User $user): Response
    {
        if ($user->isMaintenanceEngineer(Zones::CAIRO_EAST)) {
            return Response::allow();
        }

        return Response::deny('You do not have the right permission to view CE sites activities.');
    }

    /**
     * Modifications on a site are visible to that site's team.
     */
    public function viewSiteModifications(User $user, Site $site): Response
    {
        if ($user->isModificationAdmin() || $user->inTeam($site->team)) {
            return Response::allow();
        }

        return Response::deny('The site is not in your area to view its modifications.');
    }

    /**
     * Zone managers act within their zone; site engineers and team managers
     * across their whole team.
     */
    public function createSiteModifications(User $user, Site $site): Response
    {
        if (
            $user->isModificationAdmin()
            || $user->isZoneManager($site->zone)
            || $user->isSiteEngineer($site->team)
            || $user->isTeamManager($site->team)
        ) {
            return Response::allow();
        }

        return Response::deny('The site is not in your area to modify it.');
    }

    public function update(User $user, Site $site): Response
    {
        if ($user->isSiteAdmin()) {
            return Response::allow();
        }

        return Response::deny('You do not have the right permission.');
    }

    public function delete(User $user, Site $site): bool
    {
        return false;
    }

    public function restore(User $user, Site $site): bool
    {
        return false;
    }

    public function forceDelete(User $user, Site $site): bool
    {
        return false;
    }

}
