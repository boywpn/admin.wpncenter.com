<?php

namespace Modules\Platform\Core\Helper;

/**
 * Theme Helper
 *
 * Class ThemeHelper
 * @package Modules\Platform\Core\Helper
 */
class ThemeHelper
{

    /**
     * Supported theme colors
     */
    const SUPPORTED_THEMES = [
        'theme-red' => 'Red',
        'theme-pink' => 'Pink',
        'theme-purple' => 'Purple',
        'theme-deep-purple' => 'Deep-purple',
        'theme-indigo' => 'Indigo',
        'theme-blue' => 'Blue',
        'theme-light-blue' => 'Light-blue',
        'theme-cyan' => 'Cyan',
        'theme-teal' => 'Teal',
        'theme-green' => 'Green',
        'theme-light-green' => 'Light-green',
        'theme-lime' => 'Lime',
        'theme-amber' => 'Amber',
        'theme-orange' => 'Orange',
        'theme-deep-orange' => 'Deep-orange',
        'theme-grey' => 'Grey',
        'theme-blue-grey' => 'Blue-grey',
        'theme-black' => 'Black'
    ];

    /**
     * Check if theme is active
     * @param $theme
     * @return string
     */
    public static function isActive($theme)
    {
        $auth = \Auth::user();

        if ($theme == $auth->theme()) {
            return 'active';
        }
    }
}
