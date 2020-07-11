<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Modules\Leads\Entities\LeadStatus;
use Modules\Platform\Core\Helper\StringHelper;

/**
 *
 * Class LeadOverview
 *
 * @package App\Charts
 */
class LeadOverview extends Chart
{
    /**
     * LeadOverview constructor.
     * @param $leads
     * @param string $color
     */
    public function __construct($leads, $color = '#2196F3')
    {


        parent::__construct();

        $this->displayLegend(false);

        if (count($leads) == 0) {
            foreach (LeadStatus::all() as $status) {
                $this->dataset($status->name, 'bar', [0])->color('#ffffff')->backgroundColor($color);
                $color = StringHelper::darkenColor($color);
            }
        }

        foreach ($leads as $k => $bar) {

            $this->dataset($bar->name, 'bar', [$bar->counter])->color('#ffffff')
                ->backgroundColor($color);
            $color = StringHelper::darkenColor($color);
        }
    }
}
