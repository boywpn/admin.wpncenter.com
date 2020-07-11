<?php

namespace Modules\Orders\Http\Controllers;

use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Modules\Accounts\Entities\Account;
use Modules\Orders\Datatables\OrderDatatable;
use Modules\Orders\Entities\Order;
use Modules\Orders\Http\Forms\OrderForm;
use Modules\Orders\Http\Requests\OrdersRequest;
use Modules\Orders\Service\OrderService;
use Modules\Platform\Core\Datatable\ActivityLogDataTable;
use Modules\Platform\Core\Helper\SettingsHelper;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Repositories\GenericRepository;
use Modules\Products\Entities\Product;

class OrdersController extends ModuleCrudController
{
    protected $datatable = OrderDatatable::class;
    protected $formClass = OrderForm::class;
    protected $storeRequest = OrdersRequest::class;
    protected $updateRequest = OrdersRequest::class;
    protected $entityClass = Order::class;

    protected $moduleName = 'orders';

    protected $permissions = [
        'browse' => 'orders.browse',
        'create' => 'orders.create',
        'update' => 'orders.update',
        'destroy' => 'orders.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'orders.status.index', 'label' => 'settings.status'],
        ['route' => 'orders.carrier.index', 'label' => 'settings.carrier'],


    ];

    protected $settingsPermission = 'orders.settings';

    protected $cssFiles = [
        'BAP_Order.css'
    ];

    protected $jsFiles = [
        'BAP_Order.js'
    ];

    protected $sectionButtons = [

        [
            'section' => 'billing_address',
            'class' => '',
            'id' => 'order-copy-from-shipping',
            'href' => '#',
            'label' => 'copy_from_shipping_address',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_shipping_address',
        ],
        [
            'section' => 'billing_address',
            'class' => 'm-r-10',
            'id' => 'order-copy-from-account',
            'href' => '#',
            'label' => 'copy_from_account',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_account',
        ],
        [
            'section' => 'shipping_address',
            'class' => '',
            'id' => 'order-copy-from-billing',
            'href' => '#',
            'label' => 'copy_from_billing_address',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_billing_address',
        ]
    ];


    protected $showFields = [

        'information' => [

            'order_number' => [
                'type' => 'text',
            ],


            'carrier_number' => [
                'type' => 'text',
            ],


            'deal_id' => [
                'type' => 'manyToOne',
                'relation' => 'deal',
                'column' => 'name',
                'dont_translate' => true
            ],

            'customer_no' => [
                'type' => 'text',
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



            'purchase_order' => [
                'type' => 'text',
            ],


            'due_date' => [
                'type' => 'date',
            ],

            'order_date' => [
                'type' => 'date',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'order_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'orderStatus',
                'column' => 'name'
            ],


            'order_carrier_id' => [
                'type' => 'manyToOne',
                'relation' => 'orderCarrier',
                'column' => 'name',
                'dont_translate' => true
            ],

        ],


        'billing_address' => [

            'bill_to' => [
                'type' => 'text',
            ],

            'bill_tax_number' => [
                'type' => 'text',
            ],

            'bill_street' => [
                'type' => 'text',
            ],

            'bill_city' => [
                'type' => 'text',
            ],

            'bill_state' => [
                'type' => 'text',
            ],


            'bill_country' => [
                'type' => 'text',
            ],


            'bill_zip_code' => [
                'type' => 'text',
            ],

        ],


        'shipping_address' => [

            'ship_to' => [
                'type' => 'text',
            ],

            'ship_tax_number' => [
                'type' => 'text',
            ],

            'ship_street' => [
                'type' => 'text',
            ],

            'ship_city' => [
                'type' => 'text',
            ],


            'ship_state' => [
                'type' => 'text',
            ],


            'ship_country' => [
                'type' => 'text',
            ],


            'ship_zip_code' => [
                'type' => 'text',
            ],

        ],


        'terms' => [

            'terms_and_cond' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-md-12 col-sm-12'
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

            'paid' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-6',
                'in_show_view' => true
            ],

            'rows' => [
                'type' => 'text',
                'col-class' => '',
                'in_show_view' => false
            ],


        ],


    ];

    protected $languageFile = 'orders::orders';

    protected $routes = [
        'index' => 'orders.orders.index',
        'create' => 'orders.orders.create',
        'show' => 'orders.orders.show',
        'edit' => 'orders.orders.edit',
        'store' => 'orders.orders.store',
        'destroy' => 'orders.orders.destroy',
        'update' => 'orders.orders.update'
    ];

    private $orderService;

    public function __construct(OrderService  $orderService)
    {
        parent::__construct();

        $this->orderService = $orderService;
    }

    /**
     * Order print
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function printOrder($identifier)
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

        $pdf = \PDF::loadView('orders::pdf.print', $printData);

        return $pdf->inline($entity->order_number . '_ORDER.pdf');
    }


    public function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'href' => route('orders.orders.print', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect pull-right',
            ],
            'label' => trans('orders::orders.print')
        );
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
            'href' => route('orders.orders.convert.to.invoice', ['id' => $this->entity->id]),
            'attr' => [

            ],
            'label' => trans('orders::orders.convert_to_invoice')
        );
    }

    public function convertToInvoice($orderId){

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $orderService = \App::make(OrderService::class);

        $invoice = $orderService->convertToInvoice($orderId);

        if(!empty($invoice)){
            flash(trans('core::core.record_converted'))->success();

            return redirect()->route('invoices.invoices.show',$invoice->id);
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

        $this->beforeShow(request(), $entity);

        $view = view('orders::show');

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

        $request = \App::make($this->updateRequest ?? Request::class);

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

        $this->orderService->saveRows($entity, $request['rows']);

        $entity = $repository->updateEntity($input, $entity);

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

        $this->orderService->saveRows($entity, $request['rows']);

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
     * Return company settings to set in order
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

        $product =  Product::find($productId);

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
                'quantity' => config('orders.default_quantity', 1),
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
