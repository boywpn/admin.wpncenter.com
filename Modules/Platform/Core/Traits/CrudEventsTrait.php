<?php
/**
 * Created by PhpStorm.
 * User: laravel-bap.com
 * Date: 18.11.18
 * Time: 10:48
 */

namespace Modules\Platform\Core\Traits;

/**
 * Helper function that are injected into CRUD functions
 * Trait CrudEventsTrait
 * @package Modules\Platform\Core\Traits
 */
trait CrudEventsTrait
{

    /**
     * Function invoked before update of entity
     * @param $request
     * @param $entity
     * @param $input
     */
    public function beforeUpdate($request, &$entity, &$input)
    {

    }

    /**
     * Function invoked after update of entity
     * @param $request
     * @param $entity
     *
     */
    public function afterUpdate($request, &$entity)
    {

    }

    /**
     * Function invoked before entity store
     * @param $request
     */
    public function beforeStore($request)
    {

    }

    /**
     * Function invoked before entity store
     * @param $request
     */
    public function beforeStoreInput($request, &$input)
    {

    }

    /**
     * Function invoked after entity store
     * @param $request
     * @param $entity
     */
    public function afterStore($request, &$entity)
    {

    }

    /**
     * Function invoked before entity destroy
     * @param $request
     * @param $entity
     */
    public function beforeDestroy($request, &$entity)
    {

    }

    /**
     * Function invoked after entity destroy
     * @param $request
     *
     */
    public function afterDestroy($request)
    {

    }

    /**
     * Function invoked before edit form show
     * @param $request
     * @param $entity
     */
    public function beforeEdit($request, &$entity)
    {

    }

    /**
     * Function invoked before entity show
     * @param $request
     * @param $entity
     */
    public function beforeShow($request, &$entity)
    {

    }

    /**
     * Function invoked before create form show
     * @param $request
     */
    public function beforeCreate($request)
    {

    }

    /**
     * Function invoked before index
     * @param $request
     * @param $datatable
     * @param $additionalVariables
     */
    public function beforeIndex($request, &$datatable, &$additionalVariables)
    {

    }


}