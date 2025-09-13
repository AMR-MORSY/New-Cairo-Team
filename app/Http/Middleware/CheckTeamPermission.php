<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toaster;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();
        $teamAndZone = $user->getAreaAndZone();
        $team = $teamAndZone['area'];

        if (!$team) {
            Toaster::error('You are not assigned to any team. Please contact your administrator.');
            return redirect()->back();
        }

       
        setPermissionsTeamId($team->id);

        if (!$user->hasPermissionTo($permission)) {
            abort(403, "You don't have permission to {$permission} in your team.");
        }

        return $next($request);
    }
}
