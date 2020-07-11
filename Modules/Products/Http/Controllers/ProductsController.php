<?php

namespace Modules\Products\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Products\Datatables\ProductDatatable;
use Modules\Products\Datatables\Tabs\PriceListDatatable;
use Modules\Products\Entities\Product;
use Modules\Products\Helper\ProductHelper;
use Modules\Products\Http\Forms\ProductForm;
use Modules\Products\Http\Requests\ProductsRequest;


class ProductsController extends ModuleCrudController
{
    protected $datatable = ProductDatatable::class;
    protected $formClass = ProductForm::class;
    protected $storeRequest = ProductsRequest::class;
    protected $updateRequest = ProductsRequest::class;
    protected $entityClass = Product::class;

    protected $moduleName = 'products';

    protected $permissions = [
        'browse' => 'products.browse',
        'create' => 'products.create',
        'update' => 'products.update',
        'destroy' => 'products.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'products.type.index', 'label' => 'settings.type'],
        ['route' => 'products.category.index', 'label' => 'settings.category'],


    ];

    protected $jsFiles = [
        'BAP_Products.js'
    ];

    protected $settingsPermission = 'products.settings';

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],

            'image_path' => ['type' => 'image'],

            'part_number' => [
                'type' => 'text',
            ],


            'vendor_part_number' => [
                'type' => 'text',
            ],


            'product_sheet' => [
                'type' => 'text',
            ],


            'website' => [
                'type' => 'text',
            ],


            'serial_no' => [
                'type' => 'text',
            ],


            'price' => [
                'type' => 'decimal',
            ],

            'owned_by' => [
                'type' => 'assigned_to',
            ],

            'vendor_id' => [
                'type' => 'manyToOne',
                'relation' => 'vendor',
                'column' => 'name',
                'dont_translate' => true
            ],


            'product_type_id' => [
                'type' => 'manyToOne',
                'relation' => 'productType',
                'column' => 'name'
            ],

            'product_category_id' => [
                'type' => 'manyToOne',
                'relation' => 'productCategory',
                'column' => 'name'
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],

        ],


    ];

    protected $languageFile = 'products::products';

    protected $relationTabs = [
        'price_list' => [
            'icon' => 'book',
            'permissions' => [
                'browse' => 'products.browse',
                'update' => 'products.update',
                'create' => 'products.create'
            ],
            'datatable' => [
                'datatable' => PriceListDatatable::class
            ],
            'route' => [
                'linked' => 'products.price_list.linked',
                'create' => 'products.price_list.create',
                'select' => 'products.price_list.selection',
                'bind_selected' => 'products.price_list.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'products::price_list.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'product_id',
                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'products::price_list.module'
            ],
        ],
    ];


    protected $routes = [
        'index' => 'products.products.index',
        'create' => 'products.products.create',
        'show' => 'products.products.show',
        'edit' => 'products.products.edit',
        'store' => 'products.products.store',
        'destroy' => 'products.products.destroy',
        'update' => 'products.products.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }


    /**
     *
     * @param $request
     * @param $entity
     * @param $input
     */
    public function beforeUpdate($request, &$entity, &$input)
    {
        /**
         * If there is no profile_image in input. Remove this field and don't process.
         * Why? If there was image before update it will be removed because all variables from input are processed even null values.
         */
        if (!isset($input['image_path'])) {
            unset($input['image_path']);
        }
    }

    /**
     * After entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function afterStore($request, &$entity)
    {

        if (config('bap.demo')) {
            return false;
        }


        /**
         * Product upload
         */
        $picturePath = ProductHelper::IMAGE_PATH;

        //Product Image
        $profilePicture = $request->file('image_path');

        if ($profilePicture != null) {

            $image = 'product_image_' . $entity->id . '_.' . $profilePicture->getClientOriginalExtension();

            $uploadSuccess = $profilePicture->move($picturePath, $image);

            if ($uploadSuccess) {

                $entity = $this->getRepository()->update([
                    'image_path' => $picturePath . $image
                ], $entity->id);

            }
        }
    }

    /**
     * After entity update
     * @param $request
     * @param $entity
     */
    public function afterUpdate($request, &$entity)
    {
        /**
         * Avatar upload
         */
        $this->afterStore($request, $entity);

    }

}
