<?php

namespace Modules\Platform\Core\Helper;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Krucas\Settings\Facades\Settings;


/**
 *
 * Settings Helper
 *
 * Class SettingsHelper
 * @package Modules\Platform\Core\Helper
 */
class SettingsHelper
{
    const S_COMPANY_NAME = 's_company_name';

    const S_COMPANY_ADDRESS_ = 's_company_address';

    const S_COMPANY_CITY = 's_company_city';

    const S_COMPANY_STATE = 's_company_state';

    const S_COMPANY_POSTAL_CODE = 's_company_postal_code';

    const S_COMPANY_COUNTRY = 's_company_country';

    const S_COMPANY_PHONE = 's_company_phone';

    const S_COMPANY_FAX = 's_company_fax';

    const S_COMPANY_WEBSITE = 's_company_website';

    const S_COMPANY_VAT_ID = 's_company_vat_id';

    const S_DISPLAY_SHOW_LOGO_ON_LOGIN = 's_display_show_logo_on_login';

    const S_DISPLAY_SHOW_LOGO_IN_APPLICATION = 's_display_show_logo_in_application';

    const S_DISPLAY_SHOW_LOGO_IN_PDF = 's_display_show_logo_in_pdf';

    const S_DISPLAY_APPLICATION_NAME = 's_display_application_name';

    const S_DISPLAY_LOGO_UPLOAD = 's_display_logo_upload';

    const S_DISPLAY_PDF_LOGO_UPLOAD = 's_display_pdf_logo_upload';

    const S_DISPLAY_LOGIN_BACKGROUND_IMAGE = 's_display_login_background_image';

    const S_DISPLAY_SIDEBAR_BACKGROUND = 's_display_sidebar_background';

    const S_ANNOUNCEMENT_MESSAGE = 's_announcement_message';

    const S_ANNOUNCEMENT_DISPLAY_CLASS = 's_announcement_display_class';

    const CONST_LOGO_UPLOAD_PATH = 'storage/files/logo/';

    /**
     * Return company settings
     * @return array
     */
    public static function companySettings()
    {
        return [
            'company_name' => CompanySettings::get(SettingsHelper::S_COMPANY_NAME),
            'address' => CompanySettings::get(SettingsHelper::S_COMPANY_ADDRESS_),
            'city' => CompanySettings::get(SettingsHelper::S_COMPANY_CITY),
            'state' => CompanySettings::get(SettingsHelper::S_COMPANY_STATE),
            'postal_code' => CompanySettings::get(SettingsHelper::S_COMPANY_POSTAL_CODE),
            'country' => CompanySettings::get(SettingsHelper::S_COMPANY_COUNTRY),
            'phone' => CompanySettings::get(SettingsHelper::S_COMPANY_PHONE),
            'fax' => CompanySettings::get(SettingsHelper::S_COMPANY_FAX),
            'website' => CompanySettings::get(SettingsHelper::S_COMPANY_WEBSITE),
            'vat_id' => CompanySettings::get(SettingsHelper::S_COMPANY_VAT_ID)
        ];
    }


    public static function getApplicationTitle()
    {

        $context = CompanySettings::getContext();

        if (!empty($context)) {
            return CompanySettings::get(\Modules\Platform\Core\Helper\SettingsHelper::S_DISPLAY_APPLICATION_NAME, config('app.name'));
        }

        return config('app.name');
    }

    /**
     * @return string
     */
    public static function siebarBackground()
    {
        $styles = '';

        $backgroundImage = CompanySettings::get(SettingsHelper::S_DISPLAY_SIDEBAR_BACKGROUND, 'orange.jpg');

        if ($backgroundImage != '') {
            $styles = "background: url('/bg/sidebar/" . $backgroundImage . "') no-repeat no-repeat; background-size: cover;";
        }

        return $styles;
    }

    /**
     * Display application logo if exist
     */
    public static function displayLogo()
    {
        $logo = CompanySettings::get(SettingsHelper::S_DISPLAY_LOGO_UPLOAD);

        if ($logo != '') {
            return '<img class="application-logo" src="' . asset($logo) . '" />';
        }
    }

    public static function logoPath()
    {
        $logo = CompanySettings::get(SettingsHelper::S_DISPLAY_LOGO_UPLOAD);

        if ($logo != '') {
            return '<img class="application-logo" src="' . public_path($logo) . '" />';
        }
    }

    public static function pdfLogoPath(){
        $logo = CompanySettings::get(SettingsHelper::S_DISPLAY_PDF_LOGO_UPLOAD);

        if ($logo != '') {
            return '<img class="application-logo" src="' . public_path($logo) . '" />';
        }
    }
}
