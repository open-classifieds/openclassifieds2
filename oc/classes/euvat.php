<?php
/**
 * EU Vat helper functions
 *
 * @package    OC
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class euvat {

    public static $date_start = '2015-01-01';
    
    /**
     * gets the country code from the user
     * @param  string $ip_address 
     * @param boolean $force_geioip, forces the usage of geoip not cloudflare
     * @return string             
     */
    public static function country_code($ip_address=NULL, $force_geoip = FALSE)
    {
        //cloudflare installed? no ip?
        if ($ip_address===NULL AND !empty($_SERVER["HTTP_CF_IPCOUNTRY"]) AND !$force_geoip )
        {
            $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
        }
        //no cloudflare installed or forced try geoip
        else
        {
            if ($ip_address===NULL)
                $ip_address = Request::$client_ip;

            $country_code = Geoip3::instance()->country_code($ip_address);
        }

        return $country_code;
    }



    /**
     * get country name from a country code, in case not set get by ip
     * @param  string $country_code [description]
     * @param  string $ip_address 
     * @return string
     */
    public static function country_name($country_code=NULL, $ip_address=NULL)
    {
        $countries = self::countries();

        if ($country_code===NULL OR empty($country_code))
            $country_code = self::country_code($ip_address);

        return (isset($countries[$country_code])) ? $countries[$country_code] : NULL;
    }

    /**
     * verifies vies number
     * @param  string $vat_number   
     * @param  string $country_code 
     * @return boolean               
     */
    public static function verify_vies($vat_number,$country_code=NULL)
    {
        if ($vat_number==NULL OR $vat_number=='' OR strlen($vat_number)<3)
            return FALSE;

        if ($country_code===NULL OR empty($country_code))
            $country_code = self::country_code();

        //first check if country is part of EU 
        if ((array_key_exists($country_code, self::get_vat_rates())))
        {
            if($country_code=='GR')
                $country_code = 'EL';
            
            $client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
            $res = $client->checkVat(array('countryCode' => $country_code,'vatNumber' => $vat_number));

            //not valid
            if (!$res->valid)
                return FALSE;
        }        
        else {
            return FALSE; // THIS LINE WILL BE REMOVED!! Return false if the country is not in the array $countries
        }

        return TRUE;
    }

    /**
     * its a valid vat country?
     * @param  strinf  $country_code 
     * @return boolean               
     */
    public static function is_eu_country($country_code=NULL)
    {   
        if ($country_code===NULL OR empty($country_code))
            $country_code = self::country_code();

        $eu_rate = self::get_vat_rates();

        return isset($eu_rate[$country_code]);
    }

    /**
     * get VAT for the copuntry
     * @param  strinf  $country_code 
     * @return integer               
     */
    public static function vat_by_country($country_code)
    {
        $eu_rate = self::get_vat_rates();

        // list of country exceptions
        $excluded = explode(',',core::config('general.vat_excluded_countries'));

        if ( isset($eu_rate[$country_code]) AND 
            is_numeric($eu_rate[$country_code]['standard_rate']) AND 
            !in_array($country_code, $excluded) )
            return $eu_rate[$country_code]['standard_rate'];
        else 
            return 0;
    }

    /**
     * returns how much VAT the user needs to pay
     * @return integer 
     */
    public static function vat_percentage()
    {
        //in time frame? and activated?
        // if (core::config('general.eu_vat')==TRUE  AND time()>=strtotime(self::$date_start))
        if (time()>=strtotime(self::$date_start))
        {
            //logged lets check vat number
            if (Auth::instance()->logged_in()===TRUE) 
            {
                $user = Auth::instance()->get_user();

                //its a country from the eu country? and without validated VAT vies
                if(self::is_eu_country($user->country) AND strlen($user->VAT_number) > 2)
                {
                    return self::vat_by_country($user->country);
                }
            }
            //not logged get country
            else
            {
                return self::vat_by_country(self::country_code());
            }
        }

        //not in time or not activated
        return 0;
    }

    /**
     * 
     * @param  boolean $reload  
     * @return void
     */
    public static function get_vat_rates($reload = FALSE)
    {
        //we check the date of our local versions.php
        $vat_rates_file = APPPATH.'config/eu_vat_rates.json';

        //if older than a month or ?reload=1 force reload
        if (!file_exists($vat_rates_file) OR time() > strtotime('+1 month',filemtime($vat_rates_file)) OR $reload === TRUE )
        {
            //read from external source outdated http://wceuvatcompliance.s3.amazonaws.com/rates.json  , new prooject https://github.com/benbucksch/eu-vat-rates
            $file = Core::curl_get_contents('https://cdn.jsdelivr.net/gh/benbucksch/eu-vat-rates/rates.json?r='.time());
            
            if ($file!==NULL)
                File::write($vat_rates_file, $file);
        }

        //return only the rates
        $rates = json_decode(file_get_contents($vat_rates_file),TRUE);

        return $rates['rates'];
    }


    /**
     * Array of country codes (ISO 3166-1 alpha-2) and corresponding names
     * @see https://gist.github.com/vxnick/380904
     * @var array
     */
    public static function countries()
    {
        return array (
            'AF' => __('Afghanistan'),
            'AX' => __('Aland Islands'),
            'AL' => __('Albania'),
            'DZ' => __('Algeria'),
            'AS' => __('American Samoa'),
            'AD' => __('Andorra'),
            'AO' => __('Angola'),
            'AI' => __('Anguilla'),
            'AQ' => __('Antarctica'),
            'AG' => __('Antigua And Barbuda'),
            'AR' => __('Argentina'),
            'AM' => __('Armenia'),
            'AW' => __('Aruba'),
            'AU' => __('Australia'),
            'AT' => __('Austria'),
            'AZ' => __('Azerbaijan'),
            'BS' => __('Bahamas'),
            'BH' => __('Bahrain'),
            'BD' => __('Bangladesh'),
            'BB' => __('Barbados'),
            'BY' => __('Belarus'),
            'BE' => __('Belgium'),
            'BZ' => __('Belize'),
            'BJ' => __('Benin'),
            'BM' => __('Bermuda'),
            'BT' => __('Bhutan'),
            'BO' => __('Bolivia'),
            'BA' => __('Bosnia And Herzegovina'),
            'BW' => __('Botswana'),
            'BV' => __('Bouvet Island'),
            'BR' => __('Brazil'),
            'IO' => __('British Indian Ocean Territory'),
            'BN' => __('Brunei Darussalam'),
            'BG' => __('Bulgaria'),
            'BF' => __('Burkina Faso'),
            'BI' => __('Burundi'),
            'KH' => __('Cambodia'),
            'CM' => __('Cameroon'),
            'CA' => __('Canada'),
            'CV' => __('Cape Verde'),
            'KY' => __('Cayman Islands'),
            'CF' => __('Central African Republic'),
            'TD' => __('Chad'),
            'CL' => __('Chile'),
            'CN' => __('China'),
            'CX' => __('Christmas Island'),
            'CC' => __('Cocos (Keeling) Islands'),
            'CO' => __('Colombia'),
            'KM' => __('Comoros'),
            'CG' => __('Congo'),
            'CD' => __('Congo, Democratic Republic'),
            'CK' => __('Cook Islands'),
            'CR' => __('Costa Rica'),
            'CI' => __('Cote D\'Ivoire'),
            'HR' => __('Croatia'),
            'CU' => __('Cuba'),
            'CY' => __('Cyprus'),
            'CZ' => __('Czech Republic'),
            'DK' => __('Denmark'),
            'DJ' => __('Djibouti'),
            'DM' => __('Dominica'),
            'DO' => __('Dominican Republic'),
            'EC' => __('Ecuador'),
            'EG' => __('Egypt'),
            'SV' => __('El Salvador'),
            'GQ' => __('Equatorial Guinea'),
            'ER' => __('Eritrea'),
            'EE' => __('Estonia'),
            'ET' => __('Ethiopia'),
            'FK' => __('Falkland Islands (Malvinas)'),
            'FO' => __('Faroe Islands'),
            'FJ' => __('Fiji'),
            'FI' => __('Finland'),
            'FR' => __('France'),
            'GF' => __('French Guiana'),
            'PF' => __('French Polynesia'),
            'TF' => __('French Southern Territories'),
            'GA' => __('Gabon'),
            'GM' => __('Gambia'),
            'GE' => __('Georgia'),
            'DE' => __('Germany'),
            'GH' => __('Ghana'),
            'GI' => __('Gibraltar'),
            'GR' => __('Greece'),
            'GL' => __('Greenland'),
            'GD' => __('Grenada'),
            'GP' => __('Guadeloupe'),
            'GU' => __('Guam'),
            'GT' => __('Guatemala'),
            'GG' => __('Guernsey'),
            'GN' => __('Guinea'),
            'GW' => __('Guinea-Bissau'),
            'GY' => __('Guyana'),
            'HT' => __('Haiti'),
            'HM' => __('Heard Island & Mcdonald Islands'),
            'VA' => __('Holy See (Vatican City State)'),
            'HN' => __('Honduras'),
            'HK' => __('Hong Kong'),
            'HU' => __('Hungary'),
            'IS' => __('Iceland'),
            'IN' => __('India'),
            'ID' => __('Indonesia'),
            'IR' => __('Iran, Islamic Republic Of'),
            'IQ' => __('Iraq'),
            'IE' => __('Ireland'),
            'IM' => __('Isle Of Man'),
            'IL' => __('Israel'),
            'IT' => __('Italy'),
            'JM' => __('Jamaica'),
            'JP' => __('Japan'),
            'JE' => __('Jersey'),
            'JO' => __('Jordan'),
            'KZ' => __('Kazakhstan'),
            'KE' => __('Kenya'),
            'KI' => __('Kiribati'),
            'KR' => __('Korea'),
            'KW' => __('Kuwait'),
            'KG' => __('Kyrgyzstan'),
            'LA' => __('Lao People\'s Democratic Republic'),
            'LV' => __('Latvia'),
            'LB' => __('Lebanon'),
            'LS' => __('Lesotho'),
            'LR' => __('Liberia'),
            'LY' => __('Libyan Arab Jamahiriya'),
            'LI' => __('Liechtenstein'),
            'LT' => __('Lithuania'),
            'LU' => __('Luxembourg'),
            'MO' => __('Macao'),
            'MK' => __('Macedonia'),
            'MG' => __('Madagascar'),
            'MW' => __('Malawi'),
            'MY' => __('Malaysia'),
            'MV' => __('Maldives'),
            'ML' => __('Mali'),
            'MT' => __('Malta'),
            'MH' => __('Marshall Islands'),
            'MQ' => __('Martinique'),
            'MR' => __('Mauritania'),
            'MU' => __('Mauritius'),
            'YT' => __('Mayotte'),
            'MX' => __('Mexico'),
            'FM' => __('Micronesia, Federated States Of'),
            'MD' => __('Moldova'),
            'MC' => __('Monaco'),
            'MN' => __('Mongolia'),
            'ME' => __('Montenegro'),
            'MS' => __('Montserrat'),
            'MA' => __('Morocco'),
            'MZ' => __('Mozambique'),
            'MM' => __('Myanmar'),
            'NA' => __('Namibia'),
            'NR' => __('Nauru'),
            'NP' => __('Nepal'),
            'NL' => __('Netherlands'),
            'AN' => __('Netherlands Antilles'),
            'NC' => __('New Caledonia'),
            'NZ' => __('New Zealand'),
            'NI' => __('Nicaragua'),
            'NE' => __('Niger'),
            'NG' => __('Nigeria'),
            'NU' => __('Niue'),
            'NF' => __('Norfolk Island'),
            'MP' => __('Northern Mariana Islands'),
            'NO' => __('Norway'),
            'OM' => __('Oman'),
            'PK' => __('Pakistan'),
            'PW' => __('Palau'),
            'PS' => __('Palestinian Territory, Occupied'),
            'PA' => __('Panama'),
            'PG' => __('Papua New Guinea'),
            'PY' => __('Paraguay'),
            'PE' => __('Peru'),
            'PH' => __('Philippines'),
            'PN' => __('Pitcairn'),
            'PL' => __('Poland'),
            'PT' => __('Portugal'),
            'PR' => __('Puerto Rico'),
            'QA' => __('Qatar'),
            'RE' => __('Reunion'),
            'RO' => __('Romania'),
            'RU' => __('Russian Federation'),
            'RW' => __('Rwanda'),
            'BL' => __('Saint Barthelemy'),
            'SH' => __('Saint Helena'),
            'KN' => __('Saint Kitts And Nevis'),
            'LC' => __('Saint Lucia'),
            'MF' => __('Saint Martin'),
            'PM' => __('Saint Pierre And Miquelon'),
            'VC' => __('Saint Vincent And Grenadines'),
            'WS' => __('Samoa'),
            'SM' => __('San Marino'),
            'ST' => __('Sao Tome And Principe'),
            'SA' => __('Saudi Arabia'),
            'SN' => __('Senegal'),
            'RS' => __('Serbia'),
            'SC' => __('Seychelles'),
            'SL' => __('Sierra Leone'),
            'SG' => __('Singapore'),
            'SK' => __('Slovakia'),
            'SI' => __('Slovenia'),
            'SB' => __('Solomon Islands'),
            'SO' => __('Somalia'),
            'ZA' => __('South Africa'),
            'GS' => __('South Georgia And Sandwich Isl.'),
            'ES' => __('Spain'),
            'LK' => __('Sri Lanka'),
            'SD' => __('Sudan'),
            'SR' => __('Suriname'),
            'SJ' => __('Svalbard And Jan Mayen'),
            'SZ' => __('Swaziland'),
            'SE' => __('Sweden'),
            'CH' => __('Switzerland'),
            'SY' => __('Syrian Arab Republic'),
            'TW' => __('Taiwan'),
            'TJ' => __('Tajikistan'),
            'TZ' => __('Tanzania'),
            'TH' => __('Thailand'),
            'TL' => __('Timor-Leste'),
            'TG' => __('Togo'),
            'TK' => __('Tokelau'),
            'TO' => __('Tonga'),
            'TT' => __('Trinidad And Tobago'),
            'TN' => __('Tunisia'),
            'TR' => __('Turkey'),
            'TM' => __('Turkmenistan'),
            'TC' => __('Turks And Caicos Islands'),
            'TV' => __('Tuvalu'),
            'UG' => __('Uganda'),
            'UA' => __('Ukraine'),
            'AE' => __('United Arab Emirates'),
            'GB' => __('United Kingdom'),
            'US' => __('United States'),
            'UM' => __('United States Outlying Islands'),
            'UY' => __('Uruguay'),
            'UZ' => __('Uzbekistan'),
            'VU' => __('Vanuatu'),
            'VE' => __('Venezuela'),
            'VN' => __('Viet Nam'),
            'VG' => __('Virgin Islands, British'),
            'VI' => __('Virgin Islands, U.S.'),
            'WF' => __('Wallis And Futuna'),
            'EH' => __('Western Sahara'),
            'YE' => __('Yemen'),
            'ZM' => __('Zambia'),
            'ZW' => __('Zimbabwe')
        );
    }

}