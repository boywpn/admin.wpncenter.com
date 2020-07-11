<?php

namespace Modules\Platform\Core\Repositories;

use Modules\Orders\Entities\Order;
use Modules\Platform\Core\Helper\CrudHelper;
use Modules\Platform\Core\Helper\DatabaseHelper;
use Prettus\Repository\Eloquent\BaseRepository;
use Stringy\Stringy;

/**
 * Class PlatformRepository
 * @package Modules\Platform\Core\Repositories
 */
abstract class PlatformRepository extends BaseRepository
{

    /**
     * Find by Entity Id (null if not exist)
     * @param $id
     * @return null
     */
    public function findWithoutFail($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Find by entity id and cleanup
     * @param $id
     * @return null
     */
    public function findWithoutFailForCopy($id)
    {

        $entity = $this->findWithoutFail($id);
        if (!empty($entity)) {

            try {
                $rows = $entity->rows()->get();

                foreach ($rows as $row) {
                    $row->id = null;
                }

                $entity->rows = $rows;
            } catch (\Exception $e) {

            }

            return $entity;

        }
        return null;
    }


    /**
     * Create entity from attributes - method used in SettingsCrudController
     *
     * @param array $attributes
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(array $attributes)
    {
        foreach ($attributes as &$attr){

            if($attr == '' && $attr != 0){ // skip 0 check
                $attr = null;
            }
        }

        return parent::create($attributes);
    }

    /**
     * Create Entity - method used in ModuleCrudController
     *
     * @param $attributes
     * @param $entity
     * @return mixed
     */
    public function createEntity($attributes, $entity)
    {


        unset($attributes['_method']);
        unset($attributes['_token']);
        unset($attributes['owned_by']);
        unset($attributes['entityCreateMode']);
        unset($attributes['relationType']);
        unset($attributes['relatedField']);
        unset($attributes['relatedEntityId']);
        unset($attributes['relatedEntity']);

        foreach ($attributes as $field => $value) {
            if (method_exists($entity, $field)) { // skip relations
            } else {
                if ($value == '') {
                    $value = null;
                }
                $entity->setAttribute($field, $value);
            }
        }

        $entity->save();

        foreach ($attributes as $field => $value) {
            if (method_exists($entity, $field)) {
                $e = CrudHelper::learnMethodType($entity, $field);
                if ($e == CrudHelper::BELONGS_TO) {
                    $entity->{$field}()->associate($value);
                }
                if ($e == CrudHelper::BELONGS_TO_MANY) {
                    $entity->{$field}()->sync($value);
                }
            }
        }

        // save relations
        $entity->save();

        return $entity;
    }

    /**
     * Update for crud
     * Need to use this function to set owner
     * @param $attributes
     * @param $entity
     * @return mixed
     */
    public function updateEntity($attributes, $entity)
    {
        unset($attributes['_method']);
        unset($attributes['_token']);
        unset($attributes['owned_by']);

        foreach ($attributes as $field => $value) {
            if (method_exists($entity, $field)) {

                $e = CrudHelper::learnMethodType($entity, $field);
                if ($e == CrudHelper::BELONGS_TO) {
                    $entity->{$field}()->associate($value);
                }
                if ($e == CrudHelper::BELONGS_TO_MANY) {
                    $entity->{$field}()->sync($value);
                }
            } else {
                if ($value == '') {
                    $value = null;
                }
                $entity->setAttribute($field, $value);
            }
        }

        $entity->save();

        return $entity;
    }

    /**
     * Next record
     *
     * @param $entity
     * @return mixed
     */
    public function next($entity)
    {
        $nextId = $this->model::where('id', '>', $entity->id)->orderBy('id','asc')->limit(1)->first();

        return $nextId;
    }

    /**
     * Prev record
     *
     * @param $entity
     * @return mixed
     */
    public function prev($entity)
    {
        $prevId = $this->model::where('id', '<', $entity->id)->orderBy('id','desc')->limit(1)->first();

        return $prevId;
    }
}
