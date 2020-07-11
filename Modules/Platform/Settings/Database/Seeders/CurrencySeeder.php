<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

/**
 * Class SettingsTimeFormatSeeder
 */
class CurrencySeeder extends SeederHelper
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = array(

            array('code' => 'AFN', 'name' => 'Afghani', 'symbol' => '؋'),
            array('code' => 'ALL', 'name' => 'Lek', 'symbol' => 'Lek'),
            array('code' => 'ANG', 'name' => 'Netherlands Antillian Guilder', 'symbol' => 'ƒ'),
            array('code' => 'ARS', 'name' => 'Argentine Peso', 'symbol' => '$'),
            array('code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => '$'),
            array('code' => 'AWG', 'name' => 'Aruban Guilder', 'symbol' => 'ƒ'),
            array('code' => 'AZN', 'name' => 'Azerbaijanian Manat', 'symbol' => 'ман'),
            array('code' => 'BAM', 'name' => 'Convertible Marks', 'symbol' => 'KM'),
            array('code' => 'BBD', 'name' => 'Barbados Dollar', 'symbol' => '$'),
            array('code' => 'BGN', 'name' => 'Bulgarian Lev', 'symbol' => 'лв'),
            array('code' => 'BMD', 'name' => 'Bermudian Dollar', 'symbol' => '$'),
            array('code' => 'BND', 'name' => 'Brunei Dollar', 'symbol' => '$'),
            array('code' => 'BOB', 'name' => 'BOV Boliviano Mvdol', 'symbol' => '$b'),
            array('code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'),
            array('code' => 'BSD', 'name' => 'Bahamian Dollar', 'symbol' => '$'),
            array('code' => 'BWP', 'name' => 'Pula', 'symbol' => 'P'),
            array('code' => 'BYR', 'name' => 'Belarussian Ruble', 'symbol' => 'p.'),
            array('code' => 'BZD', 'name' => 'Belize Dollar', 'symbol' => 'BZ$'),
            array('code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => '$'),
            array('code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF'),
            array('code' => 'CLP', 'name' => 'CLF Chilean Peso Unidades de fomento', 'symbol' => '$'),
            array('code' => 'CNY', 'name' => 'Yuan Renminbi', 'symbol' => '¥'),
            array('code' => 'COP', 'name' => 'COU Colombian Peso Unidad de Valor Real', 'symbol' => '$'),
            array('code' => 'CRC', 'name' => 'Costa Rican Colon', 'symbol' => '₡'),
            array('code' => 'CUP', 'name' => 'CUC Cuban Peso Peso Convertible', 'symbol' => '₱'),
            array('code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč'),
            array('code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr'),
            array('code' => 'DOP', 'name' => 'Dominican Peso', 'symbol' => 'RD$'),
            array('code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => '£'),
            array('code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'),
            array('code' => 'FJD', 'name' => 'Fiji Dollar', 'symbol' => '$'),
            array('code' => 'FKP', 'name' => 'Falkland Islands Pound', 'symbol' => '£'),
            array('code' => 'GBP', 'name' => 'Pound Sterling', 'symbol' => '£'),
            array('code' => 'GIP', 'name' => 'Gibraltar Pound', 'symbol' => '£'),
            array('code' => 'GTQ', 'name' => 'Quetzal', 'symbol' => 'Q'),
            array('code' => 'GYD', 'name' => 'Guyana Dollar', 'symbol' => '$'),
            array('code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => '$'),
            array('code' => 'HNL', 'name' => 'Lempira', 'symbol' => 'L'),
            array('code' => 'HRK', 'name' => 'Croatian Kuna', 'symbol' => 'kn'),
            array('code' => 'HUF', 'name' => 'Forint', 'symbol' => 'Ft'),
            array('code' => 'IDR', 'name' => 'Rupiah', 'symbol' => 'Rp'),
            array('code' => 'ILS', 'name' => 'New Israeli Sheqel', 'symbol' => '₪'),
            array('code' => 'IRR', 'name' => 'Iranian Rial', 'symbol' => '﷼'),
            array('code' => 'ISK', 'name' => 'Iceland Krona', 'symbol' => 'kr'),
            array('code' => 'JMD', 'name' => 'Jamaican Dollar', 'symbol' => 'J$'),
            array('code' => 'JPY', 'name' => 'Yen', 'symbol' => '¥'),
            array('code' => 'KGS', 'name' => 'Som', 'symbol' => 'лв'),
            array('code' => 'KHR', 'name' => 'Riel', 'symbol' => '៛'),
            array('code' => 'KPW', 'name' => 'North Korean Won', 'symbol' => '₩'),
            array('code' => 'KRW', 'name' => 'Won', 'symbol' => '₩'),
            array('code' => 'KYD', 'name' => 'Cayman Islands Dollar', 'symbol' => '$'),
            array('code' => 'KZT', 'name' => 'Tenge', 'symbol' => 'лв'),
            array('code' => 'LAK', 'name' => 'Kip', 'symbol' => '₭'),
            array('code' => 'LBP', 'name' => 'Lebanese Pound', 'symbol' => '£'),
            array('code' => 'LKR', 'name' => 'Sri Lanka Rupee', 'symbol' => '₨'),
            array('code' => 'LRD', 'name' => 'Liberian Dollar', 'symbol' => '$'),
            array('code' => 'LTL', 'name' => 'Lithuanian Litas', 'symbol' => 'Lt'),
            array('code' => 'LVL', 'name' => 'Latvian Lats', 'symbol' => 'Ls'),
            array('code' => 'MKD', 'name' => 'Denar', 'symbol' => 'ден'),
            array('code' => 'MNT', 'name' => 'Tugrik', 'symbol' => '₮'),
            array('code' => 'MUR', 'name' => 'Mauritius Rupee', 'symbol' => '₨'),
            array('code' => 'MXN', 'name' => 'MXV Mexican Peso Mexican Unidad de Inversion (UDI)', 'symbol' => '$'),
            array('code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM'),
            array('code' => 'MZN', 'name' => 'Metical', 'symbol' => 'MT'),
            array('code' => 'NGN', 'name' => 'Naira', 'symbol' => '₦'),
            array('code' => 'NIO', 'name' => 'Cordoba Oro', 'symbol' => 'C$'),
            array('code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr'),
            array('code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => '₨'),
            array('code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => '$'),
            array('code' => 'OMR', 'name' => 'Rial Omani', 'symbol' => '﷼'),
            array('code' => 'PAB', 'name' => 'USD Balboa US Dollar', 'symbol' => 'B/.'),
            array('code' => 'PEN', 'name' => 'Nuevo Sol', 'symbol' => 'S/.'),
            array('code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => 'Php'),
            array('code' => 'PKR', 'name' => 'Pakistan Rupee', 'symbol' => '₨'),
            array('code' => 'PLN', 'name' => 'Zloty', 'symbol' => 'zł'),
            array('code' => 'PYG', 'name' => 'Guarani', 'symbol' => 'Gs'),
            array('code' => 'QAR', 'name' => 'Qatari Rial', 'symbol' => '﷼'),
            array('code' => 'RON', 'name' => 'New Leu', 'symbol' => 'lei'),
            array('code' => 'RSD', 'name' => 'Serbian Dinar', 'symbol' => 'Дин.'),
            array('code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => 'руб'),
            array('code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼'),
            array('code' => 'SBD', 'name' => 'Solomon Islands Dollar', 'symbol' => '$'),
            array('code' => 'SCR', 'name' => 'Seychelles Rupee', 'symbol' => '₨'),
            array('code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr'),
            array('code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => '$'),
            array('code' => 'SHP', 'name' => 'Saint Helena Pound', 'symbol' => '£'),
            array('code' => 'SOS', 'name' => 'Somali Shilling', 'symbol' => 'S'),
            array('code' => 'SRD', 'name' => 'Surinam Dollar', 'symbol' => '$'),
            array('code' => 'SVC', 'name' => 'USD El Salvador Colon US Dollar', 'symbol' => '$'),
            array('code' => 'SYP', 'name' => 'Syrian Pound', 'symbol' => '£'),
            array('code' => 'THB', 'name' => 'Baht', 'symbol' => '฿'),
            array('code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => 'TL'),
            array('code' => 'TTD', 'name' => 'Trinidad and Tobago Dollar', 'symbol' => 'TT$'),
            array('code' => 'TWD', 'name' => 'New Taiwan Dollar', 'symbol' => 'NT$'),
            array('code' => 'UAH', 'name' => 'Hryvnia', 'symbol' => '₴'),
            array('code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'),
            array('code' => 'UYU', 'name' => 'UYI Peso Uruguayo Uruguay Peso en Unidades Indexadas', 'symbol' => '$U'),
            array('code' => 'UZS', 'name' => 'Uzbekistan Sum', 'symbol' => 'лв'),
            array('code' => 'VEF', 'name' => 'Bolivar Fuerte', 'symbol' => 'Bs'),
            array('code' => 'VND', 'name' => 'Dong', 'symbol' => '₫'),
            array('code' => 'XCD', 'name' => 'East Caribbean Dollar', 'symbol' => '$'),
            array('code' => 'YER', 'name' => 'Yemeni Rial', 'symbol' => '﷼'),
            array('code' => 'ZAR', 'name' => 'Rand', 'symbol' => 'R'),
        );

        DB::table('bap_currency')->truncate();

        $final = [];
        foreach ($currencies as $currency) {
            $currency['created_at'] = Carbon::now();

            $final[] = $currency;
        }

        DB::table('bap_currency')->insert($final);
    }
}
