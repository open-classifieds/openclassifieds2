<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_EWallet extends Auth_Frontcontroller {

    public function action_index()
    {
        if (! Core::config('general.ewallet'))
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        $user = Auth::instance()->get_user();

        $this->template->title = __('My transactions');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My transactions')));
        Controller::$full_width = true;

        $transactions = new Model_Transaction();
        $transactions = $transactions->where('id_user', '=', $user->id_user);


        $pagination = Pagination::factory(array(
            'view' => 'oc-panel/crud/pagination',
            'total_items' => $transactions->count_all(),
        ))->route_params(array(
            'controller' => $this->request->controller(),
            'action' => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $transactions = $transactions->order_by('id_transaction', 'desc')
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();

        $pagination = $pagination->render();

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/transactions', ['transactions' => $transactions, 'pagination' => $pagination]);


    }

    /**
     * Pay for add money, and set new order
     *
     */
    public function action_add_money()
    {
        if (! Core::config('general.ewallet'))
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        if (! Core::config('general.ewallet_add_money'))
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        $id_product = Model_Order::PRODUCT_ADD_MONEY;

        //how much money
        if (!is_numeric($money = Core::request('money')))
        {
            $packages = Model_Transaction::get_money_packages();
            $money = array_keys($packages);
            $money = reset($money);
        }

        //get price for the money
        $amount = Model_Transaction::get_money_price($money);

        $currency = core::config('payment.paypal_currency');

        $order = Model_Order::new_order(NULL, $this->user, $id_product, $amount, $currency, NULL, NULL, $money);

        // redirect to payment
        $this->redirect(Route::url('default', ['controller' =>'ad','action'=>'checkout' ,'id' => $order->id_order]));
    }

}
