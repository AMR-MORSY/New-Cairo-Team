<?php

namespace App\Imports\NUR\NUR5G;

use Illuminate\Support\Collection;
use App\Imports\NUR\NUR5G\FM5GImport;
use App\Imports\NUR\NUR5G\NUR5GImport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Import5G implements WithMultipleSheets
{
   public $week, $year, $technology_cells,$total_net_cells;



    public function __construct($week, $year, $cells,$total_net_cells)
    {
        $this->week = $week;
        $this->year = $year;
        $this->technology_cells = $cells;
        $this->total_net_cells=$total_net_cells;
    }

    public function sheets(): array
    {
        return [
            '5G Network' => new NUR5GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
            '5G F.M' => new FM5GImport($this->week, $this->year, $this->technology_cells,$this->total_net_cells),
        ];
    }
}
