<?php

namespace Modules\Platform\CodeGenerator\Lib;

/**
 * File helper
 *
 * Class FileHelper
 * @package Modules\Platform\CodeGenerator\Lib
 */
class FileHelper
{


    /**
     * @param $config
     * @return string
     */
    public static function newModuleFilePath($config)
    {
        return 'Modules/' . $config['setup']['module_name'];
    }

    /**
     * Create File
     *
     * @param $file
     * @param $content
     * @return bool|int
     */
    public static function createFile($file, $content)
    {
        $path = pathinfo($file, PATHINFO_DIRNAME);

        if (!file_exists($path)) {
            mkdir($path, 0776, true);
        }

        return file_put_contents($path, $content);
    }


    /**
     * @param $dir
     * @return bool
     */
    public static function recurseRmdir($dir)
    {
        if (file_exists($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? self::recurseRmdir("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
        }
    }
}
