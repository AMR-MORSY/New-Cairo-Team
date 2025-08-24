<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site\Modification> $modifications
 * @property-read int|null $modifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Zone> $zones
 * @property-read int|null $zones_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereUpdatedAt($value)
 */
	class Area extends \Eloquent {}
}

namespace App\Models\Site{
/**
 * @property int $id
 * @property string $nodal_code
 * @property string $cascade_code
 * @property string $cascade_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Site\Site $site
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade whereCascadeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade whereCascadeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade whereNodalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cascade whereUpdatedAt($value)
 */
	class Cascade extends \Eloquent {}
}

namespace App\Models\Site{
/**
 * @property int $id
 * @property string $site_code
 * @property int $subcontractor_id
 * @property int $requester_id
 * @property string|null $description
 * @property string|null $pending
 * @property int $modification_status_id
 * @property int $zone_id
 * @property int $action_owner
 * @property int $project_id
 * @property string $request_date
 * @property string $d6_date
 * @property string $cw_date
 * @property string $wo_code
 * @property string|null $final_coast
 * @property string|null $est_coast
 * @property int $reported
 * @property string|null $reported_at
 * @property string|null $deleted_at
 * @property int $invoice_id
 * @property string|null $month
 * @property int|null $year
 * @property int $area_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $actionOwner
 * @property-read \App\Models\Area $area
 * @property-read \App\Models\Zone $zone
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereActionOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereCwDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereD6Date($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereEstCoast($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereFinalCoast($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereModificationStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification wherePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereReported($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereReportedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereRequestDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereRequesterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereSiteCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereSubcontractorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereWoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Modification whereZoneId($value)
 */
	class Modification extends \Eloquent {}
}

namespace App\Models\Site{
/**
 * @property int $id
 * @property string $site_code
 * @property string $site_name
 * @property string|null $BSC
 * @property string|null $RNC
 * @property string|null $office
 * @property string $type
 * @property string $category
 * @property string $severity
 * @property string $sharing
 * @property string|null $host
 * @property string|null $gest
 * @property string|null $vf_code
 * @property string|null $et_code
 * @property string|null $we_code
 * @property int $zone_id
 * @property int $area_id
 * @property int|null $cells_2G
 * @property int|null $cells_3G
 * @property int|null $cells_4G
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site\Cascade> $cascades
 * @property-read int|null $cascades_count
 * @property-read mixed $nodal_code
 * @property-read mixed $nodal_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereBSC($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereCells2G($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereCells3G($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereCells4G($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereEtCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereGest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereRNC($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereSharing($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereSiteCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereSiteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereVfCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereWeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Site whereZoneId($value)
 */
	class Site extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $time_zone
 * @property \Illuminate\Support\Carbon|null $login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Area> $areas
 * @property-read int|null $areas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site\Modification> $modifications
 * @property-read int|null $modifications_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Zone> $zones
 * @property-read int|null $zones_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property int $is_active
 * @property int $area_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area $area
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site\Modification> $modifications
 * @property-read int|null $modifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zone whereUpdatedAt($value)
 */
	class Zone extends \Eloquent {}
}

