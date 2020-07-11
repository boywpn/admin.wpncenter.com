<?php

namespace Modules\Platform\Core\Widgets;

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
        'coll_class' => 'col-lg-3 col-md-3 col-sm-3 col-xs-12',
        'icon_color' => '',
        'counter' => 0,
        'icon_type' => 'material',
        'href' => ''
    ];

    public function run()
    {
        return view('core::widgets.count_widget', [
            'config' => $this->config,
            'counter' => 334
        ]);
    }
}
