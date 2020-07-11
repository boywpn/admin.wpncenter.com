<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Products\Datatables\PriceListDatatable;
use Modules\Products\Datatables\ProductDatatable;
use Modules\Products\Entities\PriceList;
use Modules\Products\Http\Forms\PriceListForm;
use Modules\Products\Http\Requests\PriceListRequest;

class PriceListController extends ModuleCrudController
{
    protected $datatable = PriceListDatatable::class;
    protected $formClass = PriceListForm::class;
    protected $storeRequest = PriceListRequest::class;
    protected $updateRequest = PriceListRequest::class;
    protected $entityClass = PriceList::class;

    protected $moduleName = 'products';

    protected $permissions = [
        'browse' => 'products.browse',
        'create' => 'products.create',
        'update' => 'products.update',
        'destroy' => 'products.destroy'
    ];

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
                'col-class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-6'
            ],
            'price' => [
                'type' => 'decimal',
                'col-class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-6'
            ],

            'owned_by' => [
                'type' => 'assigned_to',
                'col-class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-6'
            ],

            'product_id' => [
                'type' => 'manyToOne',
                'relation' => 'product',
                'column' => 'name',
                'dont_translate' => true,
                'col-class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-6'
            ],
        ],

    ];

    public function beforeIndex($request, &$datatable)
    {


        $productId = $request->get('productId');

        $datatable->setAdditionalValues([
            'productId' => $productId
        ]);

    }


    protected $languageFile = 'products::price_list';

    protected $routes = [
        'index' => 'products.price_list.index',
        'create' => 'products.price_list.create',
        'show' => 'products.price_list.show',
        'edit' => 'products.price_list.edit',
        'store' => 'products.price_list.store',
        'destroy' => 'products.price_list.destroy',
        'update' => 'products.price_list.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Load price list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadPriceList(Request $request)
    {
        $priceListId = $request->get('priceListId', null);

        $priceList = PriceList::find($priceListId);

        $priceListData = [
            'name' => '',
            'price' => '',
            'price_list_id' => 0,
            'product_id' => ''
        ];

        if ($priceList != null) {
            $priceListData = [
                'name' => $priceList->name,
                'price' => $priceList->price,
                'price_list_id' => $priceList->id,
                'product_id' => $priceList->product_id,
            ];

            if(!empty($priceList->product)){
                $priceListData['product_name'] = $priceList->product->name.' - '.$priceList->name;
            }

        }

        return response()->json([
            'data' => $priceListData
        ]);
    }

}
