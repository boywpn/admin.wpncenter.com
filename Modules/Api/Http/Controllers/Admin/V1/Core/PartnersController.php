<?php

namespace Modules\Api\Http\Controllers\Admin\V1\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Admin\AdminController;
use Modules\Core\Partners\Entities\Partners;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class PartnersController extends AdminController
{

    protected $entityClass = Partners::class;

    public function lists($request){

        $per_page = $request['per_page'];

        $partners = $this->entityClass::where('owner_id', Auth::user()->owner_id)->paginate($per_page);

        return $this->success(0, $partners);

    }

    public function editPartner($request){

        $repository = $this->getRepository(Partners::class);

        $validator = Validator::make($request, [
            'id' => 'required',
            'name' => 'required|string|max:20',
            'website' => 'required|string|url|max:100',
            'is_active' => 'required'
        ]);

        if($validator->fails()){
            return $this->error(2001, $validator->errors());
        }

        $entity = $repository->findWithoutFail($request['id']);
        if(!$entity){
            return $this->error(2002);
        }

        $entity = $repository->updateEntity($request, $entity);

        return $this->success(0);

    }

}