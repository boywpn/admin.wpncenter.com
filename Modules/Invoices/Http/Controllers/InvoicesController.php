<?php

namespace Modules\Invoices\Http\Controllers;

use Cog\Contracts\Ownership\Ownable;
use Illuminate\Http\Request;
use Modules\Accounts\Entities\Account;
use Modules\Invoices\Datatables\InvoiceDatatable;
use Modules\Invoices\Entities\Invoice;
use Modules\Invoices\Http\Forms\InvoiceForm;
use Modules\Invoices\Http\Requests\InvoicesRequest;
use Modules\Invoices\Service\InvoiceService;
use Modules\Platform\Core\Datatable\ActivityLogDataTable;
use Modules\Platform\Core\Helper\SettingsHelper;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Repositories\GenericRepository;
use Modules\Products\Entities\Product;

class InvoicesController extends ModuleCrudController
{
    protected $datatable = InvoiceDatatable::class;
    protected $formClass = InvoiceForm::class;
    protected $storeRequest = InvoicesRequest::class;
    protected $updateRequest = InvoicesRequest::class;
    protected $entityClass = Invoice::class;

    protected $moduleName = 'invoices';

    protected $permissions = [
        'browse' => 'invoices.browse',
        'create' => 'invoices.create',
        'update' => 'invoices.update',
        'destroy' => 'invoices.destroy'
    ];

    protected $cssFiles = [
        'BAP_Invoice.css'
    ];

