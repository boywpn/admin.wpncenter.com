<?php
namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\AppController;
use App\Models\Old\Agents;
use App\Models\Trnf\Banks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Traits\RespondTrait;
use Modules\Wallet\Jobs\Entities\Jobs;

class AppWalletController extends AppController
{

    use RespondTrait;

    function test(){

        return Banks::find(1);

    }

    public function callback(Request $request){

        /**
         * Type
         * 1. From Wallet
         * 2. From Order
        */

        $input = $request->post();
        $validator = Validator::make($input, [
            'type' => 'required',
            'id' => 'required',
            'wallet_id' => 'required',
            'data' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($this->respond(false, [], $validator->errors(), 'Error Requirement'), 400);
        }

        // Update status wallet
        $this->entityClass = Jobs::class;
        $repository = $this->getRepository();
        $entity = $repository->findWithoutFail($input['wallet_id']);

        if(!$entity){
            return response()->json($this->respond(false, [], [], 'ไม่มีข้อมูล Wallet ในระบบ'), 400);
        }

        if(!empty($entity['callback_at'])){
            return response()->json($this->respond(false, [], [], 'ไม่สามารถทำรายการซ้ำได้'), 400);
        }

        $response = $input['data'];

        if($response['codeid'] == 0){
            if($input['type'] == 1) {
                $repository->updateEntity(['status_id' => 3, 'transaction_id' => $input['id'], 'callback_at' => date('Y-m-d H:i:s'), 'callback_result' => json_encode($response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)], $entity);
            }
        }else{
            if($input['type'] == 1) {
                $repository->updateEntity(['callback_at' => date('Y-m-d H:i:s'), 'callback_result' => json_encode($response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)], $entity);
            }
        }

        return response()->json($this->respond(true, $entity, [], 'อัฟเดตข้อมูลเรียบร้อย'), 200);

    }

}