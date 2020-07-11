<?php

namespace Modules\Platform\Core\Helper;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Platform\Companies\Http\Controllers\CompanyContextController;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Entities\User;

/**
 * Class FormHelper
 * @package Modules\Platform\Core\Helper
 */
class FormHelper
{
    /**
     * Assign Selected User or Group from Object Model
     * @param $model
     * @return string
     */
    public static function assignSelectedFromModel($model)
    {
        if ($model != null && isset($model->owner) != null) {
            if ($model->owner instanceof User) {
                return 'user-' . $model->owner->id;
            } else {
                return 'group-' . $model->owner->id;
            }
        }
    }

    /**
     * Convert entity to choises
     * @param $entityList
     * @param $fieldName
     * @return array
     */
    public static function entityToChoises($entityList, $fieldName)
    {
        $result = [];

        foreach ($entityList as $e) {
            $result[$e->id] = $e->{$fieldName};
        }

        return $result;
    }

    public static function calendarDefaultView()
    {
        $options = [
            'month' => trans('core::core.dict.month'),
            'agendaWeek' => trans('core::core.dict.basic_week'),
            'agendaDay' => trans('core::core.dict.basic_day'),

        ];

        return $options;
    }

    /**
     * Calendar first day
     * @return array
     */
    public static function calendarFirstDay()
    {
        $options = [
            1 => trans('core::core.dict.monday'),
            0 => trans('core::core.dict.sunday')
        ];
        return $options;
    }

    /**
     * @return array
     */
    public static function calendarDayStartsAt()
    {
        $options = [];

        for ($i = 1; $i <= 9; $i++) {
            $options['0' . $i . ':00:00'] = '0' . $i . ':00:00';
        }

        return $options;
    }


    /**
     * Return share with users
     * @return array
     */
    public static function shareWithChoises()
    {
        if (!empty(Landlord::getTenants()->first())) {
            $users = User::where('company_id', '=', Landlord::getTenants()->first())->get();
        } else {
            $users = User::all();
        }

        return $users->pluck('name', 'id')->toArray();
    }

    /**
     * Return Array of users and groups
     * @return array
     */
    public static function assignedToChoises()
    {

        if(!empty(Landlord::getTenants()->first())){
            $users = User::where('company_id','=',Landlord::getTenants()->first())->get();
            $groups = Group::where('company_id','=',Landlord::getTenants()->first())->get();
        }else{
            $users = User::all();
            $groups = Group::all();
        }


        $options = [
            trans('core::core.form.optgroup.users') => $users->mapWithKeys(function ($item) {
                return ['user-' . $item['id'] => $item['name']];
            })->toArray(),
            trans('core::core.form.optgroup.groups') => $groups->mapWithKeys(function ($item) {
                return ['group-' . $item['id'] => $item['name']];
            })->toArray(),
        ];

        return $options;
    }

    public static function getActionMethod(){
        return \Route::getCurrentRoute()->getActionMethod();
    }
}
