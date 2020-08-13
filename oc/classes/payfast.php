<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Payfast helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class payfast {

    const url_sandbox_gateway = 'https://sandbox.payfast.co.za/eng/process';
    const url_gateway         = 'https://www.payfast.co.za/eng/process';

    const ipn_sandbox_url       = 'https://sandbox.payfast.co.za/eng/query/validate';
    const ipn_url               = 'https://www.payfast.co.za/eng/query/validate';

    public static function validate_itn()
    {
        // Notify PayFast that information has been received
        header( 'HTTP/1.0 200 OK' );
        flush();

        // Variable initialization
        $error = FALSE;
        $err_msg = '';
        $output = '';
        $param_string = '';
        $host = (Core::config('payment.payfast_sandbox') == 1) ? self::ipn_sandbox_url : self::ipn_url;

        if (! $error)
        {
            $output = "Posted Variables:\n\n"; // DEBUG

            // Strip any slashes in data
            foreach ($_POST as $key => $val)
                $data[$key] = stripslashes($val);

            // Dump the submitted variables and calculate security signature
            foreach ($data as $key => $val)
            {
               if ($key != 'signature')
                 $param_string .= $key .'='. urlencode($val) .'&';
            }

            // Remove the last '&' from the parameter string
            $param_string = substr($param_string, 0, -1);
            $temp_param_string = $param_string;

            // If a passphrase has been set in the PayFast Settings, then it needs to be included in the signature string.
            $pass_phrase = ''; //You need to get this from a constant or stored in you website
            if (! empty($pass_phrase))
            {
                $temp_param_string .= '&passphrase='.urlencode( $pass_phrase );
            }
            $signature = md5($temp_param_string);

            $result = ($_POST['signature'] == $signature);

            $output .= "Security Signature:\n\n"; // DEBUG
            $output .= "- posted     = ". $_POST['signature'] ."\n"; // DEBUG
            $output .= "- calculated = ". $signature ."\n"; // DEBUG
            $output .= "- result     = ". ( $result ? 'SUCCESS' : 'FAILURE' ) ."\n"; // DEBUG
        }

        //// Verify source IP
        if (! $error)
        {
            $valid_hosts = array(
                'www.payfast.co.za',
                'sandbox.payfast.co.za',
                'w1w.payfast.co.za',
                'w2w.payfast.co.za',
                );

            $valid_ips = array();

            foreach ($valid_hosts as $hostname)
            {
                $ips = gethostbynamel( $hostname );

                if ($ips !== FALSE)
                    $valid_ips = array_merge( $valid_ips, $ips );
            }

            // Remove duplicates
            $valid_ips = array_unique( $valid_ips );

            if (! in_array( $_SERVER['REMOTE_ADDR'], $valid_ips ))
            {
                $error = TRUE;
                $err_msg = 'Bad source IP address';
            }
        }

        //// Connect to server to validate data received
        if (! $error)
        {
            // Use cURL (If it's available)
            if (function_exists('curl_init'))
            {
                $output .= "\n\nUsing cURL\n\n"; // DEBUG

                // Create default cURL object
                $ch = curl_init();

                // Base settings
                $curl_opts = array(
                    // Base options
                    CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)', // Set user agent
                    CURLOPT_RETURNTRANSFER => TRUE,  // Return output as string rather than outputting it
                    CURLOPT_HEADER => FALSE,         // Don't include header in output
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_SSL_VERIFYPEER => FALSE,

                    // Standard settings
                    CURLOPT_URL => $host,
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => $param_string,
                );
                curl_setopt_array( $ch, $curl_opts );

                // Execute CURL
                $res = curl_exec( $ch );
                curl_close( $ch );

                if ($res === FALSE)
                {
                    $error = TRUE;
                    $err_msg = 'An error occurred executing cURL';
                }
            }
            // Use fsockopen
            else
            {
                $output .= "\n\nUsing fsockopen\n\n"; // DEBUG

                // Construct Header
                $header = "POST /eng/query/validate HTTP/1.0\r\n";
                $header .= "Host: ". $host ."\r\n";
                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $header .= "Content-Length: " . strlen($param_string) . "\r\n\r\n";

                // Connect to server
                $socket = fsockopen('ssl://'. $host, 443, $errno, $errstr, 10);

                // Send command to server
                fputs( $socket, $header . $param_string );

                // Read the response from the server
                $res = '';
                $header_done = FALSE;

                while(! feof($socket ))
                {
                    $line = fgets($socket, 1024);

                    // Check if we are finished reading the header yet
                    if (strcmp($line, "\r\n") == 0)
                    {
                        // read the header
                        $header_done = TRUE;
                    }
                    // If header has been processed
                    elseif ($header_done)
                    {
                        // Read the main response
                        $res .= $line;
                    }
                }
            }
        }

        //// Get data from server
        if (! $error)
        {
            // Parse the returned data
            $lines = explode("\n", $res);

            $output .= "\n\nValidate response from server:\n\n"; // DEBUG

            foreach ($lines as $line) // DEBUG
                $output .= $line ."\n"; // DEBUG
        }

        //// Interpret the response from server
        if (! $error)
        {
            // Get the response from PayFast (VALID or INVALID)
            $result = trim($lines[0]);

            $output .= "\nResult = ". $result; // DEBUG

            // If the transaction was valid
            if (strcmp($result, 'VALID') == 0)
            {
                return TRUE;
            }
            // If the transaction was NOT valid
            else
            {
                // Log for investigation
                $error = TRUE;
                $err_msg = 'The data received is invalid';
            }
        }

        // If an error occurred
        if ($error)
        {
            $output .= "\n\nAn error occurred!";
            $output .= "\nError = ". $err_msg;
            Kohana::$log->add(Log::ERROR, $output);
            return FALSE;
        }
    }

     /**
     * generates HTML for pay button
     * @param  Model_Order $order
     * @return string
     */
    public static function form(Model_Order $order)
    {
        if ( Core::config('payment.payfast_merchant_id')!='' AND Core::config('payment.payfast_merchant_key')!='' AND Core::extra_features() == TRUE)
        {
            $form_action = ( Core::config('payment.payfast_sandbox') == 1) ? self::url_sandbox_gateway : self::url_gateway;

            $info = ['merchant_id' => Core::config('payment.payfast_merchant_id'),
                'merchant_key' => Core::config('payment.payfast_merchant_key'),
                'return_url' => URL::base(TRUE),
                'cancel_url' => Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)),
                'notify_url' => Route::url('default',array('controller'=>'payfast', 'action'=>'itn','id'=>'1')),
                'm_payment_id' => $order->id_order,
                'amount' => number_format($order->amount, 2, '.', ''),
                'item_name' => $order->description,
                'item_description' => $order->description
            ];

            // Create output string
            $payfast_output = '';

            foreach ($info as $key => $value)
                $payfast_output .= $key .'='. urlencode(trim($value)) . '&';

            $payfast_output = substr($payfast_output, 0, -1);

            $info['signature'] = md5($payfast_output);

            return View::factory('pages/payfast/form', array('order' => $order, 'form_action' => $form_action, 'info' => $info));
        }

        return '';
    }
}
