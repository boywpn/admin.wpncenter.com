<?php

namespace Modules\Platform\Core\Helper;

use Carbon\Carbon;
use Dotenv\Dotenv;

/**
 * Environment variables
 *
 * Class EnvHelper
 * @package Modules\Platform\Core\Helper
 */
class EnvHelper
{
    public static function env()
    {
        return $environment = (new \josegonzalez\Dotenv\Loader(base_path().'/.env'))
            ->parse()
            ->toArray(); // Throws LogicException if ->parse() is not called first
    }

    public static function checkIsEnvConfigured()
    {
        try {
            $env = new Dotenv(base_path());
            // $env->load();

            $env = $env->required(['DB_HOST','DB_DATABASE','DB_USERNAME','DB_PASSWORD','APP_URL']);

            return [
                'allow_to_install' => true,
                'message' => trans('bap.env_configured')
            ];
        } catch (\RuntimeException $e) {
            return [
                'allow_to_install' =>  false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Update Environment file variable
     *
     * @param array $data
     */
    public static function updateEnv($data = array())
    {
        if (!count($data)) {
            return;
        }

        $pattern = '/([^\=]*)\=[^\n]*/';

        $envFile = base_path() . '/.env';
        $lines = file($envFile);
        $newLines = [];
        foreach ($lines as $line) {
            preg_match($pattern, $line, $matches);

            if (!count($matches)) {
                $newLines[] = $line;
                continue;
            }

            if (!key_exists(trim($matches[1]), $data)) {
                $newLines[] = $line;
                continue;
            }

            $line = trim($matches[1]) . "={$data[trim($matches[1])]}\n";
            $newLines[] = $line;
        }

        $newContent = implode('', $newLines);
        file_put_contents($envFile, $newContent);
    }
}
