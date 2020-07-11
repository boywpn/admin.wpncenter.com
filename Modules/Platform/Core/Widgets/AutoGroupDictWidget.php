<?php

namespace Modules\Platform\Core\Widgets;

use Arrilot\Widgets\AbstractWidget;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * Class AutoGroupDictWidget
 * @package Modules\Platform\Core\Widgets
 */
class AutoGroupDictWidget extends AbstractWidget
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
        'href' => '',
        'max' => 10
    ];

    public function run()
    {

        $dictEntity = App::make($this->config['dict']);
        $moduleTable = $this->config['moduleTable'];
        $groupBy = $this->config['groupBy'];

        $records = DB::table($moduleTable)
            ->selectRaw('count(1) as total, '.$groupBy)
            ->groupBy($groupBy);

        if (Landlord::hasTenant('company_id')) {
            $records->where('company_id', Landlord::getTenantId('company_id'));
        }

        $records = $records->get();
        $dictStatus = $dictEntity::all();

        $grouped = [];
        $result = [];

        foreach ($records as $record){
            $grouped[$record->$groupBy] = $record->total;
        }

        foreach ($dictStatus as $status){
            try {
                $result[$status->id] = [
                    'icon' => $status->icon,
                    'color' => $status->color,
                    'title' => $status->name,
                    'count' => isset($grouped[$status->id]) ? $grouped[$status->id] : 0
                ];
            }catch (\Exception $e){
                $result[$status->id] = 0;
            }
        }

        return view('core::widgets.auto_group_dict_widget', [
            'config' => $this->config,
            'records' => $result
        ]);
    }
}
