<?php

namespace Modules\Api\Traits;

/**
 * Respond function
 *
 * Trait RespondTrait
 * @package Modules\Api\Traits
 */
trait RespondTrait
{

    /**
     * Respond with api result
     *
     * @param bool $status
     * @param array $data
     * @param array $errors
     * @param array $msg
     * @return array
     */
    protected function respond($status = false, $data = [], $errors = [], $msg = [])
    {
        return ['status' => $status, 'data' => $data, 'message' => $msg, 'errors' => $errors];
    }

    protected function respondWithCode($status = false, $codeid, $data = [], $msg = [], $errors = [])
    {
        return [
            'status' => $status,
            'codeid' => $codeid,
            'message' => $msg,
            'data' => $data,
            'errors' => $errors
        ];
    }

}