<?php defined('SYSPATH') or die('No direct script access.');

 /**
  * yclpay helper class
  *
  * @package    OC
  * @category   Payment
  * @author     Oliver <oliver@open-classifieds.com>
  * @copyright  (c) 2009-2015 Open Classifieds Team
  * @license    GPL v3
  */

 class yclpay {

     /**
      * generates HTML for pay button
      * @param  Model_Order $order
      * @return string
      */
     public static function button(Model_Order $order)
     {
         return View::factory('pages/yclpay/button', ['order' => $order]);
     }
 }
