<?php defined('SYSPATH') or die('No direct script access.');

/**
 * paguelofacil helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class paguelofacil {
    
    const url_gateway            = 'https://secure.paguelofacil.com/LinkDeamon.cfm?';
    const url_sandbox_gateway    = 'https://dev.paguelofacil.com/LinkDeamon.cfm?';

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.paguelofacil_cclw') != '' AND Core::extra_features() == TRUE)
        {
            $cclw = Core::config('payment.paguelofacil_cclw'); // customer code

            // order properties
            $id_order = $order->id_order;                // invoice number 
            $cdsc     = URL::title($order->description); // invoice desc
            $cmtn     = $order->amount;                  // invoice amount

            // build URL
            $url = ( ( Core::config('payment.paguelofacil_testing') == 1 ) ? self::url_sandbox_gateway : self::url_gateway);
            $url.= "CCLW=$cclw&CMTN=$cmtn&CDSC=$cdsc&id_order=$id_order";

            return View::factory('pages/paguelofacil/button', array('url' => $url));
        }

        return '';
    }


    public static function check_result()
    {
        if (Request::current()->param('id') == Core::config('general.api_key') AND Core::request('Estado') == 'Aprobada')
            return TRUE;

        return FALSE;
    }

}