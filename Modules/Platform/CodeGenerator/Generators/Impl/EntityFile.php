<?php

namespace Modules\Platform\CodeGenerator\Generators\Impl;

use Modules\Platform\CodeGenerator\Generators\GeneratorInterface;
use Modules\Platform\CodeGenerator\Lib\FileHelper;
use Modules\Platform\CodeGenerator\Lib\StubGenerator;
use Modules\Platform\CodeGenerator\Lib\StubStringGenerator;

/**
 * Entity generator
 *
 * Class EntityFile
 * @package Modules\Platform\CodeGenerator\Generators\Impl
 */
class EntityFile implements GeneratorInterface
{

    /**
     * @param $config
     * @return bool
     */
    public function generate($config)
    {
        $moduleName = $config['setup']['module_name'];
        $singularName = $config['setup']['singular_name'];

        foreach ($config['setup']['entity'] as $key => $entity) {
            $implementsOwnable = $this->implementsOwnable($entity);
            $hasMorpTrait = $this->hasMorpTrait($entity);
            $logsActivityTrait = $this->logsActivityTrait($entity);
            $commentableUseTrait = $this->commentableUseTrait($entity);
            $hasAttachmentTrait = $this->hasAttachmentTrait($entity);
            $commentsMustBeApproved = $this->commentsMustBeApproved($entity);
            $getRelatedModelAttribute = $this->getRelatedModelAttr($entity);
            $belongToTenantTrait = $this->belongToTenantTrait($entity);
            $logsAttributeProp = $this->logsAttributeProp($entity);
            $fillableProp = $this->fillableProp($entity);
            $manyToOneRelations = $this->manyToOneRelations($entity);
            $dateFieldParse = $this->dateFieldParse($entity);

            $stubGenerator = new StubGenerator();
            $stubGenerator->setSource('Modules/Platform/CodeGenerator/Stubs/entity.stub');

            if ($entity['type'] == 'main') {
                $entityName = $entity['name'];

                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Entities/$entityName.php");
            } else {
                $entityName = $entity['name'];

                $stubGenerator->setTarget(FileHelper::newModuleFilePath($config) . "/Entities/$entityName.php");
            }

            $stubGenerator->save([
                ':MODULE_NAME:' => $moduleName,
                ':MODULE_NAME_LOWERCASE:' => strtolower($moduleName),
                ':ENTITY_NAME:' => $entityName,
                ':ENTITY_TABLE:' => $entity['table'],
                ':ENTITY_NAME_LOWER_CASE:' => strtolower($entityName),
                ':IMPLEMENTS_OWNABLE:' => $implementsOwnable,
                ':HAS_MORPH_USE:' => $hasMorpTrait,
                ':BELONGSTOTENANTS:' => $belongToTenantTrait,
                ':LOGS_ACTIVITY_USE:' => $logsActivityTrait,
                ':COMMENATABLE_USE:' => $commentableUseTrait,
                ':HAS_ATTACHMENT_USE:' => $hasAttachmentTrait,
                ':COMMENTS_MUST_BE_APPROVED:' => $commentsMustBeApproved,
                ':GET_RELATED_MODEL_ATTRIBUTE_PROP:' => $getRelatedModelAttribute,
                ':LOGS_ATTRIBUTES_PROP:' => $logsAttributeProp,
                ':FILLABLE_PROP:' => $fillableProp,
                ':MANY_TO_ONE_RELATIONS:' => $manyToOneRelations,
                ':SINGULAR_NAME:' => $singularName,
                ':DATE_FIELD_PARSE_PROP:' => $dateFieldParse
            ]);
        }

        return true;
    }


    private function dateFieldParse($entity)
    {
        $view = \View::make('codegenerator::entity.date_field_prop');
        $view->with('entity', $entity);

        return $view;
    }

    /**
     * @param $entity
     * @return string
     */
    private function implementsOwnable($entity)
    {
        $result = '';
        if (isset($entity['ownable']) && $entity['ownable']) {
            $result = ' implements Ownable ';
        }
        return $result;
    }

    /**
     * @param $entity
     * @return string
     */
    private function hasMorpTrait($entity)
    {
        $result = '';

        if (isset($entity['ownable']) && $entity['ownable']) {
            $result = ' , HasMorphOwner ';
        }
        return $result;
    }

    /**
     * @param $entity
     * @return string
     */
    private function logsActivityTrait($entity)
    {
        $result = '';

        if (isset($entity['activity']) && $entity['activity']) {
            $result = ', LogsActivity';
        }
        return $result;
    }

    /**
     * @param $entity
     * @return string
     */
    private function commentableUseTrait($entity)
    {
        $result = '';

        if (isset($entity['comments']) && $entity['comments']) {
            $result = ' , Commentable ';
        }
        return $result;
    }

    /**
     * @param $entity
     * @return string
     */
    private function belongToTenantTrait($entity)
    {
        $result = '';

        if (isset($entity['type']) && $entity['type'] == 'main') {
            $result = ' , BelongsToTenants ';
        }

        return $result;
    }

    /**
     * @param $entity
     * @return string
     */
    private function hasAttachmentTrait($entity)
    {
        $result = '';

        if (isset($entity['attachments']) && $entity['attachments']) {
            $result = ' , HasAttachment ';
        }
        return $result;
    }

    /**
     * @param $entity
     * @return string
     */
    private function commentsMustBeApproved($entity)
    {
        $result = '';

        if (isset($entity['comments']) && $entity['comments']) {
            $result = 'protected $mustBeApproved = false;';
        }
        return $result;
    }

    /**
     * @param $entity
     * @return \Illuminate\Contracts\View\View|string
     */
    private function getRelatedModelAttr($entity)
    {
        $result = '';

        if (isset($entity['activity']) && $entity['activity']) {
            return \View::make('codegenerator::entity.get_related_model_attr');
        }
        return $result;
    }

    /**
     * @param $entity
     * @return \Illuminate\Contracts\View\View|string
     */
    private function logsAttributeProp($entity)
    {
        $result = '';

        if (isset($entity['activity']) && $entity['activity']) {
            $view = \View::make('codegenerator::entity.log_attributes');
            $view->with('entity', $entity);

            return $view;
        }
        return $result;
    }

    /**
     * @param $entity
     * @return \Illuminate\Contracts\View\View
     */
    private function fillableProp($entity)
    {
        $view = \View::make('codegenerator::entity.fillable');
        $view->with('entity', $entity);

        return $view;
    }

    /**
     * @param $entity
     * @return \Illuminate\Contracts\View\View
     */
    private function manyToOneRelations($entity)
    {
        $view = \View::make('codegenerator::entity.many_to_one');
        $view->with('entity', $entity);

        return $view;
    }
}
