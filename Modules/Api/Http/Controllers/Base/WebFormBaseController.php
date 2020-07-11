<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 15.11.18
 * Time: 10:50
 */

namespace Modules\Api\Http\Controllers\Base;

/**
 * Simple webform create controller
 *
 * Allows to skip jwt authentication and use simple post
 *
 * Class WebFormBaseController
 * @package Modules\Api\Http\Controllers\Base
 */
class WebFormBaseController extends CrudApiController
{

    protected $skipAuth = true;


}
