<?php

namespace App\Enums;

enum SitePermissions: string
{
    /**
     * Renamed from the old 'view_CE_sites'. The zone is no longer baked into
     * the permission - it comes from users.zone.
     */
    case VIEW_ZONE_SITES = 'view_zone_sites';
    case CREATE_NEW_SITES = 'create_new_sites';
    case UPDATE_SITES = 'update_sites';
}
