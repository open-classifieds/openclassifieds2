<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author      Slobodan <slobodan@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 * *
 */
class Model_Order extends ORM {


    /**
     * Table name to use
     *
     * @access  protected
     * @var     string  $_table_name default [singular model name]
     */
    protected $_table_name = 'orders';

    /**
     * Column to use as primary key
     *
     * @access  protected
     * @var     string  $_primary_key default [id]
     */
    protected $_primary_key = 'id_order';

    /**
     * Status constants
     */
    const STATUS_CREATED        = 0;   // just created
    const STATUS_PAID           = 1;   // paid!
    const STATUS_REFUSED        = 5;   //tried to paid but not succeed
    const STATUS_REFUND         = 99;  //we refunded the money

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = array(
        self::STATUS_CREATED      =>  'Created',
        self::STATUS_PAID         =>  'Paid',
        self::STATUS_REFUSED      =>  'Refused',
        self::STATUS_REFUND       =>  'Refund',
    );

    /**
     * Id of products
     */
    const PRODUCT_CATEGORY      = 1; //paid to post in a paid category
    const PRODUCT_TO_TOP        = 2; //paid to return the ad to the first page
    const PRODUCT_TO_FEATURED   = 3; // paid to featured an ad in the site
    const PRODUCT_AD_SELL       = 4; // a customer paid to buy the item/ad
    const PRODUCT_APP_FEE       = 5; // application fee, thats what we cahrge to the seller

    /**
     * returns the product array
     * @return string
     */
    public static function products()
    {
        $products = array(
                            self::PRODUCT_CATEGORY      =>  __('Post in paid category'),
                            self::PRODUCT_TO_TOP        =>  __('Top up ad'),
                            self::PRODUCT_TO_FEATURED   =>  __('Feature ad'),
                            self::PRODUCT_AD_SELL       =>  __('Buy product'),
                            self::PRODUCT_APP_FEE       =>  __('Application Fee'),
                        );
        if (Core::config('general.subscriptions')==TRUE AND Core::extra_features() == TRUE )
        {
            $plans = new Model_Plan();
            $plans = $plans->where('status','=',1)
                            ->cached()->find_all();
            foreach ($plans as $plan)
                $products[$plan->id_plan] = $plan->name;
        }

        return $products;
    }


