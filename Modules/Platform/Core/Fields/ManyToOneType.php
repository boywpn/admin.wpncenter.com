<?php

namespace Modules\Platform\Core\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;
use Modules\Platform\Core\Repositories\GenericRepository;

/**
 * Class ManyToOneType
 * @package Modules\Platform\Core\Fields
 */
class ManyToOneType extends FormField
{
    /**
     * @inheritdoc
     */
    public function getDefaults()
    {
        return [
            'attr' => ['class' => null, 'id' => $this->getName()],
            'display_value' => '',
            'modal_title' => 'core::core.choose_record'
        ];
    }

    public function setupValue()
    {
        $model = $this->getOption('model');
        $relation = $this->getOption('relation');
        $relationField = $this->getOption('relation_field');

        if (is_array($model)) { // Section for new object in modal.

            $genericRepo = \App::make(GenericRepository::class);

            if (isset($model['relatedEntity']) && $model['relatedEntity'] != '') {
                if ($model['relatedField'] == $this->getOption('real_name')) {
                    $genericRepo->setupModel($model['relatedEntity']);

                    $record = $genericRepo->findWithoutFail($model['relatedEntityId']);

                    $this->setOption('display_value', $record->{$relationField});
                }
            }
        } else {
            if ($model != null && $relation != null && $relationField != null && $model->{$relation} != null) {
                $this->setOption('display_value', $model->{$relation}->{$relationField});
            }
        }

        parent::setupValue();
    }

    protected function getTemplate()
    {
        return 'vendor.laravel-form-builder.manyToOne';
    }
}
