<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * Request generator
 *
 * Class RequestFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class RequestFile implements GeneratorInterface
{
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $rules = $this->requestRulesBody($config);

        $stubGenerator = new StubGenerator();
        $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/request.stub');
        $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Http/Requests/${moduleName}Request.php");

        return $stubGenerator->save([
            ':MODULE_NAME:' => $moduleName,
            ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
            ':REQUEST_RULES_BODY:' => $rules
        ]);
    }

    /**
     * Generate Request with rules base on config file
     *
     * @param $config
     * @return string
     */
    private function requestRulesBody($config)
    {
        $result = '';

        foreach ($config['setup']['entity'] as $entity) {
            if ($entity['type'] = 'main') {
                foreach ($entity['properties'] as $section) {
                    foreach ($section as $field => $props) {
                        if (isset($props['rules']) && $props['rules'] != '') {
                            $result .= "'$field' => '${props['rules']}' ,";
                        }
                    }
                }
            }
        }

        return $result;
    }
}
