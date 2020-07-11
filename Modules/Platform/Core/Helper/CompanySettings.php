<?php

namespace Modules\Platform\Core\Helper;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Krucas\Settings\Context;
use Modules\Platform\Companies\Service\CompanyService;

class CompanySettings
{

    public static function getCompanyPrefix()
    {
        if (\Auth::check()) {
            $user = \Auth::user();

            $companyConext = session()->get(CompanyService::COMPANY_CONTEXT_SESSION);

            if ($companyConext != null) {

                return '__' . $companyConext->id;
            }
            if ($user->company != null) {
                return '__' . $user->company->id;;
            }

            return '';
        }
        return '';
    }

    public static function getContextCompanyId()
    {

        if (\Auth::check()) {
            $user = \Auth::user();

            $companyConext = session()->get(CompanyService::COMPANY_CONTEXT_SESSION);

            $companyId = null;

            if ($companyConext != null) {
                $companyId = $companyConext->id;

            }
            if ($user->company != null) {
                $companyId = $user->company->id;
            }

            return $companyId;
        }

        return null;

    }

    public static function getContext()
    {

        if (\Auth::check()) {
            $user = \Auth::user();

            $companyConext = session()->get(CompanyService::COMPANY_CONTEXT_SESSION);

            $companyId = null;

            if ($companyConext != null) {
                $companyId = $companyConext->id;

            }else if ($user->company != null) {
                $companyId = $user->company->id;
            }else{
                $companyId = Landlord::getTenants()->first();
            }

            if($companyId != null ) {

                return new Context(['company_id' => $companyId]);

            }
            return null;
        }

        return null;
    }

    public static function get($key, $default = null, $companyId = null)
    {

        $settingsContext = self::getContext();

        if ($companyId != null && $companyId > 0) {
            $settingsContext = new Context(['company_id' => $companyId]);
        }

        if (!empty($settingsContext)) {
            return \Settings::context($settingsContext)->get($key);
        }

        return \Settings::get($key, $default);

    }

    public static function set($key, $value = null, $companyId = null)
    {
        $settingsContext = self::getContext();

        if ($companyId != null && $companyId > 0) {
            $settingsContext = new Context(['company_id' => $companyId]);
        }

        if (!empty($settingsContext)) {
            \Settings::context($settingsContext)->set($key, $value);
        }

        return \Settings::set($key, $value);
    }

}