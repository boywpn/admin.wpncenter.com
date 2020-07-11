<?php
/**
 * Created by PhpStorm.
 * User: laravel-bap.com
 * Date: 08.09.18
 * Time: 18:49
 */

namespace Modules\Api\Http\Controllers\Base;


use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Api\Service\Saas\SaasService;

/**
 * Saas API Base Controller
 *
 * Class SaasApiBaseController
 * @package Modules\Api\Http\Controllers
 */
class SaasApiBaseController extends Controller
{


    protected $service;

    public function __construct(SaasService $service)
    {
        $this->service = $service;
    }

    /**
     * Validate Secret
     * @param $request
     */
    protected function validateSecret($request)
    {

        $result = $this->service->validateSecret($request->get('api_secret'));

        if ($result['status'] == 'error') {
            throw new HttpResponseException(response($result));
        }

    }

    protected function respondWithSuccess($message,$data = [])
    {
        throw  new HttpResponseException(response()->json([
            'status' => 'success',
            'message' => $message,
            'data'    => $data
        ]));
    }

    protected function respondWithError($message,$data = [])
    {
        throw  new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $message,
            'data'    => $data
        ]));
    }

}