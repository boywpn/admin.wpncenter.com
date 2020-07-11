<?php

namespace Modules\Core\Owners\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Owners\Datatables\OwnersDatatable;
use Modules\Core\Owners\Entities\Owners;
use Modules\Core\Owners\Http\Forms\OwnersForm;
use Modules\Core\Owners\Http\Requests\OwnersRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class OwnersController extends ModuleCrudController
{
    protected $datatable = OwnersDatatable::class;
    protected $formClass = OwnersForm::class;
    protected $storeRequest = OwnersRequest::class;
    protected $updateRequest = OwnersRequest::class;
    protected $entityClass = Owners::class;

    protected $moduleName = 'CoreOwners';

    protected $permissions = [
        'browse' => 'owners.browse',
        'create' => 'owners.create',
        'update' => 'owners.update',
        'destroy' => 'owners.destroy'
    ];

    protected $showFields = [

        'information' => [
            'name' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'code' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'phone' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'is_active' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4'
            ],
            'is_suspend' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4'
            ],
            'api_token' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ]
        ],
        'note' => [
            'note' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],
        ],
    ];

    protected $languageFile = 'core/owners::owners';

    protected $routes = [
        'index' => 'core.owners.index',
        'create' => 'core.owners.create',
        'show' => 'core.owners.show',
        'edit' => 'core.owners.edit',
        'store' => 'core.owners.store',
        'destroy' => 'core.owners.destroy',
        'update' => 'core.owners.update',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function genToken($identifier)
    {
        $owner = Owners::find($identifier);

        $data = [
            'id' => $owner->id,
            'code' => $owner->code,
            'company_id' => $owner->company_id
        ];

        $token = encrypter('encrypt', json_encode($data, JSON_UNESCAPED_UNICODE), config('app.key_api'), config('app.salt_api'));
        $encode_token = encrypter('decrypt', $token, config('app.key_api'), config('app.salt_api'));
        $json_token = json_decode($encode_token);

        return compact('user', 'token', 'encode_token', 'json_token');
    }
}
