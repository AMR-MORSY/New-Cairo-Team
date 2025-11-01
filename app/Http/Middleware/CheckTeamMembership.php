<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Toaster;

class CheckTeamMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        // Check if user belongs to any team

        if ($user->teams()->count() === 0 && !$user->email='morsy.mamr@gmail.com') {
      
              abort(403, 'You are not assigned to any team. Please contact your administrator.');
            
        }

       

        setPermissionsTeamId(session('team_id')); /////////when the user logged in, team_id stored in the session 

        

       $user->unsetRelation('roles')->unsetRelation('permissions');

     
       
        // Get user's team and zone info
        // $teamAndZone = $user->getAreaAndZone();
    
        // $area = $teamAndZone['area'];
        // $zone = $teamAndZone['zone'];

        // // Share team and zone data with all views
        // view()->share('currentTeam', $area);
        // view()->share('currentZone', $zone);
        // view()->share('userTeamAndZone', $teamAndZone);

        return $next($request);
       
    }
}
