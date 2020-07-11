<?php

namespace Modules\Api\Http\Controllers\Upc\V1;

use app\cryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Upc\UpcController;
use Modules\Member\Members\Entities\Members;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class MembersController extends UpcController
{

    public function login(Request $request)
    {

        $input = $request->only(['username', 'password', 'domain']);
        $data = [];

        if (!$request->has('username')) {
            return $this->error(101);
        }
        if (!$request->has('password')) {
            return $this->error(101);
        }
        if (!$request->has('domain')) {
            return $this->error(101);
        }

        $on_newsystem = 0;
        $ck_new = 0;
        // Check exist in new system
        $member = \App\Models\Old\Members::where('username', $input['username'])
            ->where('password', $input['password'])
            ->where('domain', $input['domain'])
            ->select('A_I','new_id','id','username','status','on_newsystem','on_newsystem_at')
            ->first();

        if(!$member){
            $ck_new = 1;
        }

        // Gen key_change when login
        $login_key = rand12(12);
        $data['secret_key'] = $login_key;

        if($ck_new == 0){
            $on_newsystem = $member['on_newsystem'];
        }

        // Check on new sytem
        if($ck_new == 0 && $on_newsystem == 0){

            $member = $member->toArray();

            // Check exist already some people another system before this system online.
            $member_new = Members::where('old_id', $member['A_I'])
                ->select('id','name','username','old_id','first_update','first_update_at')
                ->first();

            // If exist generate key_change for update username and password for UPC system.
            if($member_new){

                // Update key to member
                Members::where('id', $member_new->id)->update(['key_change' => $login_key]);

                $data['member'] = $member_new->toArray();
                $member_id = $member_new->id;

                // Check first update
                if(empty($member_new->first_update)){
                    $data['first_update'] = false;
                }else{
                    $data['first_update'] = true;
                }

            }
            // If have will be created
            else{

                $data['first_update'] = false;
                $cMember = new \Modules\Member\Members\Http\Controllers\MembersController();

                $member_new = $cMember->addMember($member['A_I']);
                $member_new = json_decode($member_new, true);
                $member_id = $member_new['data']['id'];

                // Update key to member
                Members::where('id', $member_id)->update(['key_change' => $login_key]);

            }

        }

        // If have on new system have to use new username
        else{

            $member = Members::where('username', $input['username'])
                ->select('id','name','username','password','old_id','first_update','first_update_at')
                ->first();

            if(!$member){
                return $this->error(102);
            }

            if (!Hash::check($input['password'], $member->password)) {
                return $this->error(102);
            }

            $member = $member->toArray();
            unset($member['password']);

            $member_id = $member['id'];
            // Update key to member
            Members::where('id', $member_id)->update(['key_change' => $login_key]);

            $data['member'] = $member;

            $data['first_update'] = true;

        }

        \cryptor::setKey(env('CRYPTOR_SALT_FRONT'), env('CRYPTOR_KEY_FRONT'));
        $encrypt = \cryptor::encrypt($member_id."_".$login_key);
        $data['key_change'] = $encrypt;
        $data['key_change_en'] = \cryptor::decrypt($encrypt);

        \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        $encrypt = \cryptor::encrypt($member_id."_".$login_key);
        $data['token_wallet'] = $encrypt;
        $data['token_wallet_en'] = \cryptor::decrypt($encrypt);

        return $this->success(0, $data);
    }

    public function firstUpdate(Request $request)
    {

        $input = $request->only(['token', 'name', 'username', 'old_password', 'password', 'email']);

        if (!$request->has('token')) {
            return $this->error(103);
        }
        if (!$request->has('name')) {
            return $this->error(103);
        }
        if (!$request->has('username')) {
            return $this->error(103);
        }
        if (!$request->has('old_password')) {
            return $this->error(103);
        }
        if (!$request->has('password')) {
            return $this->error(103);
        }

        \cryptor::setKey(env('CRYPTOR_SALT_FRONT'), env('CRYPTOR_KEY_FRONT'));
        $key = \cryptor::decrypt($input['token']);
        $ekey = explode("_", $key);
        if(!isset($ekey[1])){
            return $this->error(104);
        }
        $member_id = $ekey[0];
        $key_change = $ekey[1];

        // Check key and member_id
        $member = Members::where('id', $member_id)
            ->where('key_change', $key_change)
            ->select('id','name','username','old_id','first_update','first_update_at')
            ->first();
        if(!$member){
            return $this->error(104);
        }

        // Check old password
        $o_member = \App\Models\Old\Members::where('A_I', $member->old_id)
            ->select('A_I', 'password')
            ->first();
        if($input['old_password'] != $o_member->password){
            return $this->error(105);
        }

        // check username exist
        $ck_username = Members::where('username', $input['username'])->count();
        if($ck_username > 0){
            return $this->error(106);
        }

        // check email exist
        if(!empty($input['email'])){
            $ck_email = Members::where('email', $input['email'])->count();
            if($ck_email > 0){
                return $this->error(107);
            }
        }

        $datetime = date('Y-m-d H:i:s');
        $arrData = [
            'name' => $input['name'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'email' => $input['email'],
            'first_update' => 1,
            'first_update_at' => $datetime,
        ];

        $repository = $this->getRepository(Members::class);

        $entity = $repository->findWithoutFail($member_id);

        $entity = $repository->updateEntity($arrData, $entity);

        $data = [
            'name' => $entity->name,
            'username' => $entity->username,
            'email' => $entity->email,
            'updated_at' => $datetime
        ];

        // Update old system
        \App\Models\Old\Members::where('A_I', $member->old_id)->update(['on_newsystem' => 1, 'on_newsystem_at' => $datetime]);

        return $this->success(0, $data);

    }

    public function username(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'domain' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $data = [
            'member_id' => $token['data']['member_id'],
            'domain' => $input['domain']
        ];

        $url = 'https://admin.wpnadmin.com/v1/username/';
        $username = $this->getCURL($url, 'POST', $data);

        $json = json_decode($username, true);

        if($json['codeid'] != 200){

            return $this->error(4, $json);

        }

        $arrData['records'] = count($json['data']);
        $arrData['list'] = $json['data'];

        return $this->success(0, $arrData);

    }

    public function confirmAuto(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'id' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $password = encrypter('encrypt', $input['password']);
        $data = [
            'confirmed_auto' => 1,
            'confirmed_password' => $password,
            'confirmed_auto_at' => $this->datetime
        ];

        \App\Models\Old\Username::where('A_I', $input['id'])->update($data);

        return $this->success(0);

    }


}