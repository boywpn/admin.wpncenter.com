<?php

namespace Modules\Platform\Core\Traits;

use Modules\Platform\Core\Entities\Comment;

/**
 * Trait Commentable
 * @package Modules\Platform\Core\Traits
 */
trait FunctionalTrait
{

    public static function badgeTable(){

        $data = self::all();
        $value = [];
        foreach ($data as $data){
            $value[$data->id] = [
                'icon' => $data->icon,
                'color' => $data->color,
                'name' => $data->name
            ];
        }

        return $value;

    }

    public static function getSelectOption($filter = true){

        $main = self::all();

        $data = [];
        $data_filter = [];
        foreach ($main as $main){
            $data[$main->id] = $main->name;

            $data_filter[] = [
                'value' => $main->id,
                'label' => $main->name
            ];
        }

        return ($filter) ? $data_filter : $data;
    }

    /**
     * Set for custom tab data
    */
    public function setTabData($id){

        $value = self::find($id);

        $data = [
            'entity' => $value,
        ];

        return $data;

    }

}
