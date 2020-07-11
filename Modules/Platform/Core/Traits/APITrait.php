<?php

namespace Modules\Platform\Core\Traits;

use Modules\Platform\Core\Entities\Comment;

/**
 * Trait Commentable
 * @package Modules\Platform\Core\Traits
 */
trait APITrait
{

    public function error($msg)
    {
        $json = array(
            'status' => false,
            'message' => $msg,
        );
        $output =  json_encode($json,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        return $output;
    }

    public function errorMsg($msg, $data = null)
    {
        $output = array(
            'status' => false,
            'message' => $msg,
            'data' => $data,
        );
        return $output;
    }

    public function success($msg, $data)
    {
        $json = array(
            'status' => true,
            'data' => $data,
            'message' => $msg,
        );
        $output =  json_encode($json,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        return $output;
    }
    public function successMsg($msg, $data)
    {
        $json = array(
            'status' => true,
            'data' => $data,
            'message' => $msg,
        );
        return $json;
    }

}
