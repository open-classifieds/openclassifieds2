<?php defined('SYSPATH') or die('No direct script access.');

/**
 * zenith helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class zenith {

    const url_gateway            = 'https://www.globalpay.com.ng/GlobalPayAPI/Paymentgatewaycapture.aspx';
    const url_sandbox_gateway    = 'https://demo.globalpay.com.ng/GlobalPayAPI/Paymentgatewaycapture.aspx';

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order
     * @return string
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.zenith_merchantid') != '' AND Core::config('payment.zenith_uid') != '' AND Core::config('payment.zenith_pwd') != '' AND Core::extra_features() == TRUE)
        {
            $merchantid = Core::config('payment.zenith_merchantid'); // customer code
            $uid = Core::config('payment.zenith_uid'); // customer code
            $pwd = Core::config('payment.zenith_pwd'); // customer code

            // URL
            $url = ( ( Core::config('payment.zenith_testing') == 1 ) ? self::url_sandbox_gateway : self::url_gateway);

            return View::factory('pages/zenith/button', array('url' => $url, 'merchantid' => $merchantid, 'uid' => $uid, 'pwd' => $pwd, 'order' => $order));
        }

        return '';
    }


    public static function check_result(Model_Order $order)
    {
        ini_set ('soap.wsdl_cache_enabled', 0);
        require Kohana::find_file('vendor/zenith', 'nusoap');

        if (Core::config('payment.zenith_testing'))
        {
            $endpoint = 'https://demo.globalpay.com.ng/GlobalpayWebService_demo/service.asmx?wsdl';
            $namespace = 'https://www.eazypaynigeria.com/globalpay_demo/';
            $soap_action = 'https://www.eazypaynigeria.com/globalpay_demo/getTransactions';
        }
        else
        {
            $endpoint = 'https://www.globalpay.com.ng/globalpaywebservice/service.asmx?wsdl';
            $namespace = 'https://www.eazypaynigeria.com/globalpay/';
            $soap_action = 'https://www.eazypaynigeria.com/globalpay/getTransactions';
        }

        $client = new nusoap_client($endpoint, TRUE);

        $client->soap_defencoding = 'UTF-8';

  	    $txnref = Core::request('txnref');

        $merch_txnref = $txnref;
        $channel = "";

        //change the merchantid to the one sent to you
        $merchantID = Core::config('payment.zenith_merchantid');
        $start_date = "";
        $end_date = "";

        //change the uid and pwd to the one sent to you
        $uid = Core::config('payment.zenith_uid');
        $pwd = Core::config('payment.zenith_pwd');
        $payment_status = "";

        $err = $client->getError();

        if($err)
        {
            d('<h2>Constructor error</h2><pre>' . $err . '</pre>');
            return FALSE;
        }

        // Doc/lit parameters get wrapped
        $MethodToCall= "getTransactions";

        $param = array(
            'merch_txnref' => $merch_txnref,
            'channel' => $channel,
            'merchantID' => $merchantID,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'uid' => $uid,
            'pwd' => $pwd,
            'payment_status' => $payment_status
        );

        $result = $client->call(
            'getTransactions',
            array('parameters' => $param),
            'http://www.eazypaynigeria.com/globalpay_demo/',
            'http://www.eazypaynigeria.com/globalpay_demo/getTransactions',
            FALSE,
            TRUE
        );

        // Check for a fault
        if ($client->fault)
        {
            d($result);
            return FALSE;
        }
        else
        {
            // Check for errors
            $err = $client->getError();

            if ($err)
            {
                // Display the error
                d('<h2>Error</h2><pre>' . $err . '</pre>');
                return FALSE;
            }
            else
            {
                //This gives getTransactionsResult
                $WebResult = $MethodToCall . "Result";

                // Pass the result into XML
                $xml = simplexml_load_string($result[$WebResult]);

                $amount = $xml->record->amount;
                $txn_date = $xml->record->payment_date;
                $pmt_method = $xml->record->channel;
                $pmt_status = $xml->record->payment_status;
                $pmt_txnref = $xml->record->txnref;
                $currency = $xml->record->field_values->field_values->field[2]->currency;
                $trans_status = $xml->record->payment_status_description;
            }

            $result = [];

            $result['merchant_name'] = "Name : ". Core::config('payment.zenith_merchant_name');
	        $result['merchant_phone'] = "Phone number : ". Core::config('payment.zenith_merchant_phone');

            if ($pmt_status == 'successful')
            {
         	    if ($amount == $order->amount)
         	    {
                    $result['amount'] = "Amount: ". $amount;
                    $result['transaction_date'] = "Transaction Date: ".$txn_date;
                    $result['payment_method'] = "Payment Method: ".$pmt_method;
                    $result['payment_status'] = "Payment Status: ".$pmt_status;
                    $result['transaction_reference_number'] = "Transaction Reference Number: ".$pmt_txnref;
                    $result['currency'] = "Currency: ".$currency;
                    $result['transaction_status'] = "Transaction Status: ".$trans_status;
         	    }
                else
                {
                    $result['transaction_amount'] = "Transaction Amount: ".$merch_amt;
                    $result['debited_amount'] = "Debited Amount: ". $amount ;
                    $result['transaction_ate'] = "Transaction Date: ".$txn_date;
                    $result['payment_method'] = "Payment Method: ".$pmt_method;
                    $result['payment_status'] = "Payment Status: ".$pmt_status. " ( Amount does not match and no service will be rendered)";
                    $result['transaction_reference_number'] = "Transaction Reference Number: ".$pmt_txnref;
                    $result['currency'] = "Currency: ".$currency;
                    $result['transaction_status'] = "Transaction Status: ".$trans_status;
                }

            }
            else
            {
         	   if ($pmt_status == 'pending')
         	   {
         			//Please display a message telling the user to check back at a later time.
         	   	    //You should have a background agent that will query globalpay web service intermittently to update the transactions on your site.
         	   		$result['amount'] = "Amount : ". $amount ;
		            $result['transaction_date'] = "Transaction Date : ".$txn_date;
		            $result['payment_method'] = "Payment Method : ".$pmt_method;
		            $result['payment_tatus'] = "Payment Status : ".$pmt_status;
		            $result['transaction_reference_number'] = "Transaction Reference Number : ".$pmt_txnref;
		            $result['currency'] = "Currency : ".$currency;
		            $result['transaction_status'] = "Transaction Status : ".$trans_status;
         	   }
         	   else
         	   {
	         	   	$result['amount'] = "Amount : ". $amount ;
		            $result['transaction_date'] = "Transaction Date : ".$txn_date;
		            $result['payment_method'] = "Payment Method : ".$pmt_method;
		            $result['payment_tatus'] = "Payment Status : ".$pmt_status;
		            $result['transaction_reference_number'] = "Transaction Reference Number : ".$pmt_txnref;
		            $result['currency'] = "Currency : ".$currency;
		            $result['transaction_status'] = "Transaction Status : ".$trans_status;
         	   }
            }

            return $result;
        }
    }

}