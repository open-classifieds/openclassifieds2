<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Paymill helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class Paymill {
    

    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount 
     * @return string         
     */
    public static function money_format($amount)
    {
        return round($amount,2)*100;
    }

    /**
     *   NOTE This will  never be exactly since stripe has variable pricing
     */
    public static function calculate_fee($amount)
    {   
        //variables
        $fee            = 2.95;
        $fee_trans      = 0.28;//USD

        //initial exchange fee + stripe fee
        return ($fee * $amount / 100) + $fee_trans;
    }

    /**
     * hack for paymill, requires the jquery to be loaded in the header...sucks...
     * @return void                 
     */
    public static function jquery()
    {
        if ( Core::config('payment.paymill_private')!='' AND 
            Core::config('payment.paymill_public')!='' AND 
            Core::extra_features() == TRUE)
        {
            foreach (Theme::$scripts['footer'] as $key=>$js)
            {
                if (strpos($js,'jquery.min.js')>0 OR strpos($js,'jquery-1.10.2.js')>0 OR strpos($js,'jquery-1.10.2.min.js')>0)
                {
                    unset(Theme::$scripts['footer'][$key]);
                    Theme::$scripts['header'][] = $js;
                    break;
                }
            }
        }
    }

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if ( Core::config('payment.paymill_private')!='' AND 
            Core::config('payment.paymill_public')!='' AND 
            Core::extra_features() == TRUE AND
            $order->loaded())
        {
            return View::factory('pages/paymill/button',array('order'=>$order));
        }

        return '';
    }

    //
    //
    //Functions from https://github.com/paymill/paybutton-examples
    //
    //

    /**
     * Perform HTTP request to REST endpoint
     *
     * @param string $action
     * @param array  $params
     * @param string $privateApiKey
     *
     * @return array
     */
    public static function requestApi( $action = '', $params = array(), $privateApiKey )
    {
        $curlOpts = array(
            CURLOPT_URL            => "https://api.paymill.com/v2/" . $action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_USERAGENT      => 'Paymill-php/0.0.2',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO         =>  APPPATH.'config/paymill.crt',
        );

        $curlOpts[ CURLOPT_POSTFIELDS ] = http_build_query( $params, null, '&' );
        $curlOpts[ CURLOPT_USERPWD ] = $privateApiKey . ':';

        $curl = curl_init();
        curl_setopt_array( $curl, $curlOpts );
        $responseBody = curl_exec( $curl );
        $responseInfo = curl_getinfo( $curl );
        if ( $responseBody === false ) {
            $responseBody = array( 'error' => curl_error( $curl ) );
        }
        curl_close( $curl );

        if ( 'application/json' === $responseInfo[ 'content_type' ] ) {
            $responseBody = json_decode( $responseBody, true );
        }

        return array(
            'header' => array(
                'status' => $responseInfo[ 'http_code' ],
                'reason' => null,
            ),
            'body'   => $responseBody
        );
    }

    /**
     * Perform API and handle exceptions
     *
     * @param        $action
     * @param array  $params
     * @param string $privateApiKey
     *
     * @return mixed
     */
    public static function request( $action, $params = array(), $privateApiKey )
    {
        if ( !is_array( $params ) ) {
            $params = array();
        }

        $responseArray = self::requestApi( $action, $params, $privateApiKey );
        $httpStatusCode = $responseArray[ 'header' ][ 'status' ];
        if ( $httpStatusCode != 200 ) {
            $errorMessage = 'Client returned HTTP status code ' . $httpStatusCode;
            if ( isset( $responseArray[ 'body' ][ 'error' ] ) ) {
                $errorMessage = $responseArray[ 'body' ][ 'error' ];
            }
            $responseCode = '';
            if ( isset( $responseArray[ 'body' ][ 'response_code' ] ) ) {
                $responseCode = $responseArray[ 'body' ][ 'response_code' ];
            }

            return array( "data" => array(
                "error"            => $errorMessage,
                "response_code"    => $responseCode,
                "http_status_code" => $httpStatusCode
            ) );
        }

        return $responseArray[ 'body' ][ 'data' ];
    }

}