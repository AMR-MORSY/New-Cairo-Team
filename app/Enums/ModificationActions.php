<?php

namespace App\Enums;

enum ModificationActions:string
{
    case RETROFITTING = "Retrofitting";
    case ANTENNA_SWAP = "Antenna Swap";
    case REPAIR = "Repair";
    case ADDING_SA = "Adding SA";
    case CHANGING_POWER_CABLE = "Changing Power Cable";
    case SHARING_PANEL = "Sharing Panel";
    case PT_RING = "PT Ring";
    case ADDING_ANTENNAS = "Adding Antennas";
    case EXTENDING_CABLES = "Extending Cables";
    case CONCRETE_WORKS = "Concrete Works";
    case CABLE_TRAYS = "Cable Trays";
    case RRUS_RELOCATION = "RRUs Relocation";
    case SITE_DISMANTLE = "Site Dismantle";
    case CAGE_INSTALLATION = "Cage Installation";
    case ADDING_MAST = "Adding Mast";
    case DISMANTLING_CABINETS = "Dismantling Cabinets";
    case RELOCATING_POWER_METER = "Relocating Power Meter";
    case RECTIFICATION = "Rectification";
    case SHELTER_SECURING = "Shelter securing";
    case INSTALL_LOCKS = "Install locks";
    case CHANGING_ANTENNA_AZ = "Changing antenna Az";
    case CHANGING_ANTENNA_HBA = "Changing Antenna HBA";
    case REMOVE_JUNK_MATERIALS = "Remove junk materials";
    case CLEARING_VSWR = "Clearing VSWR";
    case INSTALL_STEEL_LADDER = "install steel ladder";
    case INSTALL_TRIPLEXER = "install Triplexer";
    case INSTALL_COMBINER = "install combiner";
    case INSTALL_POWER_PANEL = "install Power panel";
    case INSTALL_CB = "install CB";
    case INSTALL_GRILL_WINDOWS = "install grill windows";
    case REPAIR_CAGE_DOOR = "Repair cage door";
    case DISMANTLE_ANTENNA = "Dismantle Antenna";
    case RELOCATE_ANTENNA = "Relocate Antenna";
    case RELOCATE_RRU = "Relocate RRU";
    case REMOVE_FRONDS = "Remove fronds";
}
