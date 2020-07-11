<?php

namespace Modules\Platform\Core\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityLogHelper
 * @package Modules\Platform\Core\Helper
 */
class ActivityLogHelper
{

    /**
     * Log changes of related objects
     *
     * @param Model $model
     * @param string $attribute
     * @return array
     */
    public static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        if (substr_count($attribute, '.') > 1) {
        }

        list($relatedModelName, $relatedAttribute) = explode('.', $attribute);

        $relatedModel = $model->$relatedModelName ?? $model->$relatedModelName();

        return ["{$relatedModelName}.{$relatedAttribute}" => $relatedModel->$relatedAttribute ?? null ];
    }
}
