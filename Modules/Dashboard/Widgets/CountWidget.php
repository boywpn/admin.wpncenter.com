<?php

namespace Modules\Dashboard\Widgets;

use Arrilot\Widgets\AbstractWidget;

/**
 * Class CountWidget
 * @package Modules\Dashboard\Widgets
 */
class CountWidget extends AbstractWidget
{
    protected $config = [
        'color' => 'bg-light-green',
        'bg_color' => 'bg-pink',
        'icon' => 'playlist_add_check',
        'title' => 'New',
        'coll_class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-6',
        'counter' => 0
    ];

    public function run()
    {
        return view('dashboard::widgets.count_widget', [
            'config' => $this->config,
            'counter' => 334
        ]);
    }
}
