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

        // if (!$user->hasPermissionTo($permission, 'web', $team->id)) {
        //     abort(403, "You don't have permission to {$permission} in your team.");
        // }

        // dd($team);

        // Check if user has the required permission in their team
        // if (!$user->can($permission, $team)) {
        // Toaster::error("You don't have permission to {$permission} in your team.");
        // return redirect()->back();
        // abort(403, "You don't have permission to {$permission} in your team.");
        // }

        //           // Debug information
        //     dd([
        //     'user_id' => $user->id,
        //     'team_id' => $team->id,
        //     'permission' => $permission,
        //     'user_permissions_in_team' => $user->getPermissionsViaRoles($team)->pluck('name'),
        //     'user_direct_permissions_in_team' => $user->getDirectPermissions($team)->pluck('name'),
        //     'user_all_permissions_in_team' => $user->getAllPermissions($team)->pluck('name'),
        //     'has_permission' => $user->hasPermissionTo($permission, $team),
        // ]);
        // dd([
        //     'all_permissions' => \Spatie\Permission\Models\Permission::all()->pluck('name'),
        //     'permission_exists' => \Spatie\Permission\Models\Permission::where('name', 'create_modifications')->exists(),
        // ]);

        // dd([
        //     'user_id' => $user->id,
        //     'team_id' => $team->id,
        //     'permission' => $permission,
        //     'user_all_permissions_in_team' => $user->getAllPermissions($team)->pluck('name')->toArray(),
        //     'user_roles_in_team' => $user->getRoleNames($team)->toArray(),
        //     'has_permission_with_team' => $user->hasPermissionTo($permission, $team),
        //     'has_permission_without_team' => $user->hasPermissionTo($permission),
        // ]);

        // dd([
        //     'user_id' => $user->id,
        //     'team_id' => $team->id,
        //     'permission' => $permission,
        //     'user_all_permissions_in_team' => $user->getAllPermissions($team->id)->pluck('name')->toArray(),
        //     'user_roles_in_team' => $user->getRoleNames($team->id)->toArray(),
        //     'has_permission_with_team_id' => $user->hasPermissionTo($permission, $team->id),
        // ]);

        // dd([
        //     'permissions_table' => \DB::table('permissions')->get(),
        //     'model_has_permissions' => \DB::table('model_has_permissions')->where('model_id', 1)->get(),
        //     'roles_table' => \DB::table('roles')->get(),
        //     'model_has_roles' => \DB::table('model_has_roles')->where('model_id', 1)->get(),
        // ]);
        // dd([
        //     'role_has_permissions' => \DB::table('role_has_permissions')->get(),
        //     'user_role_id' => 1, // HGLI_area_manager
        //     'role_permissions' => \DB::table('role_has_permissions')->where('role_id', 1)->get(),
        // ]);

        // $hasPermission = $user->roles()
        //     ->where('roles.team_id', $team->id)
        //     ->whereHas('permissions', function ($query) use ($permission) {
        //         $query->where('name', $permission);
        //     })
        //     ->exists();

        // if (!$hasPermission) {
        //     abort(403, "You don't have permission to {$permission} in your team.");
        // }
        setPermissionsTeamId($team->id);

        if (!$user->hasPermissionTo($permission)) {
            abort(403, "You don't have permission to {$permission} in your team.");
        }

        return $next($request);
    }
}
