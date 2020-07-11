<?php

namespace App\Http\Controllers\Statements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\Banks\Entities\BanksStatements;
use Modules\Platform\Core\Repositories\GenericRepository;

class StatementsController extends Controller
{

    /**
     * Module Repository
     * @var
     */
    protected $repository = GenericRepository::class;

    /**
     * Create instance of module repository or use generic repository
     *
     * @return mixed
     */
    protected function getRepository()
    {
        $repository = \App::make($this->repository);
        return $repository;
    }

    public function error($msg)
    {
        $json = array(
            'status' => false,
            'data' => array(),
            'message' => $msg,
        );
        $output =  json_encode($json,JSON_UNESCAPED_UNICODE);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        return $output;
    }

    public function success($msg, $data)
    {
        $json = array(
            'status' => true,
            'data' => $data,
            'message' => $msg,
        );
        $output =  json_encode($json,JSON_UNESCAPED_UNICODE);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        return $output;
    }

    public function push($number, Request $request)
    {

        $data = $request->all();

        $dir = dirname(__FILE__);
        $push_file = $dir . '/push_'.$number.'.txt';

        file_put_contents($push_file, json_encode($data, JSON_UNESCAPED_UNICODE));

        // check bank number exist
        $banks = Banks::where('number', $number)->select('id')->first();
        if(!$banks){
            return $this->error('ไม่มีหมายเลขบัญชีนี้ในระบบ');
        }

        foreach ($data as $key => $value) {
            $arrData = array(
                'bank_id' => $banks->id,
                'bank_number' => $number,
                'datetime' => $value['state_date'],
                'chanel' => $value['state_chanel'],
                'type' => $value['state_tran_type'],
                'withdraw' => $value['state_withdrawal'],
                'deposit' => $value['state_deposit'],
                'account_no' => $value['state_account_no'],
                'hashkey' => $value['state_hashkey'],
                'detail' => $value['state_detail']
            );

            $repository = $this->getRepository();

            $statement = BanksStatements::where('hashkey', $value['state_hashkey'])->first();
            if(!$statement){
                 $repository->createEntity($arrData, \App::make(BanksStatements::class)); // Save
            }
        }

    }

}
