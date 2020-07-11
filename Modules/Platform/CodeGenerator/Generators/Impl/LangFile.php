<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

/**
 * Lang file generator
 *
 * Class LangFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class LangFile implements GeneratorInterface
{

    /**
     * @param $config
     * @return bool|int
     */
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];

        $moduleNameLowerCase = strtolower($moduleName);

        $stubGenerator = new StubGenerator();
        $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/lang.stub');
        $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Resources/lang/en/$moduleNameLowerCase.php");

        return $stubGenerator->save([
            ':MODULE_NAME:' => $moduleName,
        ]);
    }
}
