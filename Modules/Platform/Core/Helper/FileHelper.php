<?php

namespace Modules\Platform\Core\Helper;

/**
 * Class FileHelper
 * @package Modules\Platform\Core\Helper
 */
class FileHelper
{
    const GRAPHIC_MIME_TYPES = [
        'image/gif',
        'image/jpeg',
        'image/png'
    ];

    /**
     * Format Size
     *
     * @param $size
     * @return string
     */
    public static function formatSize($size) {

        $units = explode(' ', 'B KB MB GB TB PB');

        $mod = 1024;

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        $endIndex = strpos($size, ".")+3;

        return substr( $size, 0, $endIndex).' '.$units[$i];
    }

    public static function displayAttachmentIcon($attachment)
    {
        if (in_array($attachment->filetype, self::GRAPHIC_MIME_TYPES)) {
            return $attachment->url;
        }
        return '/bap/images/file_icon.png';
    }

    /**
     * Print memory consumption
     */
    public static function memoryInfo()
    {
        if (config('app.debug')) {
            self::echoFormatByest(memory_get_usage());
        }
    }

    /**
     * @param $size
     * @param int $precision
     */
    public static function echoFormatByest($size, $precision = 2)
    {
        echo PHP_EOL;
        echo '*************';
        echo self::formatBytes($size, $precision);
        echo PHP_EOL;
    }

    /**
     * @param $size
     * @param int $precision
     * @return string
     */
    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }


    public static function listFiles($dir)
    {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_file($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = $value;
                }
            }
        }
        return $result;
    }
}
