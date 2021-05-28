<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Mollie class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Payment
 * @author Oliver <oliver@open-classifieds.com>
 * @license GPL v3
 */

class Controller_Mollie extends Controller {

    protected $mollie;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        require_once Kohana::find_file('vendor', 'mollie/vendor/autoload', 'php');

        $this->mollie = new \Mollie\Api\MollieApiClient();

        $this->mollie->setApiKey(Core::config('payment.mollie_api_key'));
    }

    public function action_pay()
    {
        $this->auto_render = FALSE;

        $order = (new Model_Order())
            ->where('id_order', '=', $this->request->param('id'))
            ->where('status', '=', Model_Order::STATUS_CREATED)
            ->limit(1)
            ->find();

        if (! $order->loaded())
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]));
        }

        try {
            $payment = $this->mollie->payments->create([
                'amount' => [
                    'currency' => $order->currency,
                    'value' => (string) Mollie::money_format($order->amount),
                ],
                'description' => $order->description,
                'redirectUrl' => Route::url('default', ['controller' => 'mollie', 'action' => 'thanks', 'id' => $order->id_order]),
                'webhookUrl' => Route::url('default', ['controller' => 'mollie', 'action' => 'webhook', 'id' => $order->id_order]),
                'metadata' => [
                    'order_id' => $order->id_order,
                ],
            ]);
        } catch (Exception $e) {
            Kohana::$log->add(Log::ERROR, 'Mollie: '. $e->getMessage());
        }

        header("Location: " . $payment->getCheckoutUrl(), TRUE, 303);
    }

    public function action_thanks()
    {
        $this->auto_render = FALSE;

        $order = (new Model_Order())
            ->where('id_order', '=', $this->request->param('id'))
            ->where('status', '=', Model_Order::STATUS_CREATED)
            ->limit(1)
            ->find();

        if (! $order->loaded())
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]));
        }

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

        Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));

        $this->redirect(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'orders']));
    }

    public function action_webhook()
    {
        $this->auto_render = FALSE;

        $payment = $this->mollie->payments->get(Core::post('id'));

        $order = (new Model_Order())
            ->where('id_order', '=', $payment->metadata->order_id)
            ->where('status', '=', Model_Order::STATUS_CREATED)
            ->limit(1)
            ->find();

        if (! $order->loaded())
        {
            return;
        }

        if ($payment->isPaid() AND ! $payment->hasRefunds() AND ! $payment->hasChargebacks()) {
            $order->confirm_payment('mollie', Core::post('id'));
        }
    }
}
