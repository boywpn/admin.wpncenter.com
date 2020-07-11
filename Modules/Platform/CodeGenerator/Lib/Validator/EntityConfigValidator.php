<?php

namespace Modules\Platform\CodeGenerator\Lib\Validator;

/**
 * Entity Config Validator
 *
 * Class EntityConfigValidator
 * @package Modules\Platform\CodeGenerator\Lib\Validator
 */
class EntityConfigValidator
{
    const VALID_FIELD_TYPES = ['string', 'integer', 'text', 'ownedBy','manyToOne','date','email','decimal','datetime','boolean'];

    const VALID_ENTITY_TYPES = ['main','settings'];


    /**
     * @param $entity
     * @param $value
     * @throws \Exception
     */
    private function checkEntityProperyExist($entity, $value)
    {
        if (!isset($entity[$value])) {
            throw  new \Exception("Configuration error: 'entity.{$value}' node not found ");
        }
    }

    /**
     * @param $config
     * @throws \Exception
     */
    public function validate($config)
    {
        $count = count($config['setup']['entity']);

        if ($count < 1) {
            throw  new \Exception("Configuration error: 'setup.entity.size' should have at least 1 entity ");
        }

        foreach ($config['setup']['entity'] as $key => $entity) {
            $this->checkEntityProperyExist($entity, 'table');
            $this->checkEntityProperyExist($entity, 'type');

            if (in_array($entity['type'], self::VALID_ENTITY_TYPES)) {
            }

            $this->checkEntityProperyExist($entity, 'properties');



            $count = count($entity['properties']);

            if ($count < 1) {
                throw  new \Exception("Configuration error: 'setup.entity.{$key}' should have filled properties ");
            }

            $properties = $entity['properties'];

            foreach ($properties as $panel => $panelProperties) {
                foreach ($panelProperties as $prop => $value) {
                    if (!isset($value['type'])) {
                        throw  new \Exception("Configuration error: 'setup.entity.{$key}.{$panel}.{$prop}' should have 'type' node ");
                    }

                    $type = $value['type'];

                    if (!in_array($type, self::VALID_FIELD_TYPES)) {
                        throw  new \Exception("Configuration error: 'setup.entity.{$key}.{$panel}.{$prop}' invalid type '{$type}' ");
                    }

                    if ($type == 'manyToOne') {
                        if (!isset($value['relation'])) {
                            throw  new \Exception("Configuration error: 'setup.entity.{$key}.{$panel}.{$prop}' should have 'relation' node ");
                        }
                        if (!isset($value['display_column'])) {
                            throw  new \Exception("Configuration error: 'setup.entity.{$key}.{$panel}.{$prop}' should have 'display_column' node ");
                        }
                    }
                }
            }
        }
    }
}
