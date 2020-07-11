<?php

namespace Modules\Platform\Core\Helper\Installer;

class InstallerValidator
{
    public $valid = true;

    public $message = '';

    public function validate()
    {
        $envArray = (new \josegonzalez\Dotenv\Loader(base_path().'/.env'))->parse()->toArray();

        $toValidate = ['APP_URL','DB_HOST','DB_PORT','DB_DATABASE','DB_USERNAME'];

        foreach ($toValidate as $value) {
            if (empty($envArray[$value])) {
                $this->valid = false;
            }
        }

        if (!$this->valid) {
            $this->message =   trans('bap.env_error');
        }
        if ($this->valid) {
            $this->message =  trans('bap.env_configured');
        }
    }
}
