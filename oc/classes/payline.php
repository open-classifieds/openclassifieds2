<?php defined('SYSPATH') or die('No direct script access.');

/**
 * payline helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class payline {

    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount
     * @return string
     */
    public static function money_format($amount)
    {
        return (int) round($amount, 2) * 100;
    }

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order
     * @return string
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.payline_merchant_id') != '' AND Core::config('payment.payline_access_key') != '' AND Core::config('payment.payline_contract_number') != '' AND Core::extra_features() == TRUE)
        {
            require_once Kohana::find_file('vendor', 'payline/autoload', 'php');

            $payline = new \Payline\PaylineSDK(
                Core::config('payment.payline_merchant_id'),
                Core::config('payment.payline_access_key'),
                '',
                '',
                '',
                '',
                Core::config('payment.payline_testing') == 1 ? 'HOMO' : 'PRO',
                '',
                ''
            );
            $payline->usedBy("yclas");

            $request['payment']['amount'] = self::money_format($order->amount);
            $request['payment']['currency'] = self::get_currency_iso_code($order->currency);
            $request['payment']['action'] = 101;
            $request['payment']['mode'] = 'CPT';
            $request['order']['ref'] = $order->id_order;
            $request['order']['amount'] = $request['payment']['amount'];
            $request['order']['currency'] = $request['payment']['currency'];
            $request['order']['date'] = date('d/m/Y H:i');
            $request['payment']['contractNumber'] = Core::config('payment.payline_contract_number');
            $request['contracts'] = [$request['payment']['contractNumber']];
            $request['cancelURL'] = Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]);
            $request['returnURL'] = Route::url('default', ['controller'=>'payline', 'action'=>'result','id'=> $order->id_order]);

            $response = $payline->doWebPayment($request);

            if (isset($response) AND $response['result']['code'] == '00000')
            {
                return View::factory('pages/payline/button', ['url' => $response['redirectURL']]);
            }

            if (isset($response))
            {
                Alert::set(Alert::ERROR, 'ERROR : ' . $response['result']['code'] . ' ' . $response['result']['longMessage']);
            }
        }

        return '';
    }


    public static function check_result(Model_Order $order)
    {
        $token = Core::request('token');

        if (empty($token))
            return FALSE;

        require_once Kohana::find_file('vendor', 'payline/autoload', 'php');

        $payline = new \Payline\PaylineSDK(
            Core::config('payment.payline_merchant_id'),
            Core::config('payment.payline_access_key'),
            '',
            '',
            '',
            '',
            Core::config('payment.payline_testing') == 1 ? 'HOMO' : 'PRO',
            '',
            ''
        );

        $request['token'] = $token;
        $request['version'] = 16;

        $result = $payline->getWebPaymentDetails($request);

        if (isset($result['transaction']['id']) AND
            $result['result']['code'] == '00000' AND
            $result['payment']['amount'] == self::money_format($order->amount))
        {
            return TRUE;
        }

        return FALSE;
    }

    public static function get_currency_iso_code($currecy)
    {
        $currencies = [
            'AFA' => ['Afghan Afghani', '971'],
            'AWG' => ['Aruban Florin', '533'],
            'AUD' => ['Australian Dollars', '036'],
            'ARS' => ['Argentine Pes', '032'],
            'AZN' => ['Azerbaijanian Manat', '944'],
            'BSD' => ['Bahamian Dollar', '044'],
            'BDT' => ['Bangladeshi Taka', '050'],
            'BBD' => ['Barbados Dollar', '052'],
            'BYR' => ['Belarussian Rouble', '974'],
            'BOB' => ['Bolivian Boliviano', '068'],
            'BRL' => ['Brazilian Real', '986'],
            'GBP' => ['British Pounds Sterling', '826'],
            'BGN' => ['Bulgarian Lev', '975'],
            'KHR' => ['Cambodia Riel', '116'],
            'CAD' => ['Canadian Dollars', '124'],
            'KYD' => ['Cayman Islands Dollar', '136'],
            'CLP' => ['Chilean Peso', '152'],
            'CNY' => ['Chinese Renminbi Yuan', '156'],
            'COP' => ['Colombian Peso', '170'],
            'CRC' => ['Costa Rican Colon', '188'],
            'HRK' => ['Croatia Kuna', '191'],
            'CPY' => ['Cypriot Pounds', '196'],
            'CZK' => ['Czech Koruna', '203'],
            'DKK' => ['Danish Krone', '208'],
            'DOP' => ['Dominican Republic Peso', '214'],
            'XCD' => ['East Caribbean Dollar', '951'],
            'EGP' => ['Egyptian Pound', '818'],
            'ERN' => ['Eritrean Nakfa', '232'],
            'EEK' => ['Estonia Kroon', '233'],
            'EUR' => ['Euro', '978'],
            'GEL' => ['Georgian Lari', '981'],
            'GHC' => ['Ghana Cedi', '288'],
            'GIP' => ['Gibraltar Pound', '292'],
            'GTQ' => ['Guatemala Quetzal', '320'],
            'HNL' => ['Honduras Lempira', '340'],
            'HKD' => ['Hong Kong Dollars', '344'],
            'HUF' => ['Hungary Forint', '348'],
            'ISK' => ['Icelandic Krona', '352'],
            'INR' => ['Indian Rupee', '356'],
            'IDR' => ['Indonesia Rupiah', '360'],
            'ILS' => ['Israel Shekel', '376'],
            'JMD' => ['Jamaican Dollar', '388'],
            'JPY' => ['Japanese yen', '392'],
            'KZT' => ['Kazakhstan Tenge', '368'],
            'KES' => ['Kenyan Shilling', '404'],
            'KWD' => ['Kuwaiti Dinar', '414'],
            'LVL' => ['Latvia Lat', '428'],
            'LBP' => ['Lebanese Pound', '422'],
            'LTL' => ['Lithuania Litas', '440'],
            'MOP' => ['Macau Pataca', '446'],
            'MKD' => ['Macedonian Denar', '807'],
            'MGA' => ['Malagascy Ariary', '969'],
            'MYR' => ['Malaysian Ringgit', '458'],
            'MTL' => ['Maltese Lira', '470'],
            'BAM' => ['Marka', '977'],
            'MUR' => ['Mauritius Rupee', '480'],
            'MXN' => ['Mexican Pesos', '484'],
            'MZM' => ['Mozambique Metical', '508'],
            'NPR' => ['Nepalese Rupee', '524'],
            'ANG' => ['Netherlands Antilles Guilder', '532'],
            'TWD' => ['New Taiwanese Dollars', '901'],
            'NZD' => ['New Zealand Dollars', '554'],
            'NIO' => ['Nicaragua Cordoba', '558'],
            'NGN' => ['Nigeria Naira', '566'],
            'KPW' => ['North Korean Won', '408'],
            'NOK' => ['Norwegian Krone', '578'],
            'OMR' => ['Omani Riyal', '512'],
            'PKR' => ['Pakistani Rupee', '586'],
            'PYG' => ['Paraguay Guarani', '600'],
            'PEN' => ['Peru New Sol', '604'],
            'PHP' => ['Philippine Pesos', '608'],
            'QAR' => ['Qatari Riyal', '634'],
            'RON' => ['Romanian New Leu', '946'],
            'RUB' => ['Russian Federation Ruble', '643'],
            'SAR' => ['Saudi Riyal', '682'],
            'CSD' => ['Serbian Dinar', '891'],
            'SCR' => ['Seychelles Rupee', '690'],
            'SGD' => ['Singapore Dollars', '702'],
            'SKK' => ['Slovak Koruna', '703'],
            'SIT' => ['Slovenia Tolar', '705'],
            'ZAR' => ['South African Rand', '710'],
            'KRW' => ['South Korean Won', '410'],
            'LKR' => ['Sri Lankan Rupee', '144'],
            'SRD' => ['Surinam Dollar', '968'],
            'SEK' => ['Swedish Krona', '752'],
            'CHF' => ['Swiss Francs', '756'],
            'TZS' => ['Tanzanian Shilling', '834'],
            'THB' => ['Thai Baht', '764'],
            'TTD' => ['Trinidad and Tobago Dollar', '780'],
            'TRY' => ['Turkish New Lira', '949'],
            'AED' => ['UAE Dirham', '784'],
            'USD' => ['US Dollars', '840'],
            'UGX' => ['Ugandian Shilling', '800'],
            'UAH' => ['Ukraine Hryvna', '980'],
            'UYU' => ['Uruguayan Peso', '858'],
            'UZS' => ['Uzbekistani Som', '860'],
            'VEB' => ['Venezuela Bolivar', '862'],
            'VND' => ['Vietnam Dong', '704'],
            'AMK' => ['Zambian Kwacha', '894'],
            'ZWD' => ['Zimbabwe Dollar', '716'],
        ];

        if (isset($currencies[$currecy]))
            return $currencies[$currecy][1];

        return NULL;
    }

}