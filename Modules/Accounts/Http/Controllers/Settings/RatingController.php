<?php

namespace Modules\Accounts\Http\Controllers\Settings;

use Modules\Accounts\Datatables\Settings\AccountRatingDatatable;
use Modules\Accounts\Entities\AccountRating;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class RatingController extends ModuleCrudController
{
    protected $datatable = AccountRatingDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AccountRating::class;

    protected $moduleName = 'accounts';

    protected $permissions = [
        'browse' => 'accounts.settings',
        'create' => 'accounts.settings',
        'update' => 'accounts.settings',
        'destroy' => 'accounts.settings'
    ];

    protected $settingsBackRoute = 'accounts.accounts.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'accounts::accounts.rating';

    protected $routes = [
        'index' => 'accounts.rating.index',
        'create' => 'accounts.rating.create',
        'show' => 'accounts.rating.show',
        'edit' => 'accounts.rating.edit',
        'store' => 'accounts.rating.store',
        'destroy' => 'accounts.rating.destroy',
        'update' => 'accounts.rating.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
