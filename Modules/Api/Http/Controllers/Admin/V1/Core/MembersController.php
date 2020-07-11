<?php

namespace Modules\Api\Http\Controllers\Admin\V1\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Admin\AdminController;
use Modules\Core\Agents\Entities\Agents;
use Modules\Member\Members\Entities\Members;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class MembersController extends AdminController
{

    public function getMembers($request){

        $per_page = $request['per_page'];

        $agent_id = isset($request['agent_id']) ? $request['agent_id'] : null;

        $members = Members::where('agent_id', $agent->id)
            ->with(['banksMember' => function($query){
                $query->select('id', 'member_id', 'bank_id', 'bank_account', 'bank_number', 'is_main', 'is_active', 'notes');
            }])
            ->select(
                'id',
                'username',
                'name',
                'phone',
                'email',
                'facebook',
                'lineid',
                'status_id',
                'is_active',
                'notes',
                'created_at'
            )
            ->paginate($per_page);

        return $this->success(0, $members);

    }

    public function editMember($request){

        $repository = $this->getRepository(Members::class);

        $validator = Validator::make($request, [
            'username' => 'required',
            'name' => 'required|string|max:20',
            'status_id' => 'required|numeric',
            'is_active' => 'required|numeric'
        ]);

        if($validator->fails()){
            return $this->error(2001, $validator->errors());
        }

        $entity = Members::where('username', $request['username'])->first();
        if(!$entity){
            return $this->error(2002);
        }

        $arrData = [
            'name' => $request['name'],
            'phone' => (!empty($request['phone'])) ? $request['phone'] : null,
            'email' => (!empty($request['email'])) ? $request['email'] : null,
            'facebook' => (!empty($request['facebook'])) ? $request['facebook'] : null,
            'lineid' => (!empty($request['lineid'])) ? $request['lineid'] : null,
            'status_id' => $request['status_id'],
            'is_active' => $request['is_active'],
            'notes' => (!empty($request['notes'])) ? $request['notes'] : null,
        ];

        $entity = $repository->updateEntity($arrData, $entity);

        return $this->success(0);

    }

}