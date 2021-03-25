<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Razorpay class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Payment
 * @author Oliver <oliver@open-classifieds.com>
 * @license GPL v3
 */

class Controller_Razorpay extends Controller{

    public function action_verify()
    {
        $this->auto_render = FALSE;

        $order = new Model_Order($this->request->param('id'));

        if (! $order->loaded())
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]));
        }

        //its a fraud...lets let him know
        if ( $order->is_fraud() === TRUE )
        {
            Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
            $this->redirect(Route::url('default', ['controller'=>'ad','action'=>'checkout','id'=>$order->id_order]));
        }

        if (empty(Core::post('razorpay_payment_id')))
        {
            Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
            $this->redirect(Route::url('default', ['controller'=>'ad','action'=>'checkout','id'=>$order->id_order]));
        }

        require_once Kohana::find_file('vendor', 'razorpay-php/Razorpay', 'php');

        $api = new \Razorpay\Api\Api(Core::config('payment.razorpay_key_id'), Core::config('payment.razorpay_key_secret'));

        try
        {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $order->txn_id,
                'razorpay_payment_id' => Core::post('razorpay_payment_id'),
                'razorpay_signature' => Core::post('razorpay_signature'),
            ]);
        }
        catch(\Razorpay\Api\Errors\SignatureVerificationError $e)
        {
            // $error = 'Razorpay Error : ' . $e->getMessage();
            Alert::set(Alert::INFO, __('Transaction not successful!'));
            $this->redirect(Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]));
        }

        $order->confirm_payment('razorpay', Core::post('razorpay_payment_id'));

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
        $this->redirect(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'orders']));
    }

}
