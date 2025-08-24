<?php

namespace App\Enums;

enum ModificationStatus : string
{
    case Done="Done";
    case IN_PROGRESS ="in Progress";
    case WAITING_D6 = "Waiting D6";
    case CANCELLED= "Cancelled";

}