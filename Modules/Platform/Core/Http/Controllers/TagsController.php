<?php
/**
 * Created by PhpStorm.
 * User: laravel-bap.com
 * Date: 18.11.18
 * Time: 13:58
 */

namespace Modules\Platform\Core\Http\Controllers;


/**
 * Class TagsController
 * @package Modules\Platform\Core\Http\Controllers
 */
class TagsController extends AppBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * I leave tags implementation to you
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tags()
    {
        return response()->json([

        ]);

    }

}