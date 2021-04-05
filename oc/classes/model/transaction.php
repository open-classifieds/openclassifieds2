<?php
/**
 * Transactions
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Transaction extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'transactions';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_transaction';

    /**
     * Status constants
     */
    const TYPE_DEPOSIT  = 1; // deposit
    const TYPE_CHARGE   = 2; // charge

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = [
        self::TYPE_DEPOSIT => 'Deposit',
        self::TYPE_CHARGE   => 'Charge',
    ];

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = [
        'user' => [
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ],
        'user_from' => [
                'model'       => 'user',
                'foreign_key' => 'id_user_from',
            ],
        'order' => [
                'model'       => 'order',
                'foreign_key' => 'id_order',
            ],
    ];

    /**
     * Charge
     *
     * @param  Model_User           $user
     * @param  Model_Order|null     $order
     * @param  integer              $amount
     * @return Model_Transaction
     */
    public static function charge(Model_User $user, Model_Order $order = NULL, $amount)
    {
        if (self::wallet_balance_for($user) < $amount)
            return FALSE;

        $transaction = new self();
        $transaction->id_user = $user->id_user;
        $transaction->id_order = $order !== NULL ? $order->id_order : NULL;
        $transaction->amount = -1 * abs($amount);
        $transaction->type = self::TYPE_CHARGE;

        $transaction->save();

        $user->ewallet_balance = self::wallet_balance_for($user);
        $user->save();

        return $transaction;
    }

    /**
     * Deposit
     *
     * @param  Model_User           $user
     * @param  Model_User|null      $sender
     * @param  Model_Order|null     $order
     * @param  integer              $amount
     * @return Model_Transaction
     */
    public static function deposit(Model_User $user, Model_User $sender = NULL, Model_Order $order = NULL, $amount)
    {
        $transaction = new self();
        $transaction->id_user = $user->id_user;
        $transaction->id_user_from = $sender !== NULL ? $sender->id_user : NULL;
        $transaction->id_order = $order !== NULL ? $order->id_order : NULL;
        $transaction->amount = $amount;
        $transaction->type = self::TYPE_DEPOSIT;

        $transaction->save();

        $user->ewallet_balance = self::wallet_balance_for($user);
        $user->save();

        return $transaction;
    }

    /**
     * Transfer
     *
     * @param  Model_User           $sender
     * @param  Model_User           $recipient
     * @param  Model_Order|null     $order
     * @param  integer              $amount
     * @return Model_Transaction
     */
    public static function transfer(Model_User $sender, Model_User $recipient, Model_Order $order = NULL, $amount)
    {
        $amount = $amount;

        if (self::wallet_balance_for($sender) < $amount)
            return FALSE;

        self::charge($sender, $order, $amount);
        self::deposit($recipient, $sender, $order, $amount);

        return TRUE;
    }

    /**
     * returns the money packages
     * @return array
     */
    public static function get_money_packages()
    {
        return json_decode(Core::config('general.ewallet_money_packages'), TRUE);
    }

    /**
     * returns price for money
     * @param  integer $money
     * @return integer / false if not found
     */
    public static function get_money_price($money = NULL)
    {
        $packages = self::get_money_packages();

        //no days so return first price
        if ($money === NULL)
        {
            return reset($packages);
        }

        //normal lets check
        return (isset($packages[$money])) ? $packages[$money] : FALSE;
    }

    /**
     * deletes a money package
     * @param  integer $money
     */
    public static function delete_money_package($money)
    {
        $packages = self::get_money_packages();

        if (isset($packages[$money]))
        {
            unset($packages[$money]);

            Model_Config::set_value('general', 'ewallet_money_packages', json_encode($packages));
        }
    }

    /**
     * sets/creates a new package
     * @param integer $money
     * @param integer $price
     * @param integer $money_key key to be deleted...
     */
    public static function set_money_package($money, $price, $money_key = NULL)
    {
        $packages = self::get_money_packages();

        //this deletes the previous key in case is a set. we do it here since calling delete_money_package was cached...ugly as hell.
        if (is_numeric($money_key) AND isset($packages[$money_key]))
        {
            unset($packages[$money_key]);
        }

        //this updates a current package
        $packages[$money] = $price;

        //order from lowest to highest number of money
        ksort($packages);

        Model_Config::set_value('general', 'ewallet_money_packages', json_encode($packages));
    }

    public static function wallet_balance_for(Model_User $user)
    {
        $query = DB::select(DB::expr('SUM(amount) total'))
            ->from('transactions')
            ->where('id_user', '=', $user->id_user);

        $result = $query->execute()->as_array();
        $total = (isset($result[0]['total'])) ? $result[0]['total'] : 0;

        return $total;
    }
}
