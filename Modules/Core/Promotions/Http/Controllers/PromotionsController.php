<?php

namespace Modules\Core\Promotions\Http\Controllers;

use Modules\Core\Promotions\Datatables\PromotionsDatatable;
use Modules\Core\Promotions\Entities\Promotions;
use Modules\Core\Promotions\Http\Forms\PromotionsForm;
use Modules\Core\Promotions\Http\Requests\PromotionsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class PromotionsController extends ModuleCrudController
{
    protected $datatable = PromotionsDatatable::class;
    protected $formClass = PromotionsForm::class;
    protected $storeRequest = PromotionsRequest::class;
    protected $updateRequest = PromotionsRequest::class;
    protected $entityClass = Promotions::class;

    protected $moduleName = 'CorePromotions';

    protected $permissions = [
        'browse' => 'core.promotions.browse',
        'create' => 'core.promotions.create',
        'update' => 'core.promotions.update',
        'destroy' => 'core.promotions.destroy'
    ];

    protected $showFields = [
        'information' => [
            'name' => ['type' => 'text'],
            'partner_id' => ['type' => 'manyToOne', 'relation' => 'promotionsPartner', 'column' => 'name'],

            'percent' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'amount' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'max_value' => ['type' => 'text', 'col-class' => 'col-lg-4'],

            'min_deposit' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'max_deposit' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'expired_at' => ['type' => 'datetime', 'col-class' => 'col-lg-4'],

            'is_front' => ['type' => 'boolean', 'col-class' => 'col-lg-4'],
            'is_active' => ['type' => 'boolean', 'col-class' => 'col-lg-4'],
            'have_ref' => ['type' => 'boolean', 'col-class' => 'col-lg-4'],
        ],

        'front' => [
            'title' => ['type' => 'text', 'col-class' => 'col-lg-12'],
            'description' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ],

        'notes' => [
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'core/promotions::promotions';

    protected $routes = [
        'index' => 'core.promotions.index',
        'create' => 'core.promotions.create',
        'show' => 'core.promotions.show',
        'edit' => 'core.promotions.edit',
        'store' => 'core.promotions.store',
        'destroy' => 'core.promotions.destroy',
        'update' => 'core.promotions.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
