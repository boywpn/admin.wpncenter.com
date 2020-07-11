<?php

namespace Modules\Api\Http\Controllers\Upc\V1;

use app\cryptor;
use App\Models\Trnf\TmpBankAuto;
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
class BanksController extends UpcController
{

    public function statement(Request $request){

        $input = $request->input();

        $validator = Validator::make($input, [
            'number' => 'required',
            'datetime' => 'required'
        ]);

        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        $bank_number = $input['number'];
        $datetime = $input['datetime'];
        $s_date = changeDate('-3 minutes', $datetime);
        $e_date = changeDate('3 minutes', $datetime);

        $statement = TmpBankAuto::where('bank_number', $bank_number)
            ->where('status', '!=', 2)
            ->whereBetween('state_date', [$s_date, $e_date])
            ->get();

        if(count($statement) > 0){

            $res = $statement->toArray();
            return $this->success(0, $res);

        }

        return $this->success(0, []);

    }

}