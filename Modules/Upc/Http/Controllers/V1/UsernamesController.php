<?php

namespace Modules\Upc\Http\Controllers\V1;

use app\cryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\User\Entities\User;
use Modules\Upc\Http\Controllers\UpcController;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class UsernamesController extends UpcController
{

    protected $entityClass = Username::class;

    protected $apiUrl;

    public function getTransfer(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'username' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $username = Username::where('username', $input['username'])
            ->with(['usernameBoard' => function(){

            }, 'usernameBoard.boardsGame' => function(){

            }])
            ->where('member_id', $token['data']['member_id'])
            ->first();

        if(!$username){
            return $this->errorMsg(108);
        }

        $username = $username->toArray();

        if(empty($username['username_board']['boards_game']['api_url'])){
            $transfer = $this->apiTransfer($username);

            $credit = 0;
            $outstanding = 0;
            $betcredit = 0;

            if($transfer['responseStatus']['code'] != 200){

                if($username['username_board']['boards_game']['code'] == 'ibc' && $transfer['responseStatus']['messageDetails'] == 'MEMBER_NOT_FOUND'){

                }else{
                    return $this->error(203, $transfer['responseStatus']['messageDetails']);
                }

            }else{

                $credit = $transfer['responseDetails']['banlance'];
                $outstanding = $transfer['responseDetails']['playing'];
                $betcredit = $transfer['responseDetails']['netbanlance'];

            }

            $arrRes = [
                'username' => $input['username'],
                'credit' => $credit,
                'outstanding' => $outstanding,
                'betcredit' => $betcredit,
            ];
        }else{
            return $this->error(202);
        }


        return $this->success(0, $arrRes);


    }

    public function apiTransfer($data, $type = 'detail'){

        $arrTrans = [
            'action' => 'transfer',
            'custid' => $data['username'],
            'type' => $type,
            'amount' => 0,
            'local_ip' => get_client_ip(),
            'auto' => true,
        ];

        $trans = new TransferApiController();
        $response = $trans->transfer($arrTrans);

        return $response;

    }

    public function trnfTransfer($data){

        $arrData = [
            'username' => 'testapi',
            'apikey' => 'wBNVoFFrYUzEzuJgfEMj/HvBTal5KafIwBn14dIIcUo=',
            'staffid' => '0',
            'from' => 'member'
        ];

        return $data_post = array_merge($arrData, $data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $data = curl_exec($ch);

        $json = json_decode($data, true);

        return $json;

    }

}