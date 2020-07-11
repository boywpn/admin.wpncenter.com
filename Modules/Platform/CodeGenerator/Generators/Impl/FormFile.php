<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;

class FormFile implements GeneratorInterface
{
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $singularName = $config['setup']['singular_name'];

        foreach ($config['setup']['entity'] as $key => $entity) {
            if ($entity['type'] == 'main') {
                $stubGenerator = new StubGenerator();
                $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/form.stub');

                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Http/Forms/${singularName}Form.php");

                $formFields = \View::make('codegenerator::form.form_fields');
                $formFields->with('config', $config);

                $formUse = \View::make('codegenerator::form.form_use');
                $formUse->with('config', $config);

                $stubGenerator->save([
                    ':SINGULAR_NAME:' =>$singularName,
                    ':MODULE_NAME:' => $moduleName,
                    ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
                    ':FORM_FIELDS:' => $formFields,
                    ':FORM_USE:' => $formUse
                ]);
            }
        }

        return true;
    }
}
