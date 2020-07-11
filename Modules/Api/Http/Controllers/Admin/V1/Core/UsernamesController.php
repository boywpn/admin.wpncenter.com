<?php

namespace Modules\Api\Http\Controllers\Admin\V1\Core;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Admin\AdminController;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class UsernamesController extends AdminController
{

    public function getUsernames($request){

        $per_page = $request['per_page'];

//        $validator = Validator::make($request, [
//            'username' => 'required'
//        ]);
//
//        if($validator->fails()){
//            return $this->error(2001, $validator->errors());
//        }

        $member = (isset($request['member'])) ? $request['member'] : null;

        $partners = Username::where('core_username.company_id', Auth::user()->company_id)
            ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
            ->when(!empty($member), function($query) use ($member){
                $query->where('member_members.id', $member);
            })
            ->with(['usernameBoard' => function($query){
                $query->select(
                    'id',
                    'name',
                    'game_id',
                    'member_prefix'
                );
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select(
                    'id',
                    'name',
                    'code'
                );
            }])
            ->select(
                'core_username.id',
                'core_username.member_id',
                'core_username.board_id',
                'core_username.code',
                'core_username.token',
                'core_username.username',
                'core_username.is_active',
                'core_username.is_created',
                'core_username.is_created_at',
                'core_username.notes',
                'core_username.created_at',
                'core_username.updated_at',
                'core_username.member_at',
                'core_username.company_id'
            )
            ->orderBy('id', 'asc')
            ->paginate($per_page);

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