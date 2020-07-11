<?php

namespace Modules\Quotes\Http\Controllers;

use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Modules\Accounts\Entities\Account;
use Modules\Quotes\Service\QuoteService;
use Modules\Platform\Core\Datatable\ActivityLogDataTable;
use Modules\Platform\Core\Helper\SettingsHelper;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Repositories\GenericRepository;
use Modules\Products\Entities\Product;
use Modules\Quotes\Datatables\QuoteDatatable;
use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Http\Forms\QuoteForm;
use Modules\Quotes\Http\Requests\QuotesRequest;

class QuotesController extends ModuleCrudController
{
    protected $datatable = QuoteDatatable::class;
    protected $formClass = QuoteForm::class;
    protected $storeRequest = QuotesRequest::class;
    protected $updateRequest = QuotesRequest::class;
    protected $entityClass = Quote::class;

    protected $moduleName = 'quotes';

    protected $permissions = [
        'browse' => 'quotes.browse',
        'create' => 'quotes.create',
        'update' => 'quotes.update',
        'destroy' => 'quotes.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'quotes.stage.index', 'label' => 'settings.stage'],
        ['route' => 'quotes.carrier.index', 'label' => 'settings.carrier'],


    ];

    protected function setupCustomButtons()
    {

        $this->customShowButtons[] = array(
            'href' => route('quotes.quotes.print', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect pull-right',
            ],
            'label' => trans('quotes::quotes.print')
        );
    }

    protected $sectionButtons = [

        [
            'section' => 'address_information',
            'class' => 'm-r-10',
            'id' => 'quote-copy-from-account',
            'href' => '#',
            'label' => 'copy_from_account',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_account',
        ]
    ];

    protected $settingsPermission = 'quotes.settings';

    protected $cssFiles = [
        'BAP_Quote.css'
    ];

    protected $jsFiles = [
        'BAP_Quote.js'
    ];

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'valid_unitl' => [
                'type' => 'date',
            ],

            'amount' => [
                'type' => 'text',
            ],


            'shipping' => [
                'type' => 'text',
            ],


            'quote_stage_id' => [
                'type' => 'manyToOne',
                'relation' => 'quoteStage',
                'column' => 'name'
            ],


            'quote_carrier_id' => [
                'type' => 'manyToOne',
                'relation' => 'quoteCarrier',
                'column' => 'name',
                'dont_translate' => true
            ],

