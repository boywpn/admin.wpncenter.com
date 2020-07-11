<?php

namespace Modules\Platform\Core\Repositories;

use Carbon\Carbon;
use Modules\Platform\Core\Entities\AdvancedViews;
use Modules\Platform\Core\Entities\Comment;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class AdvancedViewRepository
 * @package Modules\Platform\Core\Repositories
 */
class AdvancedViewRepository extends PlatformRepository
{
    public function model()
    {
        return AdvancedViews::class;
    }


    /**
     * Get view list for module
     *
     * @param $moduleName
     * @return array
     */
    public function getForModule($moduleName){


        $records = AdvancedViews::query()
            ->orderBy('view_name','asc')
            ->where('module_name',$moduleName)
            ->where(function($subquery){
                $subquery->where(function($q){
                    $q->where('is_public',1)->where('is_accepted',1);
                });
                $subquery->orWhere(function($q){
                    $q->where('is_public',0)->where('owner_id',\Auth::user()->id);
                });
            });

        $result =  [
            'public' => [],
            'private' => []
        ];

        foreach ($records->get() as $record){

            if($record->is_public){
                $result['public'][] = $record;
            }else{
                $result['private'][] = $record;
            }

        }

        return $result;
    }

    public function getSingleView($moduleName,$viewId){

    }
}
