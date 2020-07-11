<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 02.10.18
 * Time: 13:29
 */

namespace Modules\Platform\Core\Helper;


/**
 * Csv Importer
 *
 *
 * Class CsvImporter
 * @package Modules\Platform\Core\Helper
 */
class CsvImporter
{

    /**
     * Relations
     * @var
     */
    private $relations;

    /**
     * Entity class
     * @var
     */
    private $entity;

    /**
     * Generic Repository
     * @var
     */
    private $repo;

    /**
     * Fields
     * @var
     */
    private $fields;

    /**
     * @param mixed $relations
     */
    public function setRelations($relations): void
    {
        $this->relations = $relations;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @param mixed $repo
     */
    public function setRepo($repo): void
    {
        $this->repo = $repo;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    /**
     * Import row from csv and create entity
     *
     * @param $attributes
     * @return mixed
     */
    public function createEntity($attributes){

        $finalAttributes = [];

        foreach ($attributes as $key =>  $attribute) {

            if ($key != '') {

                $result = CrudHelper::array_search_key($key, $this->fields);

                if (isset($result['type']) && $result['type'] == 'manyToOne') {

                    if ($attribute != '') {

                        foreach ($this->relations as $relationKey => $relation) {
                            if ($relationKey == $result['relation']) {

                                $dictionary = \App::make($relation['model'])->where($result['column'], '=', strtolower($attribute))->first();

                                //TODO I can also add here auto create related entries
                                if (!empty($dictionary)) {
                                    $attribute = $dictionary->id;
                                } else {
                                    $attribute = null;
                                }
                            }
                        }
                    }

                }

                $finalAttributes[$key] = $attribute;
            }

        }

        return $this->repo->create($finalAttributes,$this->entity);

    }

}
