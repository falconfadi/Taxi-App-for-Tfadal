<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class UsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($Xs, $titleX, $Ys, $titleY, $title): \ArielMejiaDev\LarapexCharts\LineChart
    {

        return $this->chart->lineChart()
            ->setTitle($title)
            ->setSubtitle('')
            ->addData($titleY, $Ys)
            /*->addData('Digital sales', [70, 29, 77, 28, 55, 45])*/
            ->setXAxis($Xs);
    }
}
