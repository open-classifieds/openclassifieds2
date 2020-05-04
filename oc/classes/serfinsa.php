<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Serfinsa helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2016 Open Classifieds Team
 * @license    GPL v3
 */

class Serfinsa {
    /**
     * generates HTML for apy buton
     * @return string
     */
    public static function button(Model_Order $order)
    {
        if(Core::config('payment.serfinsa_token') != '' AND Core::extra_features() == TRUE AND $order->loaded())
        {
            return View::factory('pages/serfinsa/button', compact('order'));
        }

        return '';
    }

}
