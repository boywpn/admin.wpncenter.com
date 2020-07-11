<?php

namespace Modules\Platform\Core\Helper;

/**
 * Class StringHelper
 * @package Modules\Platform\Core\Helper
 */
class StringHelper
{

    /**
     * Map priority|status etc ids to colors
     *
     * @param $value
     * @param $colorMap
     * @return mixed
     */
    public static function badgeHelper($value, $colorMap)
    {
        foreach ($colorMap as $key => $color) {
            if ($key == $value) {
                return $color;
            }
        }
    }

    public static function badgeFullHelper($value, $colorMap)
    {
        foreach ($colorMap as $key => $data) {
            if ($key == $value) {
                $icon = "";
                if(!empty($data['icon'])){
                    $icon = "<i class='".$data['icon']."'></i> ";
                }
                $icon = "<i class='".$data['icon']."'></i> ";
                $badge = "<span class='badge " . $data['color'] . "'>" . $icon . $data['name'] . "</span>";
                return $badge;
            }
        }
    }

    /**
     * @param $string
     * @param $char
     * @return bool|string
     */
    public static function fromLastChar($string, $char)
    {
        return substr(strrchr($string, $char), 1);
    }

    public static function validationArrayToString($array)
    {
        $output = implode(', ', array_map(
            function ($v, $k) {
                return sprintf(" %s '%s' dependent records found. ", $k, $v);
            },
            $array,
            array_keys($array)
        ));
        return $output;
    }

    public static function darkenColor($hex, $steps = -20)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color = hexdec($color); // Convert to decimal
            $color = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }
}
