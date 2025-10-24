<?php

namespace App\Enums;

enum SiteNoticeTypes: string
{
    case POWER="Power";
    case MICROWAVE='Microwave';
    case SITE_SECURITY='Site Security';
    case BATTERIES='Batteries';
    case SHARING='Sharing';
    case ENVIRONMENTAL='Environmental';

}