            'contact_id' => [
                'type' => 'manyToOne',
                'relation' => 'contact',
                'column' => 'full_name',
                'dont_translate' => true
            ],

            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true
            ],

        ],


        'address_information' => [

            'street' => [
                'type' => 'text',
            ],

            'city' => [
                'type' => 'text',
            ],

            'state' => [
                'type' => 'text',
            ],


            'country' => [
                'type' => 'text',
            ],


            'zip_code' => [
                'type' => 'text',
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-md-12 col-sm-12'
            ],

        ],


        'tax_and_currency' => [

            'tax_id' => [
                'type' => 'manyToOne',
                'relation' => 'tax',
                'column' => 'name',
                'col-class' => 'col-lg-4 col-md-4 col-sm-6',
                'dont_translate' => true,
            ],

            'currency_id' => [
                'type' => 'manyToOne',
                'relation' => 'currency',
                'column' => 'code',
                'col-class' => 'col-lg-4 col-md-4 col-sm-6',
                'dont_translate' => true,
            ],

            'delivery_cost' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-6',
                'in_show_view' => false
            ],

            'discount' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-6',
                'in_show_view' => false
            ],

            'rows' => [
                'type' => 'text',
                'col-class' => '',
                'in_show_view' => false
            ],


        ],


    ];

    protected $languageFile = 'quotes::quotes';

    protected $routes = [
        'index' => 'quotes.quotes.index',
        'create' => 'quotes.quotes.create',
        'show' => 'quotes.quotes.show',
        'edit' => 'quotes.quotes.edit',
        'store' => 'quotes.quotes.store',
        'destroy' => 'quotes.quotes.destroy',
        'update' => 'quotes.quotes.update'
    ];

    private $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        parent::__construct();

        $this->quoteService = $quoteService;
    }

    /**
     * Quote print
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function printQuote($identifier)
    {

        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $this->entityIdentifier = $entity->id;

        $this->entity = $entity;

        $printData = [
            'entity' => $this->entity
        ];

        $pdf = \PDF::loadView('quotes::pdf.print', $printData);

        return $pdf->inline($identifier . '_QUOTE.pdf');
    }

    protected function setupActionButtons()
    {
        $this->actionButtons[] = array(
            'href' => route($this->routes['create'], ['copy' => $this->entity->id]),
            'attr' => [

            ],
            'label' => trans('core::core.btn.copy')
        );
        $this->actionButtons[] = array(
            'href' => route('quotes.quotes.convert.to.order', ['id' => $this->entity->id]),
            'attr' => [

            ],
            'label' => trans('quotes::quotes.convert_to_order')
        );
    }

    public function convertToOrder($quoteId){

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $quoteService = \App::make(QuoteService::class);

        $order = $quoteService->convertQuoteToOrder($quoteId);

        if(!empty($order)){
            flash(trans('core::core.record_converted'))->success();

            return redirect()->route('orders.orders.show',$order->id);
        }

        flash(trans('core::core.error_while_converting'))->error();

        return redirect()->route($this->routes['index']);

    }


    /**
     * Overwritten show function
     *
     * @param $identifier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();


        $entity = $repository->find($identifier);


        $this->entity = $entity;

        $this->beforeShow(request(), $entity);

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $this->entityIdentifier = $entity->id;

        $this->entity = $entity;

        $view = view('quotes::show');

        $view->with('entity', $entity);
        $view->with('show_fields', $this->showFields);
        $view->with('show_fileds_count', count($this->showFields));

        $view->with('next_record', $repository->next($entity));
        $view->with('prev_record', $repository->prev($entity));
        $view->with('disableNextPrev', $this->disableNextPrev);

        $this->setupCustomButtons();
        $this->setupActionButtons();
        $view->with('customShowButtons', $this->customShowButtons);
        $view->with('actionButtons',$this->actionButtons);
        $view->with('commentableExtension', false);
        $view->with('actityLogDatatable', null);
        $view->with('attachmentsExtension', false);
        $view->with('entityIdentifier', $this->entityIdentifier);


        $view->with('hasExtensions', false);

        $view->with('relationTabs', $this->setupRelationTabs($entity));

        $view->with('baseIcons', $this->baseIcons);

        /*
         * Extensions
         */

        if (in_array(self::COMMENTS_EXTENSION, class_uses($this->entity))) {
            $view->with('commentableExtension', true);
            $view->with('hasExtensions', true);
        }
        if (in_array(self::ACTIVITY_LOG_EXTENSION, class_uses($this->entity))) {
            $activityLogDataTable = \App::make(ActivityLogDataTable::class);
            $activityLogDataTable->setEntityData(get_class($entity), $entity->id);
            $view->with('actityLogDatatable', $activityLogDataTable->html());
            $view->with('hasExtensions', true);
        }
        if (in_array(self::ATTACHMENT_EXTENSION, class_uses($this->entity))) {
            $view->with('attachmentsExtension', true);
            $view->with('hasExtensions', true);
        }

        return $view;
    }


    /**
     * Overloaded function allow to save rows
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($identifier)
    {
        if ($this->permissions['update'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['update'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $request = \App::make($this->updateRequest ?? \Request::class);

        $mode = $request->get('entityCreateMode', self::FORM_MODE_FULL);

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $input = $this->form($this->formClass)->getFieldValues(true);

        $currentOwner = null;
        if ($entity instanceof Ownable && $entity->hasOwner()) {
            $currentOwner = $entity->getOwner();
        }

        $this->beforeUpdate(request(), $entity, $input);

        $entity = $this->setupAssignedTo($entity, $input);

        $repository = $this->getRepository();

        $entity = $repository->updateEntity($input, $entity);

        $this->quoteService->saveRows($entity, $request['rows']);

        $this->afterUpdate(request(), $entity, $repository);

        $this->entity = $entity;

        if ($mode == self::FORM_MODE_SIMPLE) {
            return response()->json([
                'type' => 'success',
                'message' => trans('core::core.entity.updated'),
                'action' => 'refresh_datatable'
            ]);
        }

        flash(trans('core::core.entity.updated'))->success();

        return redirect(route($this->routes['show'], $entity));
    }

    /**
     * Overloaded function allow to save rows
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $request = \App::make($this->storeRequest ?? Request::class);

        $mode = $request->get('entityCreateMode', self::FORM_MODE_FULL);

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            if ($mode == self::FORM_MODE_SIMPLE) {
                return response()->json([
                    'type' => 'error',
                    'message' => trans('core::core.entity.you_dont_have_access'),
                    'action' => 'show_message'
                ]);
            }
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $storeValues = $this->form($this->formClass)->getFieldValues(true);

        if ($mode == self::FORM_MODE_SIMPLE) {

            //Bind related element
            $relatedEntityId = $request->get('relatedEntityId');
            $relationType = $request->get('relationType', null);
            $relatedField = $request->get('relatedField');
            $relatedEntity = $request->get('relatedEntity');

            if ($relationType != null) { // Relation type is not null

                $relationEntityRepos = \App::make(GenericRepository::class);
                $relationEntityRepos->setupModel($relatedEntity);

                $relationEntity = $relationEntityRepos->findWithoutFail($relatedEntityId);

                if ($relationType == 'oneToMany') {
                    $storeValues[$relatedField] = $relationEntity->id;
                }
            }
        }

        $this->beforeStore($request);

        $entity = $repository->createEntity($storeValues, \App::make($this->entityClass));

        $entity = $this->setupAssignedTo($entity, $request, true);
        $entity->save();

        $this->quoteService->saveRows($entity, $request['rows']);

        $this->afterStore($request, $entity);

        if ($mode == self::FORM_MODE_SIMPLE) {

            //Bind related element
            $relatedEntityId = $request->get('relatedEntityId');
            $relationType = $request->get('relationType', null);
            $relatedField = $request->get('relatedField');
            $relatedEntity = $request->get('relatedEntity');

            if ($relationType != null) { // Relation type is not null

                $relationEntityRepos = \App::make(GenericRepository::class);
                $relationEntityRepos->setupModel($relatedEntity);


                $relationEntity = $relationEntityRepos->findWithoutFail($relatedEntityId);


                if ($relationType == 'manyToMany') {
                    $entity->{$relatedField}()->attach($relationEntity->id);
                }
            }

            return response()->json([
                'type' => 'success',
                'message' => trans('core::core.entity.created'),
                'action' => 'refresh_datatable'
            ]);
        }

        flash(trans('core::core.entity.created'))->success();

        return redirect(route($this->routes['index']));
    }


    /**
     * Return company settings to set in quote
     * @return \Illuminate\Http\JsonResponse
     */
    public function companySettings()
    {
        return response()->json([
            'data' => SettingsHelper::companySettings()
        ]);
    }

    /**
     * Load product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadProduct(Request $request)
    {
        $productId = $request->get('productId', null);

        $product = Product::find($productId);

        $productData = [
            'product_name' => '',
            'unit_cost' => '',
            'quantity' => '',
            'product_id' => 0
        ];

        if ($product != null) {
            $productData = [
                'product_name' => $product->name,
                'unit_cost' => $product->price,
                'quantity' => config('quotes.default_quantity', 1),
                'product_id' => $product->id
            ];
        }

        return response()->json([
            'data' => $productData
        ]);
    }

    /**
     * return data from related account
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyDataFromAccount(Request $request)
    {
        $accountId = $request->get('accountId', null);

        $account = Account::find($accountId);

        $accountData = [
            'company_name' => '',
            'address' => '',
            'city' => '',
            'postal_code' => '',
            'country' => '',
            'phone' => '',
            'fax' => '',
            'vat_id' => ''
        ];

        if (!empty($account)) {
            $accountData = [
                'company_name' => $account->name,
                'address' => $account->street,
                'city' => $account->city,
                'state' => $account->state,
                'postal_code' => $account->zip_code,
                'country' => $account->country,
                'phone' => $account->phone,
                'fax' => $account->fax,
                'vat_id' => $account->tax_number,
            ];
        }

        return response()->json(
            [
                'data' => $accountData
            ]
        );
    }
}
