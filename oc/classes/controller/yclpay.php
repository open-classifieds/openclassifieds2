<?php

/**
* Yclpay class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Oliver <oliver@open-classifieds.com>
* @license GPL v3
*/

class Controller_Yclpay extends Controller{

    public function action_pay()
    {
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        $order = (new Model_Order())
            ->where('id_order', '=', $id_order)
            ->where('status', '=', Model_Order::STATUS_CREATED)
            ->limit(1)
            ->find();

        if (! $order->loaded())
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));

            $this->redirect(Route::url('default', ['controller'=>'ad','action'=>'checkout','id'=>$order->id_order]));
        }

        $transaction = Model_Transaction::charge($order->user, $order, $order->amount);

        if ($transaction === FALSE)
        {
            Alert::set(Alert::ERROR, 'Your payment has been declined');

            $this->redirect(Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]));
        }

        $order->confirm_payment('yclpay');

        Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));

        $this->redirect(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'orders']));
    }
}
