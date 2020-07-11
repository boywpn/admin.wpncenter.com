<?php

namespace Modules\Platform\CodeGenerator\Lib\Validator;

/**
 * Module Config Validator
 *
 * Class ModuleConfigValidator
 * @package Modules\Platform\CodeGenerator\Lib\Validator
 */
class ModuleConfigValidator
{

    /**
     * Validate basic module configuration
     *
     * @param $config
     * @throws \Exception
     */
    public function validate($config)
    {
        if (!isset($config['setup'])) {
            throw  new \Exception("Configuration error: 'setup' node not found ");
        }

        if (!isset($config['setup']['module_name'])) {
            throw  new \Exception("Configuration error: 'setup.module_name' node not found ");
        }
        if (!isset($config['setup']['entity_private_access'])) {
            throw  new \Exception("Configuration error: 'setup.entity_private_access' node not found ");
        }

        if (!isset($config['setup']['entity'])) {
            throw  new \Exception("Configuration error: 'setup.entity' node not found ");
        }
    }
}
