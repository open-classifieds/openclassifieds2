<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Paytabs helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

@session_start();
define("TESTING", "https://localhost:8888/paytabs/apiv2/index");
define("AUTHENTICATION", "https://www.paytabs.com/apiv2/validate_secret_key");
define("PAYPAGE_URL", "https://www.paytabs.com/apiv2/create_pay_page");
define("VERIFY_URL", "https://www.paytabs.com/apiv2/verify_payment");

class paytabs {

    private $merchant_id;
    private $secret_key;

    function __construct() {
        $this->merchant_email = Core::config('payment.paytabs_merchant_email');
        $this->secret_key = Core::config('payment.paytabs_secret_key');
        $this->api_key = "";
    }
    
    function authentication(){
        $obj = json_decode($this->runPost(AUTHENTICATION, array("merchant_email"=> $this->merchant_email, "secret_key"=>  $this->secret_key)));
        if($obj->access == "granted")
            $this->api_key = $obj->api_key;
        else 
            $this->api_key = "";
        return $this->api_key;
    }
    
    function create_pay_page($values) {
        $values['merchant_email'] = $this->merchant_email;
        $values['secret_key'] = $this->secret_key;
        $values['ip_customer'] = $_SERVER['REMOTE_ADDR'];
        $values['ip_merchant'] = $_SERVER['SERVER_ADDR'];
        return json_decode($this->runPost(PAYPAGE_URL, $values));
    }
    
    function send_request(){
        $values['ip_customer'] = $_SERVER['REMOTE_ADDR'];
        $values['ip_merchant'] = $_SERVER['SERVER_ADDR'];
        return json_decode($this->runPost(TESTING, $values));
    }
    
    function verify_payment($payment_reference){
        $values['merchant_email'] = $this->merchant_email;
        $values['secret_key'] = $this->secret_key;
        $values['payment_reference'] = $payment_reference;
        return json_decode($this->runPost(VERIFY_URL, $values));
    }
    
    function runPost($url, $fields) {
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();
        $ip = $_SERVER['REMOTE_ADDR'];

        $ip_address = array(
            "REMOTE_ADDR" => $ip,
            "HTTP_X_FORWARDED_FOR" => $ip
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $ip_address);
        curl_setopt($ch, CURLOPT_POST, core::count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.paytabs_merchant_email')!='' AND Core::config('payment.paytabs_secret_key')!='' AND Core::extra_features() == TRUE)
        {
            // PayTabs Merchant Account Details
            $pt = new paytabs();

            // order properties
            $inv_id    = $order->id_order;        // shop's invoice number 
            $inv_desc  = $order->description;   // invoice desc
            $out_summ  = $order->amount;   // invoice summ

            $result = $pt->create_pay_page(array(
                'title' => $order->user->name,
                'cc_first_name' => isset($order->user->cf_first_name) ? $order->user->cf_first_name : $order->user->name,
                'cc_last_name' => isset($order->user->cf_last_name) ? $order->user->cf_last_name : $order->user->name,
                'email' => $order->user->email,
                'cc_phone_number' => isset($order->user->cf_cc_phone_number) ? $order->user->cf_cc_phone_number : "973",
                'phone_number' => isset($order->user->cf_phone_number) ? $order->user->cf_phone_number : "33333333",
                
                'billing_address' => isset($order->user->cf_billing_address) ? $order->user->cf_billing_address : "-",
                'city' => isset($order->user->cf_city) ? $order->user->cf_city : "-",
                'state' => isset($order->user->cf_state) ? $order->user->cf_state : "-",
                'postal_code' => isset($order->user->cf_postal_code) ? $order->user->cf_postal_code : "-",
                'country' => isset($order->user->cf_country) ? $order->user->cf_country : "BHR",
                               
                'address_shipping' => isset($order->user->cf_billing_address) ? $order->user->cf_billing_address : "-",
                'city_shipping' => isset($order->user->cf_city) ? $order->user->cf_city : "-",
                'state_shipping' => isset($order->user->cf_state) ? $order->user->cf_state : "-",
                'postal_code_shipping' => isset($order->user->cf_postal_code) ? $order->user->cf_postal_code : "-",
                'country_shipping' => isset($order->user->cf_country) ? $order->user->cf_country : "BHR",
               
                "products_per_title" => $order->description,
                'currency' => core::config('payment.paypal_currency'),
                "unit_price"=> $order->amount,
                'quantity' => "1",
                'other_charges' => "0",
                'amount' => $order->amount,
                'discount'=> "0",
                "msg_lang" => "english",
                
                "reference_no" => $order->id_order,
                "site_url" => Route::url('default'),
                'return_url' => Route::url('default',array('controller'=>'paytabs','action'=>'result','id'=>$order->id_order)),
                "cms_with_version" => "API using Yclas"
            ));

            if (! empty($result->payment_url))
                return View::factory('pages/paytabs/button',array('url'=>$result->payment_url));
            else
                Alert::set(Alert::WARNING, $result->result);
        }

        return '';
    }
}