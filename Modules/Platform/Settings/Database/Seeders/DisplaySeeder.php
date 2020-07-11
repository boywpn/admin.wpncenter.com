<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Krucas\Settings\Facades\Settings;
use Modules\Platform\Core\Helper\CompanySettings;
use Modules\Platform\Core\Helper\SettingsHelper;

/**
 * Class SettingsSeeder
 */
class DisplaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->seedCompany1();
        $this->seedCompany2();
    }

    private function seedCompany1(){

        CompanySettings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_APPLICATION, 1,1);
        CompanySettings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_PDF, 1,1);
        CompanySettings::set(SettingsHelper::S_DISPLAY_APPLICATION_NAME, 'OsCorp Intranet',1);
        CompanySettings::set(SettingsHelper::S_DISPLAY_SIDEBAR_BACKGROUND, 'blue.png',1);

        CompanySettings::set(SettingsHelper::S_ANNOUNCEMENT_MESSAGE, '',1);
        CompanySettings::set(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS, '',1);

        CompanySettings::set(SettingsHelper::S_DISPLAY_LOGO_UPLOAD, 'storage/files/logo/logo__1.png',1);
        CompanySettings::set(SettingsHelper::S_DISPLAY_PDF_LOGO_UPLOAD, 'storage/files/logo/logo_pdf___1.png',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_NAME, 'OsCorp',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_ADDRESS_, '4376 Southern Avenue',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_CITY, 'Ottumwa',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_STATE, 'Iowa',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_POSTAL_CODE, '52501',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_COUNTRY, 'USA',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_PHONE, '641-455-5847',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_FAX, '',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_WEBSITE, 'http://laravel-bap.com',1);
        CompanySettings::set(SettingsHelper::S_COMPANY_VAT_ID, '',1);
        
    }

    private function seedCompany2(){

        CompanySettings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_APPLICATION, 1,2);
        CompanySettings::set(SettingsHelper::S_DISPLAY_SHOW_LOGO_IN_PDF, 1,2);
        CompanySettings::set(SettingsHelper::S_DISPLAY_APPLICATION_NAME, 'Umbrella Corporation',2);
        CompanySettings::set(SettingsHelper::S_DISPLAY_SIDEBAR_BACKGROUND, 'orange.jpg',2);

        CompanySettings::set(SettingsHelper::S_ANNOUNCEMENT_MESSAGE, '',2);
        CompanySettings::set(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS, '',2);

        CompanySettings::set(SettingsHelper::S_DISPLAY_LOGO_UPLOAD, 'storage/files/logo/logo__2.png',2);
        CompanySettings::set(SettingsHelper::S_DISPLAY_PDF_LOGO_UPLOAD, 'storage/files/logo/logo_pdf___2.png',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_NAME, 'Umbrella Corporation',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_ADDRESS_, '4376 Southern Avenue',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_CITY, 'Ottumwa',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_STATE, 'Iowa',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_POSTAL_CODE, '52501',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_COUNTRY, 'USA',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_PHONE, '641-455-5847',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_FAX, '',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_WEBSITE, 'http://laravel-bap.com',2);
        CompanySettings::set(SettingsHelper::S_COMPANY_VAT_ID, '',2);
        
    }
}
