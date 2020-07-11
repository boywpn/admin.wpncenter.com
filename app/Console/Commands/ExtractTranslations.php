<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use Stringy\Stringy;

/**
 * Class ExtractTranslations
 * @package App\Console\Commands
 */
class ExtractTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bap:extract_translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BAP Extract translations file for translation Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $path = Stringy::create(base_path());

        $baseFolder = $path->substr($path->indexOfLast('/')+1,$path->length());

        $modules = Module::all();

        $this->rrmdir(base_path('helper_files/translations/Modules'));
        $this->rrmdir(base_path('helper_files/translations/resources'));

        foreach ($modules as $module) {
            $moduleTransForm = $module->getPath() . '/Resources/lang/en';

            $this->recurse_copy($moduleTransForm, str_replace("/$baseFolder/", "/$baseFolder/helper_files/translations/", $moduleTransForm));
        }

        $resourceTrans = base_path('resources/lang');
        $this->recurse_copy($resourceTrans, str_replace("/$baseFolder/", "/$baseFolder/helper_files/translations/", $resourceTrans));



    }

    function rrmdir($path)
    {
        if (file_exists($path)) {
            // Open the source directory to read in files
            $i = new \DirectoryIterator($path);
            foreach ($i as $f) {
                if ($f->isFile()) {
                    unlink($f->getRealPath());
                } else if (!$f->isDot() && $f->isDir()) {
                    $this->rrmdir($f->getRealPath());
                }
            }
            rmdir($path);
        }
    }

    function recurse_copy($src, $dst)
    {
        dump($src.' ===> '.$dst);

        if (!file_exists($src)) {
            return false;
        }

        if (!file_exists($dst)) {
            mkdir($dst, 0777, true);
        }

        $dir = opendir($src);

        @mkdir($dst);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }

        closedir($dir);
    }
}
