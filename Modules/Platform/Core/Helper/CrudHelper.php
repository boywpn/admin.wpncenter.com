<?php

namespace Modules\Platform\Core\Helper;

use Carbon\Carbon;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class CrudHelper
 * @package Modules\Platform\Core\Helper
 */
class CrudHelper
{
    const BELONGS_TO_MANY = 'Illuminate\Database\Eloquent\Relations\BelongsToMany';

    const BELONGS_TO = 'Illuminate\Database\Eloquent\Relations\BelongsTo';

    /**
     * Check if string start with
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * Check if string end with
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
            (substr($haystack, -$length) === $needle);
    }

    /**
     * Learn method type
     * @param $classname
     * @param $method
     * @return string
     */
    public static function learnMethodType($classname, $method)
    {
        $obj = new $classname;
        $type = get_class($obj->{$method}());

        return $type;
    }

    /**
     * Set created at & updated at to now in array
     * @param $array
     * @return mixed
     */
    public static function setDatesInArray($array)
    {
        $result = [];

        foreach ($array as $arr) {
            $arr['updated_at'] = Carbon::now();
            $arr['created_at'] = Carbon::now();

            $result[] = $arr;
        }
        return $result;
    }


    /**
     * Remove values from array
     * @param $array
     * @param array $values
     * @return array
     */
    public static function removeValues($array,$values = []){

        return array_diff($array,$values);

    }

    public static function array_search_key( $needle_key, $array ) {
        foreach($array AS $key=>$value){
            if($key == $needle_key) return $value;
            if(is_array($value)){
                if( ($result = self::array_search_key($needle_key,$value)) !== false)
                    return $result;
            }
        }
        return false;
    }

    public static function relationships($model)
    {


        $relationships = [];

        foreach ((new ReflectionClass($model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class != get_class($model) ||
                !empty($method->getParameters()) ||
                $method->getName() == __FUNCTION__) {
                continue;
            }

            try {
                $return = $method->invoke($model);

                if ($return instanceof Relation) {
                    $relationships[$method->getName()] = [
                        'type' => (new ReflectionClass($return))->getShortName(),
                        'model' => (new ReflectionClass($return->getRelated()))->getName(),

                    ];
                }
            } catch (ErrorException $e) {
            }
        }

        return $relationships;
    }

}