    protected $jsFiles = [
        'BAP_Invoice.js'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'invoices.status.index', 'label' => 'settings.status'],
    ];

    protected $settingsPermission = 'invoices.settings';

    protected $sectionButtons = [
        [
            'section' => 'invoice_from',
            'class' => '',
            'id' => 'invoice-copy-from-company',
            'href' => '#',
            'label' => 'copy_from_company_settings',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_company_settings',
        ],
        [
            'section' => 'billing_address',
            'class' => '',
            'id' => 'invoice-copy-from-shipping',
            'href' => '#',
            'label' => 'copy_from_shipping_address',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_shipping_address',
        ],
        [
            'section' => 'billing_address',
            'class' => 'm-r-10',
            'id' => 'invoice-copy-from-account',
            'href' => '#',
            'label' => 'copy_from_account',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_account',
        ],
        [
            'section' => 'shipping_address',
            'class' => '',
            'id' => 'invoice-copy-from-billing',
            'href' => '#',
            'label' => 'copy_from_billing_address',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_billing_address',
        ]
    ];

    protected $showFields = [

        'information' => [

            'invoice_number' => [
                'type' => 'text',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],

            'order_id' => [
                'type' => 'manyToOne',
                'relation' => 'order',
                'column' => 'order_number',
                'dont_translate' => true,
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],

            'customer_no' => [
                'type' => 'text',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],

            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true,
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],


            'invoice_date' => [
                'type' => 'date',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],


            'due_date' => [
                'type' => 'date',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],


            'owned_by' => [
                'type' => 'assigned_to',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],


            'invoice_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'invoiceStatus',
                'column' => 'name',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],

            'contact_id' => [
                'type' => 'manyToOne',
                'relation' => 'contact',
                'column' => 'full_name',
                'dont_translate' => true,
                'col-class' => 'col-lg-3 col-md-3 col-sm-6'
            ],

            'account_number' => [
                'type' => 'text',
                'col-class' => 'col-lg-9 col-md-9 col-sm-9'
            ],

        ],

        'invoice_from' => [

            'from_company' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6'
            ],
            'from_tax_number' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6'
            ],

            'from_street' => [
                'type' => 'text',
                'col-class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-6'
            ],

            'from_city' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],


            'from_state' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],


            'from_country' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],


            'from_zip_code' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],

        ],

        'billing_address' => [

            'bill_to' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6'
            ],
            'bill_tax_number' => [
                'type' => 'text',
                'col-class' => 'col-lg-6  col-md-6 col-sm-6 col-xs-6'
            ],

            'bill_street' => [
                'type' => 'text',
                'col-class' => 'col-lg-3  col-md-3 col-sm-6 col-xs-6'
            ],

            'bill_city' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],


            'bill_state' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],


            'bill_country' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],


            'bill_zip_code' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],

        ],


        'shipping_address' => [

            'ship_to' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-md-6 col-sm-6 col-xs-6'
            ],
            'ship_tax_number' => [
                'type' => 'text',
                'col-class' => 'col-lg-6  col-md-6 col-sm-6 col-xs-6'
            ],

            'ship_street' => [
                'type' => 'text',
                'col-class' => 'col-lg-3  col-md-3 col-sm-6 col-xs-6'
            ],

            'ship_city' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],

            'ship_state' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],


            'ship_country' => [
                'type' => 'text',
                'col-class' => 'col-lg-2  col-md-2 col-sm-6 col-xs-6'
            ],


            'ship_zip_code' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],

        ],


        'terms' => [

            'terms_and_cond' => [
                'type' => 'text',
                'col-class' => 'col-lg-12  col-md-12 col-sm-12 col-xs-12'
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12  col-md-12 col-sm-12 col-xs-12'
            ],

        ],

        'tax_and_currency' => [
            'tax_id' => [
                'type' => 'manyToOne',
                'relation' => 'tax',
                'column' => 'name',
                'col-class' => 'col-lg-4  col-md-4 col-sm-4 col-xs-6',
                'dont_translate' => true,
            ],

            'currency_id' => [
                'type' => 'manyToOne',
                'relation' => 'currency',
                'column' => 'code',
                'col-class' => 'col-lg-4  col-md-4 col-sm-4 col-xs-6',
                'dont_translate' => true,
            ],

            'delivery_cost' => [
                'type' => 'text',
                'col-class' => 'col-lg-4  col-md-4 col-sm-4 col-xs-6',
                'in_show_view' => false
            ],

            'discount' => [
                'type' => 'text',
                'col-class' => 'col-lg-4  col-md-4 col-sm-4 col-xs-6',
                'in_show_view' => false
            ],

            'paid' => [
                'type' => 'text',
                'col-class' => 'col-lg-4  col-md-4 col-sm-4 col-xs-6',
                'in_show_view' => true
            ],

            'rows' => [
                'type' => 'text',
                'col-class' => '',
                'in_show_view' => false
            ],


        ],


    ];

    protected $languageFile = 'invoices::invoices';

    protected $routes = [
        'index' => 'invoices.invoices.index',
        'create' => 'invoices.invoices.create',
        'show' => 'invoices.invoices.show',
        'edit' => 'invoices.invoices.edit',
        'store' => 'invoices.invoices.store',
        'destroy' => 'invoices.invoices.destroy',
        'update' => 'invoices.invoices.update'
    ];

    private $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        parent::__construct();

        $this->invoiceService = $invoiceService;
    }

    /**
     * Invoice print
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function printInvoice($identifier)
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

        $pdf = \PDF::loadView('invoices::pdf.print', $printData);

        return $pdf->inline($entity->invoice_number . '_INVOICE.pdf');
    }

    public function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'href' => route('invoices.invoices.print', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect pull-right',
            ],
            'label' => trans('invoices::invoices.print')
        );
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

        $view = view('invoices::show');

        $view->with('entity', $entity);
        $view->with('show_fields', $this->showFields);
        $view->with('show_fileds_count', count($this->showFields));

        $view->with('next_record', $repository->next($entity));
        $view->with('prev_record', $repository->prev($entity));
        $view->with('disableNextPrev', $this->disableNextPrev);

        $this->setupCustomButtons();
        $this->setupActionButtons();
        $view->with('customShowButtons', $this->customShowButtons);
        $view->with('actionButtons', $this->actionButtons);
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

        $this->afterUpdate(request(), $entity, $repository);

        $this->invoiceService->saveRows($entity, $request['rows']);

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

        $this->invoiceService->saveRows($entity, $request['rows']);

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
     * Return company settings to set in invoice
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
            'product_id' => 0,
        ];

        if ($product != null) {

            $productSuffix = '';

            if($product->priceList()->count() > 0){
                $productSuffix = ' - '.$product->priceList()->first()->name;
            }

            $productData = [
                'product_name' => $product->name . $productSuffix,
                'unit_cost' => $product->priceList()->count() > 0 ? $product->priceList()->first()->price : $product->price,
                'quantity' => config('invoices.default_quantity', 1),
                'product_id' => $product->id,
                'multi_price' => $product->priceList()->count()
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
