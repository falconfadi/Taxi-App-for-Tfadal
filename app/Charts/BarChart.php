<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class BarChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build( $Xs, $titleX, $Ys, $titleY, $title): \ArielMejiaDev\LarapexCharts\BarChart
    {


        return $this->chart->barChart()
            ->setTitle($title)
            ->setSubtitle('')
            ->addData($titleY, $Ys)
            /*->addData('Digital sales', [70, 29, 77, 28, 55, 45])*/
            ->setXAxis($Xs);
    }

//    public function build( ): \ArielMejiaDev\LarapexCharts\BarChart
//    {
//
//        return $this->chart->barChart()
//            ->setTitle('San Francisco vs Boston.')
//            ->setSubtitle('Wins during season 2021.')
//            ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
//            ->addData('Boston', [7, 3, 8, 2, 6, 4])
//            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
//    }
}
