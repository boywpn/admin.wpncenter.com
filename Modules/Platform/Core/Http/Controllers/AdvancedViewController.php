<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 24.10.18
 * Time: 13:38
 */

namespace Modules\Platform\Core\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Platform\Core\Entities\AdvancedViews;
use Modules\Platform\Core\Http\Requests\AdvancedViewRequest;
use Modules\Platform\Core\Repositories\AdvancedViewRepository;

/**
 * Class AdvancedViewController
 * @package Modules\Platform\Core\Http\Controllers
 */
class AdvancedViewController extends AppBaseController
{

    private $advancedViewRepo;

    public function __construct(AdvancedViewRepository $advancedViewRepository)
    {
        $this->advancedViewRepo = $advancedViewRepository;
        parent::__construct();
    }


    /**
     * Get for edit
     * @param $id
     * @param Request $request
     * @return array|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|null|string
     */
    public function get($id, Request $request)
    {

        $table = \App::make($request->get('dataTableType'));
        $tableId = $request->get('tableId');
        $mode = $request->get('mode');

        $user = \Auth::user();

        $entity = $this->advancedViewRepo->findWithoutFail($id);

        if (!empty($entity)) {

            $hasPublicAccess = ($entity->is_public && $user->hasPermissionTo('advanced_view.manage_public'));
            $hasPrivateAccess = (!$entity->is_public && ($entity->owner_id == $user->id));

            if ($hasPublicAccess || $hasPrivateAccess) {

                $view = view('core::extension.advanced_view.update');

                $availableColumns = [];
                $selectedColumns = [];

                foreach ($table->availableColumns() as $k => $value) {

                    $underialized = unserialize($entity->defined_columns);

                    if (!empty($underialized) && in_array($k, $underialized)) {
                        $selectedColumns[] = $value;
                    } else {
                        $availableColumns[] = $value;
                    }
                }

                $view->with('moduleName', $entity->module_name);
                $view->with('viewEdit', $entity);
                $view->with('availableColumns', $availableColumns);
                $view->with('selectedColumns', $selectedColumns);
                $view->with('filterDefinitions', $table->getFilterDefinition());
                $view->with('tableId', $tableId);

                return $view;
            }

        } else {
            $view = view('core::extension.advanced_view.create');

            $selectedColumns = [];

            $view->with('moduleName', $request->get('moduleName'));
            $view->with('viewEdit', null);
            $view->with('availableColumns', $table->availableColumns());
            $view->with('selectedColumns', $selectedColumns);
            $view->with('filterDefinitions', $table->getFilterDefinition());
            $view->with('tableId', $tableId);

            return $view;

        }

    }

    /**
     * Delete
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {

        $user = \Auth::user();

        $id = $request->get('id');

        $view = $this->advancedViewRepo->findWithoutFail($id);

        if (!empty($view)) {

            if ($view->is_public && $user->hasPermissionTo('advanced_view.manage_public')) {

                $view->delete();

                return response()->json([
                    'type' => 'success',
                    'message' => trans('core::core.advanced_view.view_deleted'),
                    'action' => 'show_message',
                ]);
            }

            if (!$view->is_public && ($view->owner_id == $user->id)) {

                $view->delete();

                return response()->json([
                    'type' => 'success',
                    'message' => trans('core::core.advanced_view.view_deleted'),
                    'action' => 'show_message',
                ]);
            }

        }

        return response()->json([
            'type' => 'error',
            'message' => trans('core::core.advanced_view.error'),
            'action' => 'show_message',
        ]);
    }

    /**
     * Update
     * @param AdvancedViewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdvancedViewRequest $request)
    {


        $user = \Auth::user();

        $advanedView = $this->advancedViewRepo->findWithoutFail($request->get('filterId'));

        if (!empty($advanedView)) {

            $hasPublicAccess = ($advanedView->is_public && $user->hasPermissionTo('advanced_view.manage_public'));
            $hasPrivateAccess = (!$advanedView->is_public && ($advanedView->owner_id == $user->id));

            if ($hasPublicAccess || $hasPrivateAccess) {

                $advanedView->view_name = $request->get('view_name');

                $visiblity = $request->get('visibility');

                if ($visiblity == 'public') {
                    $advanedView->is_public = true;
                    $advanedView->is_accepted = true;
                } else {
                    $advanedView->is_public = false;
                    $advanedView->is_accepted = true;
                }

                $advanedView->owner_id = \Auth::user()->id;


                $advanedView->filter_rules = $request->get('module_rules');
                $advanedView->defined_columns = serialize($request->get('selected_fields'));
                $advanedView->save();

                if ($advanedView) {

                    return response()->json([
                        'type' => 'success',
                        'message' => trans('core::core.advanced_view.view_updated'),
                        'action' => 'show_message',
                    ]);

                } else {
                    return response()->json([
                        'type' => 'error',
                        'message' => trans('core::core.advanced_view.error'),
                        'action' => 'show_message'
                    ]);
                }

            }


        }


        return response()->json([
            'type' => 'error',
            'message' => trans('core::core.advanced_view.error'),
            'action' => 'show_message'
        ]);


    }

    /**
     * Create advanced view
     *
     * @param AdvancedViewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(AdvancedViewRequest $request)
    {

        $advanedView = new AdvancedViews();
        $advanedView->view_name = $request->get('view_name');
        $advanedView->module_name = $request->get('module_name');

        $visiblity = $request->get('visibility');

        if ($visiblity == 'public') {
            $advanedView->is_public = true;
            $advanedView->is_accepted = true;
        } else {
            $advanedView->is_public = false;
            $advanedView->is_accepted = true;
        }

        $advanedView->owner_id = \Auth::user()->id;

        $advanedView->filter_rules = $request->get('module_rules');
        $advanedView->defined_columns = serialize($request->get('selected_fields'));
        $advanedView->save();

        if ($advanedView) {

            return response()->json([
                'type' => 'success',
                'message' => trans('core::core.advanced_view.new_view_created'),
                'action' => 'show_message',
            ]);

        } else {
            return response()->json([
                'type' => 'error',
                'message' => trans('core::core.advanced_view.error'),
                'action' => 'show_message'
            ]);
        }


    }

}
