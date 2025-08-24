<?php

namespace App\Enums;

enum JobRoles :string
{

    case DEPARTMENT_HEAD= "head of dep.";
    case SENIOR_MANAGER = "senior manager";
    case ZONE_MANAGER= "zone manager";
    case SITE_ENGINEER= "site engineer";

}