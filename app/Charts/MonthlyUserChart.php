<?php

namespace App\Charts;

// use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyUserChart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */

    protected $chart;
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        return $this->chart->donutChart()
            ->setTitle('Top scorers of the team')
            ->setDataset( [40, 50, 60])
           ->setDataLabels(true) ;
    }
}
