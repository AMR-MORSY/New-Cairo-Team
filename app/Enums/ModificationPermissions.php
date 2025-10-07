<?php

namespace App\Enums;

enum ModificationPermissions: string
{
  case VIEW_MODIFICATIONS = 'view_modifications';
  case CREATE_MODIFICATIONS = 'create_modifications';
  case UPDATE_MODIFICATIONS = 'update_modifications';
  case VIEW_AREA_MODIFICATIONS = 'view_area_modifications';
  case UPDATE_OWN_MODIFICATIONS =  'update_own_modifications';
  case UPDATE_ZONE_MODIFICATIONS =  'update_zone_modifications';


  public static function teamManagerRoles()
  {
    return [
      self::VIEW_AREA_MODIFICATIONS->value,
      self::CREATE_MODIFICATIONS->value,
      self::UPDATE_MODIFICATIONS->value

    ];
  }
  public static function zoneManagerRoles()
  {
    return [
      self::VIEW_AREA_MODIFICATIONS->value,
      self::CREATE_MODIFICATIONS->value,
      self::UPDATE_ZONE_MODIFICATIONS->value,
      self::UPDATE_OWN_MODIFICATIONS->value

    ];
  }
  public static function siteEngineerRoles()
  {
    return [
      self::VIEW_AREA_MODIFICATIONS->value,
      self::CREATE_MODIFICATIONS->value,
      self::UPDATE_OWN_MODIFICATIONS->value

    ];
  }
}
