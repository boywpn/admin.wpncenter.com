<?php

namespace Modules\Platform\CodeGenerator\Generators;

/**
 * Interface GeneratorInterface
 * @package Modules\Platform\CodeGenerator\Generators
 */
interface GeneratorInterface
{

    /**
     * Generate Files base on config
     * @param $config
     * @return mixed
     */
    public function generate($config);
}