    /**
     * returns the product descripton
     * @param  int $product
     * @return string
     */
    public static function product_desc($product)
    {
        //its a plan product
        if ($product >= 100)
        {
            $plan = new Model_Plan($product);
            return Text::removebbcode(($plan->loaded())?$plan->name.' - '.$plan->description:NULL);
        }
        else
        {
            $products = self::products();

            return (isset($products[$product])) ? $products[$product] : '' ;
        }

    }

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'ad' => array(
                'model'       => 'ad',
                'foreign_key' => 'id_ad',
            ),
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'coupon' => array(
                'model'       => 'coupon',
                'foreign_key' => 'id_coupon',
            ),
    );

    /**
     * confirm payment for order
     *
     * @param string    $id_order [unique indentifier of order]
     * @param string    $txn_id id of the transaction depending on provider
     */
    public function confirm_payment($paymethod = 'paypal', $txn_id = NULL)
    {

        // update orders
        if($this->loaded())
        {

            $this->status    = self::STATUS_PAID;
            $this->pay_date  = Date::unix2mysql();
            $this->paymethod = $paymethod;
            $this->txn_id    = $txn_id;

            try {
                $this->save();
            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            //if saved delete coupon from session and -- number of coupons.
            Model_Coupon::sale($this->coupon);

            //for membership plans
            if ($this->id_product >= 100)
            {
                Model_Subscription::new_order($this);


                $replace_email = array('[AD.TITLE]'     => $this->description,
                                     '[URL.AD]'         => Route::url('pricing'),
                                     '[ORDER.ID]'       => $this->id_order,
                                     '[PRODUCT.ID]'     => $this->id_product,
                                     '[VAT.COUNTRY]'    => (isset($this->VAT) AND $this->VAT > 0)?$this->VAT_country:'',
                                     '[VAT.NUMBER]'     => (isset($this->VAT) AND $this->VAT > 0)?$this->VAT_number:'',
                                     '[VAT.PERCENTAGE]' => (isset($this->VAT) AND $this->VAT > 0)?$this->VAT:'');

            }
            else
            {
                $ad  = $this->ad;

                //depending on the product different actions
                switch ($this->id_product) {
                    case Model_Order::PRODUCT_AD_SELL:
                            $ad->sale($this);
                        break;
                    case Model_Order::PRODUCT_TO_TOP:
                            $ad->to_top();
                        break;
                    case Model_Order::PRODUCT_TO_FEATURED:
                            $ad->to_feature($this->featured_days);
                        break;
                    case Model_Order::PRODUCT_CATEGORY:
                            $ad->paid_category();
                        break;
                }

                $url_ad = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));

                $replace_email = array('[AD.TITLE]'     => $ad->title,
                                     '[URL.AD]'         => $url_ad,
                                     '[ORDER.ID]'       => $this->id_order,
                                     '[PRODUCT.ID]'     => $this->id_product,
                                     '[VAT.COUNTRY]'    => (isset($this->VAT) AND $this->VAT > 0)?$this->VAT_country:'',
                                     '[VAT.NUMBER]'     => (isset($this->VAT) AND $this->VAT > 0)?$this->VAT_number:'',
                                     '[VAT.PERCENTAGE]' => (isset($this->VAT) AND $this->VAT > 0)?$this->VAT:'');

            }

            //send email to site owner! new sale!!
            if(core::config('email.new_ad_notify') == TRUE)
            {
                Email::content(core::config('email.notify_email'),
                                    core::config('general.site_name'),
                                    core::config('email.notify_email'),
                                    core::config('general.site_name'),'ads-sold',
                                    $replace_email);
            }

        }
    }


    /**
     * creates an order
     * @param  Model_Ad $ad
     * @param  Model_User $user
     * @param  integer   $id_product
     * @param  numeric   $amount
     * @param  string   $currency
     * @param  string   $description
     * @return Model_Order
     */
    public static function new_order(Model_Ad $ad = NULL, $user, $id_product, $amount, $currency = NULL, $description = NULL, $featured_days = NULL, $quantity = 1)
    {
        if ($currency === NULL)
            $currency = core::config('payment.paypal_currency');

        if ($description === NULL)
            $description = Model_Order::product_desc($id_product);

        //get if theres an unpaid order for this product and this ad
        $order = new Model_Order();

        if ($ad!==NULL AND $ad->loaded())
            $order->where('id_ad',      '=', $ad->id_ad);

        $order
              ->where('id_user',    '=', $user->id_user)
              ->where('status',     '=', Model_Order::STATUS_CREATED)
              ->where('id_product', '=', $id_product)
              ->where('amount',     '=', $amount)
              ->where('currency',   '=', $currency)
              ->limit(1)->find();

        //if no unpaid create order
        if (!$order->loaded())
        {
            //add coupon ID and discount only if not AD_SELL
            if (Model_Coupon::valid($id_product))
            {
                $amount = Model_Coupon::price($id_product,$amount);
                $order->id_coupon = Model_Coupon::current()->id_coupon;
            }

            //create order
            $order = new Model_Order;
            $order->id_user       = $user->id_user;
            if ($ad!==NULL AND $ad->loaded())
                $order->id_ad         = $ad->id_ad;
            $order->id_product    = $id_product;
            $order->currency      = $currency;
            $order->amount        = $amount;
            $order->description   = $description;
            $order->quantity   = $quantity;

            // check product
            if($order->id_product == Model_Order::PRODUCT_AD_SELL)
            {
                // check if ad has VAT
                if(isset($order->ad->cf_vatnumber) AND $order->ad->cf_vatnumber AND isset($order->ad->cf_vatcountry) AND $order->ad->cf_vatcountry)
                {
                    $order->VAT_country = $order->ad->cf_vatcountry;
                    $order->VAT_number = $order->ad->cf_vatnumber;

                    if(euvat::is_eu_country($order->ad->cf_vatcountry))
                        $order->VAT = euvat::vat_by_country($order->ad->cf_vatcountry);
                    elseif(isset($order->ad->cf_vatcountry) AND isset($order->ad->cf_vatnoneu) AND $order->ad->cf_vatnoneu > 0 AND $order->ad->cf_vatnoneu!=NULL)
                        $order->VAT = $order->ad->cf_vatnoneu;

                }
                // check if user has VAT
                elseif(isset($order->ad->user->cf_vatnumber) AND $order->ad->user->cf_vatnumber AND isset($order->ad->user->cf_vatcountry) AND $order->ad->user->cf_vatcountry)
                {
                    $order->VAT_country = $order->ad->user->cf_vatcountry;
                    $order->VAT_number = $order->ad->user->cf_vatnumber;

                    if(euvat::is_eu_country($order->ad->user->cf_vatcountry))
                        $order->VAT = euvat::vat_by_country($order->ad->user->cf_vatcountry);
                    elseif(isset($order->ad->user->cf_vatcountry) AND isset($order->ad->user->cf_vatnoneu) AND $order->ad->user->cf_vatnoneu > 0 AND $order->ad->user->cf_vatnoneu!=NULL)
                        $order->VAT = $order->ad->user->cf_vatnoneu;

                }
            }
            else
            {
                if(core::config('payment.vat_country') AND core::config('payment.vat_number'))
                {
                    $order->VAT_country = core::config('payment.vat_country');
                    $order->VAT_number = core::config('payment.vat_number');

                    // if is an eu country the VAT rate is calculated automatically, otherwise it's set by the vat_non_eu field
                    if(euvat::is_eu_country(core::config('payment.vat_country')))
                        $order->VAT = euvat::vat_by_country(core::config('payment.vat_country'));
                    elseif (core::config('payment.vat_non_eu') AND core::config('payment.vat_non_eu') > 0)
                        $order->VAT = core::config('payment.vat_non_eu');
                }
            }

            //store how many days the ad is featured
            if ($featured_days!==NULL AND is_numeric($featured_days))
                $order->featured_days = $featured_days;

            try {
                $order->save();
            }
            catch (Exception $e){
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            //send email to user with link to pay
            $url_checkout = $user->ql('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order));

            $replace = array('[ORDER.ID]'    => $order->id_order,
                             '[ORDER.DESC]'  => $order->description,
                             '[ORDER.QUANTITY]'  => $order->quantity,
                             '[URL.CHECKOUT]'=> $url_checkout);

            //$user->email('new-order',$replace);
        }

        return $order;
    }



    public function exclude_fields()
    {
        return array('created','id_ad','id_user');
    }

    /**
     *
     * formmanager definitions
     *
     */
    public function form_setup($form)
    {

        $form->fields['description']['display_as'] = 'textarea';
        $form->fields['status']['display_as']       = 'select';
        $form->fields['status']['options']          = array_keys(self::$statuses);
        $form->fields['id_product']['display_as']   = 'select';
        $form->fields['id_product']['options']      = array_keys(self::products());
        $form->fields['txn_id']['display_as']       = 'text';

    }

    /**
     * renders a modal with alternative paymethod instructions
     * @return string
     */
    public function alternative_pay_button()
    {
        if($this->loaded())
        {
            if (core::config('payment.alternative')!='' )
            {
                $content = Model_Content::get_by_title(core::config('payment.alternative'));
                return View::factory('pages/alternative_payment',array('content'=>$content))->render();
            }
        }

        return FALSE;
    }

    /**
     * returns the featured plancs
     * @return array
     */
    public static function get_featured_plans()
    {
        return json_decode(Core::config('payment.featured_plans'),TRUE);
    }

    /**
     * returns price for  plan
     * @param  integer $days
     * @return integer / false if not found
     */
    public static function get_featured_price($days=NULL)
    {
        $plans = self::get_featured_plans();

        //no days so return first price
        if ($days===NULL)
            return reset($plans);

        //normal lets check
        return (isset($plans[$days]))?$plans[$days]:FALSE;
    }

    /**
     * deletes a featured planplan
     * @param  integer $days
     */
    public static function delete_featured_plan($days)
    {
        $plans = self::get_featured_plans();

        if (isset($plans[$days]))
        {
            unset($plans[$days]);
            Model_Config::set_value('payment','featured_plans',json_encode($plans));
        }

    }

    /**
     * sets/creates a new plan
     * @param integer $days
     * @param integer $price
     * @param integer $days_key key to be deleted...
     */
    public static function set_featured_plan($days,$price,$days_key=NULL)
    {
        $plans = self::get_featured_plans();

        //this deletes the previous key in case is a set. we do it here since calling delete_featured was cached...ugly as hell.
        if (is_numeric($days_key) AND isset($plans[$days_key]))
            unset($plans[$days_key]);

        //this updates a current plan
        $plans[$days] = $price;

        //order from lowest to highest number of days
        ksort($plans);

        Model_Config::set_value('payment','featured_plans',json_encode($plans));
    }


    /**
     * verifies pricing in an existing order
     * @return void
     */
    public function check_pricing()
    {
        //update order based on the price and the amount of
        $days = core::get('featured_days');
        if (is_numeric($days) AND ($price = Model_Order::get_featured_price($days)) !==FALSE )
        {
            $this->amount        = $price; //get price from config
            $this->featured_days = $days;
            $this->save();
        }

        if ($this->ad->shipping_pickup() AND core::get('shipping_pickup'))
        {
            $this->amount = $this->ad->price * $this->quantity;
            $this->save();
        }

        //original coupon so we dont lose it while we do operations
        $orig_coupon = $this->id_coupon;

        //remove the coupon forced by get/post
        if(core::request('coupon_delete') != NULL)
            $this->id_coupon = NULL;
        //maybe changed the coupon? from the form
        elseif (Model_Coupon::valid($this->id_product) AND $this->id_coupon != Model_Coupon::current()->id_coupon )
            $this->id_coupon = Model_Coupon::current()->id_coupon;
        //not valid coupon anymore, this can happen if they add a coupon now but they pay days later.
        elseif($this->coupon->loaded() AND (
                                            Date::mysql2unix($this->coupon->valid_date) < time()  OR
                                            $this->coupon->status == 0 OR
                                            $this->coupon->number_coupons == 0
                                            ))
        {
            Alert::set(Alert::INFO, __('Coupon not valid, expired or already used.'));
            $this->coupon->clear();
            $this->id_coupon = NULL;
        }

        //add new discount
        $new_amount = Model_Coupon::price($this->id_product,$this->original_price());

        //recalculate price since it change the coupon
        if ($orig_coupon != $this->id_coupon OR $this->amount!=$new_amount)
        {
            $this->amount = $new_amount;

            try {
                $this->save();
            }
            catch (Exception $e){
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }

    }

    /**
     * returns the original price of the order
     * @return float
     */
    public function original_price()
    {
        //get original price for the product
        switch ($this->id_product) {
            case self::PRODUCT_CATEGORY:
                    $amount = $this->ad->category->price > 0 ? $this->ad->category->price : $this->ad->category->parent->price;
                break;
            case self::PRODUCT_TO_TOP:
                    $amount = core::config('payment.pay_to_go_on_top');
                break;
            case self::PRODUCT_TO_FEATURED:
                    $amount = Model_Order::get_featured_price($this->featured_days);
                break;
            case self::PRODUCT_AD_SELL:
                    if ($this->ad->shipping_pickup() AND core::get('shipping_pickup'))
                        $amount = $this->ad->price * $this->quantity;
                    elseif ($this->ad->shipping_price())
                        $amount = ($this->ad->price * $this->quantity) + $this->ad->shipping_price();
                    else
                        $amount = $this->ad->price * $this->quantity;
                break;
            default:
                $plan = new Model_Plan($this->id_product);

                $amount = ($plan->loaded())?$plan->price:0;
                break;
        }

        return $amount;
    }

    /**
     * calculates and adds VAT to the order amount
     */
    public function add_VAT()
    {
        if($this->VAT > 0)
        {
            $this->amount = ($this->amount) + ($this->amount * $this->VAT / 100);

            try {
                $this->save();
            }
            catch (Exception $e){
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }
    }

    /**
     * verify if a transaction is fraudulent
     * @return boolean
     */
    public function is_fraud()
    {
        //only production and api set
        if ($this->loaded() AND core::config('payment.fraudlabspro')!='')
        {
            //get the country
            $country_code = i18n::ip_country_code();

            // Include FraudLabs Pro library
            require Kohana::find_file('vendor/', 'FraudLabsPro.class');

            $fraud = new FraudLabsPro(core::config('payment.fraudlabspro'));

            try {
                // Check this transaction for possible fraud. FraudLabs Pro support comprehensive validation check,
                // and for this example, we only perform the IP address, BIN and billing country validation.
                // For complete validation, please check our developer page at http://www.fraudlabspro.com/developer
                $fraud_result = $fraud->check(array(
                    'ipAddress'         => Request::$client_ip,
                    'billingCountry'    => $country_code,
                    'quantity'          => 1,
                    'amount'            => $this->amount,
                    'currency'          => $this->currency,
                    'emailAddress'      => $this->user->email,
                    'paymentMode'       => 'others',
                    'flpChecksum'       => Core::cookie('flp_checksum',session_id()),
                ));

                $fraud_result_status = $fraud_result->fraudlabspro_status;

            }
            catch (Exception $e) {
                $fraud_result_status = 'DECLINED';
            }

            // This transaction is legitimate, let's submit to Stripe
            if($fraud_result_status == 'APPROVE')
            {
                return FALSE;
            }
            //not approved!! fraud! save log
            else
            {
                Kohana::$log->add(Log::ERROR, 'Fraud detected id_order:'.$this->id_order);
                return TRUE;
            }

        }

        //by default we say is not fraud
        return FALSE;

    }

    public static function by_user(Model_User $user)
    {
        if (! $user->loaded())
        {
            return;
        }

        return (new self)->where('id_user', '=', $user->id_user);
    }

    protected $_table_columns =
array (
  'id_order' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_order',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'id_user' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_user',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_ad' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_ad',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_product' =>
  array (
    'type' => 'string',
    'column_name' => 'id_product',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'character_maximum_length' => '20',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'paymethod' =>
  array (
    'type' => 'string',
    'column_name' => 'paymethod',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 5,
    'character_maximum_length' => '20',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' =>
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'pay_date' =>
  array (
    'type' => 'string',
    'column_name' => 'pay_date',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'currency' =>
  array (
    'type' => 'string',
    'exact' => true,
    'column_name' => 'currency',
    'column_default' => NULL,
    'data_type' => 'char',
    'is_nullable' => false,
    'ordinal_position' => 8,
    'character_maximum_length' => '3',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'amount' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'amount',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'numeric_precision' => '14',
    'numeric_scale' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'quantity' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'quantity',
    'column_default' => 0,
    'data_type' => 'int',
    'is_nullable' => false,
    'numeric_precision' => '14',
    'numeric_scale' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'VAT' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'VAT',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'numeric_precision' => '14',
    'numeric_scale' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'VAT_number' =>
  array (
    'type' => 'string',
    'exact' => true,
    'column_name' => 'VAT_number',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 11,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'VAT_country' =>
  array (
    'type' => 'string',
    'exact' => true,
    'column_name' => 'VAT_country',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 11,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'status' =>
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'status',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'description' =>
  array (
    'type' => 'string',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 11,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'txn_id' =>
  array (
    'type' => 'string',
    'column_name' => 'txn_id',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 12,
    'character_maximum_length' => '255',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'featured_days' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'featured_days',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 13,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_coupon' =>
  array (
    'type' => 'int',
    'min' => '-2147483648',
    'max' => '2147483647',
    'column_name' => 'id_coupon',
    'column_default' => NULL,
    'data_type' => 'int',
    'is_nullable' => true,
    'ordinal_position' => 14,
    'display' => '11',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
}
