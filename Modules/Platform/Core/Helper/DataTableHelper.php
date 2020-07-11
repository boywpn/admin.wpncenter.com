<?php

namespace Modules\Platform\Core\Helper;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Support\Facades\Cache;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Entities\User;

/**
 * DataTable helper
 *
 * Class DataTableHelper
 * @package Modules\Platform\Core\Helper
 */
class DataTableHelper
{
    const FILTER_DROP_DOWN_CACHE = 1440;

    public static function buttons($title = 'Exported records')
    {
        return
            [
                [
                    'extend' => 'reset',
                    'text' => trans('core::core.reset'),
                ],
                [
                    'extend' => 'copy',
                    'title' => $title,
                    'text' => trans('core::core.copy'),
                    'exportOptions' => [
                        'format' => [
                            'header' => "function(mDataProp,columnIdx) { 
                                var htmlText = '<span>' + mDataProp + '</span>';
                                var jHtmlObject = jQuery(htmlText);
                                jHtmlObject.find('div').remove();
                                var newHtml = jHtmlObject.text();
                                return newHtml;
                                }"
                        ]
                    ]
                ],
                [
                    'extend' => 'print',
                    'title' => $title,
                    'text' => trans('core::core.print'),
                    'exportOptions' => [
                        'format' => [
                            'header' => "function(mDataProp,columnIdx) { 
                                var htmlText = '<span>' + mDataProp + '</span>';
                                var jHtmlObject = jQuery(htmlText);
                                jHtmlObject.find('div').remove();
                                var newHtml = jHtmlObject.text();
                                return newHtml;
                                }"
                        ]
                    ]
                ],
                [
                    'extend' => 'excelHtml5',
                    'title' => $title,
                    'text' => trans('core::core.excel'),
                    'exportOptions' => [
                        'format' => [
                            'header' => "function(mDataProp,columnIdx) { 
                                var htmlText = '<span>' + mDataProp + '</span>';
                                var jHtmlObject = jQuery(htmlText);
                                jHtmlObject.find('div').remove();
                                var newHtml = jHtmlObject.text();
                                return newHtml;
                                }"
                        ]
                    ]
                ],
                [
                    'extend' => 'pdfHtml5',
                    'title' => $title,
                    'text' => trans('core::core.pdf'),
                    'orientation' => 'landscape',
                    'exportOptions' => [
                        'format' => [
                            'header' => "function(mDataProp,columnIdx) { 
                                var htmlText = '<span>' + mDataProp + '</span>';
                                var jHtmlObject = jQuery(htmlText);
                                jHtmlObject.find('div').remove();
                                var newHtml = jHtmlObject.text();
                                return newHtml;
                                }"
                        ]
                    ]
                ]
            ];
    }

    /**
     *
     * @return array
     */
    public static function filterOwnerDropdown()
    {


        if (!empty(Landlord::getTenants()->first())) {

            $tenantId = Landlord::getTenants()->first();

            $users = Cache::remember('users_for_company_'.$tenantId, 60, function(){
                return User::where('company_id', '=', Landlord::getTenants()->first())->get();
            });

            $groups = Cache::remember('groups_for_company_'.$tenantId, 60, function(){
                return Group::where('company_id', '=', Landlord::getTenants()->first())->get();
            });
        } else {
            $users = User::all();
            $groups = Group::all();
        }

        $result = [];

        foreach ($users as $user) {
            $result[] = [
                'value' => $user->name,
                'label' => $user->name
            ];
        }

        foreach ($groups as $group) {
            $result[] = [
                'value' => $group->name,
                'label' => $group->name
            ];
        }


        return $result;
    }

    public static function queryCauser($query, $keyword)
    {
        $users = User::where('name', 'LIKE', '%' . $keyword . '%')->get()->pluck('id')->toArray();

        if (count($users) > 0) {
            $query->whereIn('causer_id', $users);
        }
    }

    /**
     * @param $query
     * @param $keyword
     * @param null $tableName - Use this where joining with others table
     */
    public static function queryOwner($query, $keyword, $tableName = null)
    {
        $users = User::where('name', 'LIKE', '%' . $keyword . '%')->get()->pluck('id')->toArray();
        $groups = Group::where('name', 'LIKE', '%' . $keyword . '%')->get()->pluck('id')->toArray();

        if ($tableName != null) {
            $tableName = $tableName . '.';
        }

        if (count($users) > 0 && count($groups) > 0) {
            $query->where(function ($q) use ($tableName) {
                $q->where($tableName . 'owned_by_type', Group::class);
                $q->orWhere($tableName . 'owned_by_type', User::class);
            });
        } else {
            if (count($users) > 0) {
                $query->where(function ($q) use ($tableName) {
                    $q->orWhere($tableName . 'owned_by_type', User::class);
                });
            } else {
                if (count($groups) > 0) {
                    $query->where(function ($q) use ($tableName) {
                        $q->orWhere($tableName . 'owned_by_type', Group::class);
                    });
                }
            }
        }

        $array = array_merge($users, $groups);
        $query->whereIn($tableName . 'owned_by_id', $array);
    }

    /**
     * @param $keyword
     * @return array|mixed
     */
    public static function getDatesForFilter($keyword)
    {
        if (strpos($keyword, ' - ') !== false) {
            $dates = str_replace('%%', '', $keyword);

            $dates = explode(" - ", $dates);

            if ($dates[0] != '') {
                $dates[0] = DateHelper::formatDateToUTC($dates[0] . ' 00:00:00');
            }
            if ($dates[1] != '') {
                $dates[1] = DateHelper::formatDateToUTC($dates[1] . ' 23:59:59');
            }

            return $dates;
        }
    }


    /**
     * Render column link
     *
     * @param $column
     * @param $record
     * @param $columnProperties
     * @param $route
     * @return string
     */
    public static function renderLink($column, $record, $columnProperties, $route)
    {
        $displayColumn = $record->$column;

        if ($route != '') {
            $href = route($route, $record->id);
        } else {
            $href = '';
        }

        $datatype = 'text';

        if (isset($columnProperties['data_type'])) {
            $datatype = $columnProperties['data_type'];
        }

        if ($datatype == 'boolean') {
            $displayColumn = trans('core::core.yes_no.' . $displayColumn);
        }
        if ($datatype == 'image') {
            if (!empty($displayColumn)) {

                $displayColumn = "<img class='datatable_image' src='" . asset($displayColumn) . "' />";

                if (!empty($href)) {
                    return '<a data-column="' . strip_tags($column) . '" title="' . strip_tags($displayColumn) . '" href="' . $href . '"> ' . ($displayColumn) . '</a>';
                }
                return $displayColumn;

            }
        }

        if ($datatype == 'none') {
            return $displayColumn;
        }
        if ($datatype == 'datetime') {
            $displayColumn = UserHelper::formatUserDateTime($displayColumn);
        }
        if ($datatype == 'assigned_to') {
            if ($record->owned_by != null) {
                $displayColumn = $record->owned_by->name;
            }
        }

        if ($datatype == 'email') {
            $href = 'mailto:' . $record->$column;
        }
        if ($datatype == 'date') {
            $displayColumn = UserHelper::formatUserDate($displayColumn);
        }

        if (!empty($href)) {
            $link = '<a data-column="' . strip_tags($column) . '" title="' . strip_tags($displayColumn) . '" href="' . $href . '"> ' . strip_tags($displayColumn) . '</a>';
        } else {
            $link = strip_tags($displayColumn);
        }


        return $link;
    }
}
