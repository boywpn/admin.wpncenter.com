<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 *
 * Class IncomeVsExpense
 *
 * @package App\Charts
 */
class IncomeVsExpense extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct($values)
    {
        parent::__construct();

        $this->displayLegend(true);

        $labels = [];
        $income = [];
        $expense = [];

        foreach ($values as $v){
            $labels[] = $v->yearMonth;
            $income[] = $v->income;
            $expense[] = $v->expense;
        }

        $this->labels($labels);
        $this->dataset(trans('dashboard::dashboard.income'), 'bar', $income)->color('#ffffff')->backgroundColor('#2196F3');
        $this->dataset(trans('dashboard::dashboard.expense'), 'bar', $expense)->color('#ffffff')->backgroundColor('#ffa21a');

    }
}
