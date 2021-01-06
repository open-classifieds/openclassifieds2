<?php defined('SYSPATH') or die('No direct script access.');

/**
* paypal class
*
* @package Open Classifieds
* @subpackage Core
* @category Payment
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_Paypal extends Controller{


	public function after()
	{

	}

	public function action_ipn()
	{
        //todo delete
        //paypal::validate_ipn();

		$this->auto_render = FALSE;

		//START PAYPAL IPN
		//manual checks
		$id_order         = Core::request('item_number');
		$paypal_amount    = Core::request('mc_gross');
		$payer_id         = Core::request('payer_id');

		//retrieve info for the item in DB
		$order = new Model_Order();
		$order = $order->where('id_order', '=', $id_order)
					   ->where('status', '=', Model_Order::STATUS_CREATED)
					   ->limit(1)->find();

		if($order->loaded())
		{

            //order is from a payment done to the owner of the ad
            if (in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM]))
            {
                $paypal_account = $order->ad->paypal_account();

                $receiver_correct = (Core::request('receiver_email') == $paypal_account  OR Core::request('business')  == $paypal_account);
            }
            //any other payment goes to classifieds site payment
            else
            {
                $receiver_correct = (Core::request('receiver_email') == core::config('payment.paypal_account')  OR Core::request('business')  == core::config('payment.paypal_account'));
            }

            //same amount and same currency
			if ( Core::request('payment_status')   == 'Completed'
                AND  Core::request('mc_gross')     == number_format($order->amount, 2, '.', '')
				AND  Core::request('mc_currency')  == core::config('payment.paypal_currency') AND  $receiver_correct)
			{
                //same price , currency and email no cheating ;)
				if (paypal::validate_ipn())
				{
					$order->confirm_payment('paypal',Core::request('txn_id'));
				}
				else
				{
					Kohana::$log->add(Log::ERROR, 'A payment has been made but is flagged as INVALID');
					$this->response->body('KO');
				}
			}
			else //trying to cheat....
			{
				Kohana::$log->add(Log::ERROR, 'Attempt illegal actions with transaction');
				$this->response->body('KO');
			}
		}// END order loaded
		else
		{
            Kohana::$log->add(Log::ERROR, 'Order not loaded');
            $this->response->body('KO');
		}

		$this->response->body('OK');
	}

	/**
	 * [action_form] generates the form to pay at paypal
	 */
	public function action_pay()
	{
		$this->auto_render = FALSE;

		$order_id = $this->request->param('id');


		$order = new Model_Order();

        $order->where('id_order','=',$order_id)
            ->where('status','=',Model_Order::STATUS_CREATED)
            ->limit(1)->find();

        if ($order->loaded())
        {
        	// case when selling advert
            if(in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM]))
            {
        		$paypal_account = $order->ad->paypal_account();
        		$currency = $order->currency;
        	}
        	else{
        		$paypal_account = core::config('payment.paypal_account');
        		$currency = core::config('payment.paypal_currency');
        	}

			$paypal_url = (Core::config('payment.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;

		 	$paypal_data = array('order_id'            	=> $order_id,
	                             'amount'            	=> number_format($order->amount, 2, '.', ''),
	                             'site_name'        	=> core::config('general.site_name'),
	                             'site_url'            	=> URL::base(TRUE),
                                 'notify_url'           => Route::url('default',array('controller'=>'paypal','action'=>'ipn','id'=>$order_id)),
	                             'paypal_url'        	=> $paypal_url,
	                             'paypal_account'    	=> $paypal_account,
	                             'paypal_currency'    	=> $currency,
	                             'item_name'			=> $order->description);

			$this->template = View::factory('paypal', $paypal_data);
            $this->response->body($this->template->render());

		}
		else
		{
			Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default'));
		}
	}

    public function action_guestipn()
    {
        $this->auto_render = FALSE;

        //START PAYPAL IPN
        //manual checks
        $id_ad            = Core::request('item_number');
        $paypal_amount    = Core::request('mc_gross');
        $payer_id         = Core::request('payer_id');
        $payer_email      = Core::request('payer_email');
        $payer_name       = Core::request('first_name').' '.Core::request('last_name');

        //check ad exists
        $ad     = new Model_Ad($id_ad);

        //loaded published and with stock if we control the stock.
        if($ad->loaded() AND $ad->status==Model_Ad::STATUS_PUBLISHED
            AND (core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1))
            AND (core::config('payment.paypal_seller')==1 OR core::config('payment.stripe_connect')==1)
            )
        {
            //amount we need to recieve
            if($quantity = (int) core::get('quantity', 1))
            {
                $ad->price = $ad->price * $quantity;
            }

            if ($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup'))
                $ad->price = $ad->price;
            elseif($ad->shipping_price())
                $ad->price = $ad->price + $ad->shipping_price();

            //order is from a payment done to the owner of the ad
            $paypal_account = $ad->paypal_account();

            $receiver_correct = (Core::request('receiver_email') == $paypal_account  OR Core::request('business')  == $paypal_account);

            //same amount and same currency
            if ( Core::request('payment_status')   == 'Completed'
                AND  Core::request('mc_gross')     == number_format($ad->price, 2, '.', '')
                AND  Core::request('mc_currency')  == core::config('payment.paypal_currency') AND  $receiver_correct)
            {
                //same price , currency and email no cheating ;)
                if (paypal::validate_ipn())
                {
                    //create user if does not exists, if not will return the user
                    try
                    {
                        $user = Model_User::create_email($payer_email,$payer_name);
                    }
                    catch (ORM_Validation_Exception $e)
                    {
                        Kohana::$log->add(Log::ERROR, 'A user could not be created.');
                        $this->response->body('KO');
                        return;
                    }
                    //new order
                    $order = Model_Order::new_order($ad, $user, Model_Order::PRODUCT_AD_SELL,
                                                    $ad->price, core::config('payment.paypal_currency'), __('Purchase').': '.$ad->seotitle);

                    $order->confirm_payment('paypal',Core::request('txn_id'));
                }
                else
                {
                    Kohana::$log->add(Log::ERROR, 'A payment has been made but is flagged as INVALID');
                    $this->response->body('KO');
                }
            }
            else //trying to cheat....
            {
                Kohana::$log->add(Log::ERROR, 'Attempt illegal actions with transaction');
                $this->response->body('KO');
            }
        }// END order loaded
        else
        {
            Kohana::$log->add(Log::ERROR, 'Ad not loaded');
            $this->response->body('KO');
        }

        $this->response->body('OK');
    }

    /**
     * [action_form] generates the form to pay at paypal
     */
    public function action_guestpay()
    {
        $this->auto_render = FALSE;

        //check ad exists
        $id_ad  = $this->request->param('id');
        $ad     = new Model_Ad($id_ad);

        //loaded published and with stock if we control the stock.
        if($ad->loaded() AND $ad->status==Model_Ad::STATUS_PUBLISHED
            AND (core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1))
            AND (core::config('payment.paypal_seller')==1 OR core::config('payment.stripe_connect')==1)
            )
        {

            $paypal_account = $ad->paypal_account();
            $currency = $ad->currency();

            if($quantity = (int) core::get('quantity', 1))
            {
                $ad->price = $ad->price * $quantity;
            }

            if($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup'))
                $ad->price = $ad->price;
            elseif($ad->shipping_price())
                $ad->price = $ad->price + $ad->shipping_price();

            $paypal_url = (Core::config('payment.sandbox')) ? Paypal::url_sandbox_gateway : Paypal::url_gateway;
            $notify_url = Route::url('default',array('controller'=>'paypal','action'=>'guestipn','id'=>$id_ad));

            $notify_url = $notify_url . '?' . http_build_query([
                'shipping_pickup' => ($ad->shipping_pickup() AND core::get('shipping_pickup')) ? 1 : null,
                'quantity' => (int) core::get('quantity', 1),
            ]);

            $paypal_data = array('order_id'             => $id_ad,
                                 'amount'               => number_format($ad->price, 2, '.', ''),
                                 'site_name'            => core::config('general.site_name'),
                                 'site_url'             => URL::base(TRUE),
                                 'notify_url'           => $notify_url,
                                 'paypal_url'           => $paypal_url,
                                 'paypal_account'       => $paypal_account,
                                 'paypal_currency'      => $currency,
                                 'item_name'            => $ad->title);

            $this->template = View::factory('paypal', $paypal_data);
            $this->response->body($this->template->render());

        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default'));
        }

    }
}
