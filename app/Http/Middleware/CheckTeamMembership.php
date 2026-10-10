<?php

namespace App\Http\Middleware;

use App\Enums\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks users who belong to no operational team and hold no global role.
 *
 * There is no request-scoped team state any more: scope lives on the user row
 * (users.team / users.zone), so this behaves identically on a full page load
 * and on a Livewire update, which the old session-based version did not.
 */
class CheckTeamMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('home');
        }

        /*
         * users.team is cast to the Teams enum, so this is an enum or null.
         *
         * The team requirement is waived for the roles that hold no team by
         * design. Super admins are named explicitly: the Gate::before in
         * AppServiceProvider covers abilities and policies, and an abort()
         * here is neither, so it never runs for this check.
         */
        $teamNotRequired = $user->isSuperAdmin() || $user->hasAnyRole([
            UserRoles::MODIFICATION_ADMIN->value,
            UserRoles::SITE_ADMIN->value,
        ]);

        if ($user->team === null && ! $teamNotRequired) {
            abort(403, 'You are not assigned to any team. Please contact your administrator.');
        }

        return $next($request);
    }
}
