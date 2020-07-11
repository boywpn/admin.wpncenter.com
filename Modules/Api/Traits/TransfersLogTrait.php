<?php

namespace Modules\Api\Traits;

use App\Models\TransfersLog;
use Illuminate\Http\Request;
use Modules\Platform\Core\Repositories\GenericRepository;

/**
 * Respond function
 *
 * Trait RespondTrait
 * @package Modules\Api\Traits
 */
trait TransfersLogTrait
{

    public $arrLog;

    public function saveLog($data)
    {


        $this->arrLog['request_data'] = json_encode($data);
        $this->arrLog['local_ip'] = $data['local_ip'];
        $this->arrLog['primary_ip'] = get_client_ip();
        $this->arrLog['created_from'] = (isset($data['from'])) ? strtolower($data['from']) : 'api';

        $repository = \App::make(GenericRepository::class);
        $log = $repository->createEntity($this->arrLog, \App::make(TransfersLog::class));

        return $log;

    }

}