<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Modules\Platform\Core\Helper\StringHelper;

class TicketOverview extends Chart
{

    /**
     * TicketOverview constructor.
     * @param $tickets
     * @param string $color
     */
    public function __construct($tickets,$color = '#2196F3')
    {
        parent::__construct();

        $this->displayLegend(true);

        $labels = [];
        $values = [];
        $bgColors = [];

        foreach ($tickets as $k => $val) {
            $labels[] = $val->name;
            $values[] = $val->counter;
            $bgColors[] = $color;

            $color = StringHelper::darkenColor($color);
        }

        $this->labels($labels);
        $this->dataset('Tickets', 'doughnut', $values)->color('#ffffff')->backgroundColor($bgColors);

    }
}
