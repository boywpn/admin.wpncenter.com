<?php

namespace Modules\Core\Banks\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Modules\Core\Banks\Datatables\BanksDatatable;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\Banks\Http\Forms\BanksForm;
use Modules\Core\Banks\Http\Requests\BanksRequest;
use Modules\Core\Banks\Http\Requests\BanksUpdateRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class BanksController extends ModuleCrudController
{
    protected $datatable = BanksDatatable::class;
    protected $formClass = BanksForm::class;
    protected $storeRequest = BanksRequest::class;
    protected $updateRequest = BanksUpdateRequest::class;
    protected $entityClass = Banks::class;

    protected $moduleName = 'CoreBanks';
    protected $moduleAlias = 'core/banks';

    protected $cssFiles = [

    ];

    protected $jsFiles = [

    ];

    protected $permissions = [
        'browse' => 'core.banks.browse',
        'create' => 'core.banks.create',
        'update' => 'core.banks.update',
        'destroy' => 'core.banks.destroy'
    ];

    protected $showFields = [
        'login' => [
            'username' => [
                'type' => 'text',
                'col-class' => 'col-lg-3'
            ],
            'password' => [
                'type' => 'none',
                'col-class' => 'col-lg-3'
            ],
        ],

        'information' => [
            'bank_id' => ['type' => 'manyToOne', 'relation' => 'banks', 'column' => 'name'],
            'account' => ['type' => 'text'],
            'number' => ['type' => 'text'],
            'phone' => ['type' => 'text'],
            'is_active' => ['type' => 'boolean'],
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'core/banks::banks';

    protected $routes = [
        'index' => 'core.banks.index',
        'create' => 'core.banks.create',
        'show' => 'core.banks.show',
        'edit' => 'core.banks.edit',
        'store' => 'core.banks.store',
        'destroy' => 'core.banks.destroy',
        'update' => 'core.banks.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Before entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function beforeStore($request)
    {

        $password = Crypt::encryptString($request->get('password'));

        $data = [
            'replace' => [
                'password' => $password
            ],
        ];

        return $data;

    }

    /**
     *
     * @param $request
     * @param $entity
     * @param $input
     */
    public function beforeUpdate($request, &$entity, &$input)
    {
        if (isset($input['password'])) {
            if($entity->password != $input['password']) {
                $input['password'] = Crypt::encryptString($request->get('password'));
            }else{
                unset($input['password']);
            }
        }else{
            unset($input['password']);
        }
    }

}