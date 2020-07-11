<?php

namespace Modules\Platform\Core\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Modules\Platform\Core\Helper\StringHelper;
use Modules\Platform\Core\Helper\ValidationHelper;

/**
 * Settings Crud Controller
 *
 * Class SettingsCrudController
 * @package Modules\Platform\Core\Http\Controllers
 */
abstract class SettingsCrudController extends AppBaseController
{
    use FormBuilderTrait;

    /**
     * DataTable in list view (index)
     * @var
     */
    protected $datatable;

    /**
     * Default Crud view
     * @var array
     */
    protected $views = [
        'index' => 'core::crud.settings.index',
        'show' => 'core::crud.settings.show',
        'create' => 'core::crud.settings.create',
        'edit' => 'core::crud.settings.edit',
    ];

    /**
     * Path to language files
     * @var
     */
    protected $languageFile;

    /**
     * All routes
     * @var array
     */
    protected $routes = [

    ];

    /**
     * Show fields in show view and create/edit view
     *
     * Example @UserController
     *
     * @var array
     */
    protected $showFields = [

    ];

    /**
     * Show custom buttons in show view
     * Example @UserController
     * @var array
     */
    protected $customShowButtons = [];


    /**
     * Module Repository
     * @var
     */
    protected $repository;

    /**
     * Module Store Request
     * @var
     */
    protected $storeRequest;

    /**
     * Module Update Request
     * @var
     */
    protected $updateRequest;

    /**
     * Entity Form Class
     * @var
     */
    protected $formClass;

    /**
     * @var bool
     */
    protected $demoMode = false;

    /**
     * Module name - same as module folder
     * Example
     * - User Module = "user"
     * - Settings Module = "settings"
     * @var
     */
    protected $moduleName;

    /**
     * Module Entity
     * @var
     */
    protected $entity;

    /**
     * Additional JavaScript Files to include
     * @var array
     */
    protected $jsFiles = [];

    /**
     * Additional view to include
     * Works with show,create,edit - use for modals!
     * @var array
     */
    protected $includeViews = [

    ];

    /**
     * SettingsCrudController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        \View::share('language_file', $this->languageFile);
        \View::share('routes', $this->routes);
        \View::share('jsFiles', $this->jsFiles);
        \View::share('moduleName', $this->moduleName);
        \View::share('includeViews', $this->includeViews);
    }

    /**
     * Show datatable
     * @return mixed
     */
    public function index()
    {
        $datatable = \App::make($this->datatable);

        $indexView = $this->views['index'];

        return $datatable->render($indexView);
    }

    /**
     * Show create form
     * @return $this
     */
    public function create()
    {
        $createForm = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'id' => 'module_form'

        ]);

        $createView = $this->views['create'];

        $view = view($createView);
        $view->with('form_request', $this->storeRequest);
        $view->with('show_fields', $this->showFields);

        return $view->with('form', $createForm);
    }

    /**
     * Show details
     * @param $identifier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($identifier)
    {
        $repository = \App::make($this->repository);


        $entity = $repository->find($identifier);


        if (empty($entity)) {
            flash(trans($this->languageFile . '.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }


        $showView = $this->views['show'];


        $this->entity = $entity;

        $view = view($showView);
        $view->with('entity', $entity);
        $view->with('show_fields', $this->showFields);

        $view->with('next_record', $repository->next($entity));
        $view->with('prev_record', $repository->prev($entity));

        $this->setupCustomButtons();
        $view->with('customShowButtons', $this->customShowButtons);

        return $view;
    }

    /**
     * Setup Custom Buttons
     */
    protected function setupCustomButtons()
    {
    }

    /**
     * Store entity
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        if ($this->demoMode) {
            if (config('bap.demo')) {
                flash(trans('core::core.you_cant_do_that_its_demo'))->error();
                return redirect()->back();
            }
        }
        $request = \App::make($this->storeRequest ?? Request::class);

        \App::make($this->repository)->create($request->all());

        flash(trans($this->languageFile . '.created'))->success();

        return redirect(route($this->routes['index']));
    }

    /**
     * Edit entity
     * @param $identifier
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit($identifier)
    {
        $repository = \App::make($this->repository);

        $entity = $repository->find($identifier);


        if (empty($entity)) {
            flash(trans($this->languageFile . '.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        $updateForm = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'], $entity),
            'id' => 'module_form',
            'model' => $entity
        ]);


        $updateView = $this->views['edit'];


        $this->entity = $entity;

        $view = view($updateView);
        $view->with('form_request', $this->storeRequest);
        $view->with('entity', $entity);
        $view->with('show_fields', $this->showFields);

        $view->with('form', $updateForm);

        return $view;
    }

    /**
     * Update entity
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($identifier)
    {
        if ($this->demoMode) {
            if (config('bap.demo')) {
                flash(trans('core::core.you_cant_do_that_its_demo'))->error();
                return redirect()->back();
            }
        }

        $request = \App::make($this->updateRequest ?? Request::class);

        $repository = \App::make($this->repository);

        $entity = $repository->find($identifier);

        if (empty($entity)) {
            flash(trans($this->languageFile . '.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        \App::make($this->repository)->update($request->all(), $identifier);

        flash(trans($this->languageFile . '.updated'))->success();

        return redirect(route($this->routes['show'], $entity));
    }

    /**
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($identifier)
    {
        if ($this->demoMode) {
            if (config('bap.demo')) {
                flash(trans('core::core.you_cant_do_that_its_demo'))->error();
                return redirect()->back();
            }
        }

        $repository = \App::make($this->repository);

        $entity = $repository->find($identifier);

        if (empty($entity)) {
            flash(trans($this->languageFile . '.entity_not_found'))->error();
            return redirect(route($this->routes['index']));
        }

        if (config('bap.validate_fk_on_soft_delete')) {
            $validator = ValidationHelper::validateForeignKeys($entity);

            if (count($validator) > 0) {

                flash(trans('core::core.cant_delete_check_fk_keys',['fk_keys' => StringHelper::validationArrayToString($validator)]))->error();

                return redirect(route($this->routes['index']));
            }
        }

        $repository->delete($entity->id);

        flash(trans($this->languageFile . '.deleted'))->success();

        return redirect(route($this->routes['index']));
    }
}
