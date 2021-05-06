<?php

/**
* Stripe class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_Stripe extends Controller{

    /**
     * gets the payment token from stripe and marks order as paid
     */
    public function action_pay()
    {
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {

            if ( isset( $_POST[ 'stripeToken' ] ) )
            {
                //its a fraud...lets let him know
                if ( $order->is_fraud() === TRUE )
                {
                    Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                StripeKO::init();

                // Get the credit card details submitted by the form
                $token = Core::post('stripeToken');

                try
                {
                    // Create a Customer
                    $customer = \Stripe\Customer::create(array(
                      'source'  => $token,
                      'email' => $order->user->email)
                    );
                }
                catch(Exception $e)
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                //3d secure active?
                if (Core::config('payment.stripe_3d_secure') == TRUE)
                {
                    try
                    {
                        $three_d_secure =  \Stripe\ThreeDSecure::create(array(
                                                    'customer'  => $customer->id,
                                                    'amount'    => StripeKO::money_format($order->amount),
                                                    'currency'  => $order->currency,
                                                    'return_url'=> Route::url('default',array('controller'=>'stripe','action'=>'3d','id'=>$order->id_order))
                                            ));
                    }
                    catch(Exception $e)
                    {
                        // The card has been declined
                        Kohana::$log->add(Log::ERROR, 'Stripe 3D The card has been declined');
                        Alert::set(Alert::ERROR, 'The card has been declined 3D secure.');
                        $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                    }

                    //he has 3d secure redirect to stripe, if not continues normal process
                    if ($three_d_secure->status == 'redirect_pending')
                    {
                        //so we can use later the customer to store it
                        Session::instance()->set('customer_id',$customer->id);
                        die(View::factory('post_redirect', ['redirect_url' => $three_d_secure->redirect_url])->render());
                    }
                    else
                    {
                        Alert::set(Alert::WARNING, 'This Card does not support 3D secure. Please try another card or use another payment method. Thanks.');
                        $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                    }
                }

                // Create the charge on Stripe's servers - this will charge the user's card
                try
                {
                    $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        'customer'  => $customer->id,
                                                        "description" => $order->description,
                                                        "metadata"    => array("id_order" => $order->id_order))
                                                    );

                    //its a plan product
                    if ($order->id_product >= 100)
                    {
                        //save the stripe user id to be able to charge them later on renewal
                        $order->user->stripe_agreement = $customer->id;
                        $order->user->save();
                    }
                }
                catch(Exception $e)
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'Stripe The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                //mark as paid
                $order->confirm_payment('stripe',Core::post('stripeToken'));

                $moderation = core::config('general.moderation');

                if ($moderation == Model_Ad::PAYMENT_MODERATION
                    AND $order->id_product == Model_Order::PRODUCT_CATEGORY)
                {
                    Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
                    $this->redirect(Route::url('default', ['action' => 'thanks', 'controller' => 'ad', 'id' => $order->id_ad]));
                }

                if ($moderation == Model_Ad::PAYMENT_ON
                    AND $order->id_product == Model_Order::PRODUCT_CATEGORY)

                {
                    $this->redirect(Route::url('default', ['action' => 'thanks', 'controller' => 'ad', 'id' => $order->id_ad]));
                }

                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }

        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }


    /**
     * gets the payment token from stripe and marks order as paid. Methos for application fee
     */
    public function action_payconnect()
    {
        //TODO only if stripe connect enabled

        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->where('id_product','in',[Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])
                       ->limit(1)->find();

        if ($order->loaded())
        {

            if ( isset( $_POST[ 'stripeToken' ] ) )
            {
                //its a fraud...lets let him know
                if ( $order->is_fraud() === TRUE )
                {
                    Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                StripeKO::init();

                // Get the credit card details submitted by the form
                $token = Core::post('stripeToken');

                // email
                $email = Core::post('stripeEmail');

                // Create the charge on Stripe's servers - this will charge the user's card
                try
                {
                    //in case memberships the fee may be set on the plan ;)
                    $fee = NULL;
                    if ( $order->ad->user->subscription()->loaded() )
                        $fee = $order->ad->user->subscription()->plan->marketplace_fee;

                    $application_fee = StripeKO::application_fee($order->amount, $fee);

                    //we charge the fee only if its not admin
                    if (! $order->ad->user->is_admin())
                    {
                        $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        "source"      => $token,
                                                        "description" => $order->description,
                                                        "application_fee" => StripeKO::money_format($application_fee)),
                                                     array('stripe_account' => $order->ad->user->stripe_user_id)
                                                    );
                    }
                    else
                    {
                        $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        "source"      => $token,
                                                        "description" => $order->description)
                                                    );
                    }

                }
                catch(Exception $e)
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'Stripe The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                //mark as paid
                $order->confirm_payment('stripe',Core::post('stripeToken'));

                //only if is not admin
                if (! $order->ad->user->is_admin())
                {
                    //crete new order for the application fee so we know how much the site owner is earning ;)
                    $order_app = Model_Order::new_order($order->ad, $order->ad->user,
                                                        Model_Order::PRODUCT_APP_FEE, $application_fee, $order->currency,
                                                        'id_order->'.$order->id_order.' id_ad->'.$order->ad->id_ad);
                    $order_app->confirm_payment('stripe',Core::post('stripeToken'));
                }

                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }

        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }

    /**
     * gets the payment token from stripe and marks order as paid. Methos for application fee
     * connect for guests, only for sell ad products
     */
    public function action_payguest()
    {

        $this->auto_render = FALSE;

        $id_ad = $this->request->param('id');

        //check ad exists
        $ad     = new Model_Ad($id_ad);

        //loaded published and with stock if we control the stock.
        if($ad->loaded() AND $ad->status==Model_Ad::STATUS_PUBLISHED
            AND (core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1))
            AND (core::config('payment.stripe_connect')==1)
            )
        {

            if ( isset( $_POST[ 'stripeToken' ] ) )
            {

                StripeKO::init();

                // Get the credit card details submitted by the form
                $token = Core::post('stripeToken');

                // email
                $email = Core::post('stripeEmail');


                // Create the charge on Stripe's servers - this will charge the user's card
                try
                {
                    //in case memberships the fee may be set on the plan ;)
                    $fee = NULL;
                    if ( $ad->user->subscription()->loaded() )
                        $fee = $ad->user->subscription()->plan->marketplace_fee;

                    if($quantity = (int) core::get('quantity', 1))
                    {
                        $ad->price = $ad->price * $quantity;
                    }

                    if ($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup'))
                        $ad->price = $ad->price;
                    elseif($ad->shipping_price())
                        $ad->price = $ad->price + $ad->shipping_price();

                    $application_fee = StripeKO::application_fee($ad->price, $fee);

                    //we charge the fee only if its not admin
                    if (! $ad->user->is_admin())
                    {
                        $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($ad->price), // amount in cents, again
                                                        "currency"  => $ad->currency(),
                                                        "source"      => $token,
                                                        "description" => $ad->title,
                                                        "application_fee" => StripeKO::money_format($application_fee)),
                                                     array('stripe_account' => $ad->user->stripe_user_id)
                                                    );
                    }
                    else
                    {
                        $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($ad->price), // amount in cents, again
                                                        "currency"  => $ad->currency(),
                                                        "source"      => $token,
                                                        "description" => $ad->title)
                                                    );
                    }

                }
                catch(Exception $e)
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'Stripe The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'guestcheckout','id'=>$ad->id_ad)));
                }

                //create user if does not exists, if not will return the user
                try
                {
                    $user = Model_User::create_email($email);
                }
                catch (ORM_Validation_Exception $e)
                {
                    Kohana::$log->add(Log::ERROR, 'A user could not be created.');
                    $this->response->body('KO');
                    return;
                }
                //new order
                $order = Model_Order::new_order($ad, $user, Model_Order::PRODUCT_AD_SELL,
                                                $ad->price, $ad->currency(), __('Purchase').': '.$ad->seotitle);

                //mark as paid
                $order->confirm_payment('stripe',Core::post('stripeToken'));

                //only if is not admin we charge the fee
                if (! $order->ad->user->is_admin())
                {
                    //crete new order for the application fee so we know how much the site owner is earning ;)
                    $order_app = Model_Order::new_order($order->ad, $order->ad->user,
                                                        Model_Order::PRODUCT_APP_FEE, $application_fee, core::config('payment.paypal_currency'),
                                                        'id_order->'.$order->id_order.' id_ad->'.$order->ad->id_ad);
                    $order_app->confirm_payment('stripe',Core::post('stripeToken'));
                }

                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('default'));
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'guestcheckout','id'=>$ad->id_ad)));
            }

        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'guestcheckout','id'=>$ad->id_ad)));
        }
    }

    /**
     * connects the loged user to his stripe account
     * code based on https://gist.github.com/7109113
     * see https://stripe.com/docs/connect/standalone-accounts
     * @return [type] [description]
     */
    public function action_connect()
    {
        // only if stripe connect enabled
        if (Core::config('payment.stripe_connect')==FALSE )
            throw HTTP_Exception::factory(404,__('Page not found'));

        //user needs to be loged in
        if (!Auth::instance()->logged_in())
            $this->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')).'?auth_redirect='.URL::current());

        //stored in configs
        $client_id = Core::config('payment.stripe_clientid');

        if (isset($_GET['code']))
        { // Redirect w/ code
            $code = $_GET['code'];

            $token_request_body = array(
                                        'client_secret' => Core::config('payment.stripe_private'),
                                        'grant_type'    => 'authorization_code',
                                        'client_id'     => $client_id,
                                        'code'          => $code,
                                        );

            $req = curl_init('https://connect.stripe.com/oauth/token');
            curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($req, CURLOPT_POST, TRUE );
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
            $response = curl_exec($req);

            if( ! curl_errno($req))
            {
                curl_close($req);
                $response = json_decode($response, TRUE);

                if(isset($response['error_description']))
                    Alert::set(Alert::ERROR,$response['error_description']);
                elseif(isset($response['stripe_user_id']))
                {
                    //save into the user
                    $this->user->stripe_user_id = $response['stripe_user_id'];
                    $this->user->save();
                    Alert::set(Alert::INFO, __('Stripe Connected'));
                }
            }
            else
                Alert::set(Alert::ERROR, 'We could not connect with Stripe.');
        }
        elseif (isset($_GET['error']))
            Alert::set(Alert::ERROR, $_GET['error']);
        else
        { // redirect user to stripe connect
            $authorize_request_body = array(
                                            'response_type' => 'code',
                                            'scope'         => 'read_write',
                                            'client_id'     => $client_id
                                            );

            $url = 'https://connect.stripe.com/oauth/authorize?' . http_build_query($authorize_request_body);
            //echo "<a href='$url'>Connect with Stripe</a>";
            $this->redirect($url);
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
    }

    /**
     * [action_form] generates the form to pay at paypal
     */
    public function action_3d()
    {
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {
            //dr($_GET);
            if ( Core::get('status') == 'succeeded' AND Core::get('id')!=NULL AND ($customer_id = Session::instance()->get('customer_id')) != NULL)
            {
                try
                {
                    StripeKO::init();

                    // Create the charge on Stripe's servers - this will charge the user's card
                    $charge = \Stripe\Charge::create(array(
                                                        "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                        "currency"  => $order->currency,
                                                        'customer'  => $customer_id,//we charge this customer!
                                                        "description" => $order->description,
                                                        "metadata"    => array("id_order" => $order->id_order))
                                                    );
                }
                catch(Exception $e)
                {
                    // The card has been declined
                    Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                    Alert::set(Alert::ERROR, 'The card has been declined');
                    $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
                }

                //its a plan product
                if ($order->id_product >= 100)
                {
                    //save the stripe user id to be able to charge them later on renewal
                    $order->user->stripe_agreement = $customer_id;
                    $order->user->save();
                }

                //mark as paid
                $order->confirm_payment('stripe', $charge->id);

                $moderation = core::config('general.moderation');

                if ($moderation == Model_Ad::PAYMENT_MODERATION
                    AND $order->id_product == Model_Order::PRODUCT_CATEGORY)
                {
                    Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
                    $this->redirect(Route::url('default', ['action' => 'thanks', 'controller' => 'ad', 'id' => $order->id_ad]));
                }

                if ($moderation == Model_Ad::PAYMENT_ON
                    AND $order->id_product == Model_Order::PRODUCT_CATEGORY)

                {
                    $this->redirect(Route::url('default', ['action' => 'thanks', 'controller' => 'ad', 'id' => $order->id_ad]));
                }

                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));

            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }

        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }

    public function action_connect_express()
    {
        // only if stripe connect enabled
        if (Core::config('payment.stripe_connect') == FALSE )
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        //user needs to be loged in
        if (!Auth::instance()->logged_in())
        {
            $this->redirect(Route::url('oc-panel', ['controller'=>'auth','action'=>'login']).'?auth_redirect='.URL::current());
        }

        StripeCheckout::init();

        if (empty($this->user->stripe_user_id))
        {
            $account_parameters = [
                'type' => 'express',
                'email' => $this->user->email,
            ];

            if (Core::config('payment.stripe_escrow'))
            {
                $account_parameters['settings'] = [
                    'payouts' => [
                        'schedule' => [
                            'interval' => 'manual',
                        ],
                    ],
                ];
            }

            $account = \Stripe\Account::create($account_parameters);

            $this->user->stripe_user_id = $account->id;
            $this->user->save();
        }

        $account_link = \Stripe\AccountLink::create([
            'account' => $this->user->stripe_user_id,
            'refresh_url' => Route::url('default', ['controller' => 'stripe', 'action' => 'connect_express', 'id' => 'now']),
            'return_url' => Route::url('oc-panel', ['controller' => 'profile', 'action' => 'edit']),
            'type' => 'account_onboarding',
        ]);

        $this->redirect($account_link->url);
    }

    public function action_log_into_connected_account()
    {
        // only if stripe connect enabled
        if (Core::config('payment.stripe_connect') == FALSE )
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        //user needs to be loged in
        if (!Auth::instance()->logged_in())
        {
            $this->redirect(Route::url('oc-panel', ['controller'=>'auth','action'=>'login']).'?auth_redirect='.URL::current());
        }

        $this->redirect(StripeKO::create_connected_account_login_link(
            $this->user,
            Route::url('oc-panel', ['controller' => 'profile', 'action' => 'edit'])
        ));
    }
}
