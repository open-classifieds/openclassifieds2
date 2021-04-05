<?php defined('SYSPATH') or die('No direct script access.');

/**
* escrow class
*
* @package Open Classifieds
* @subpackage Core
* @category Payment
* @author Oliver <oliver@open-classifieds.com>
* @license GPL v3
*/

class Controller_Escrow extends Controller{

    protected $base_url;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        if (Core::config('payment.escrow_sandbox'))
        {
            $this->base_url = 'https://api.escrow-sandbox.com/';
        }
        else
        {
            $this->base_url = 'https://api.escrow.com/';
        }
    }

	public function action_pay()
	{
		$this->auto_render = FALSE;

		$order = (new Model_Order())
            ->where('id_order','=',$this->request->param('id'))
            ->where('status','=',Model_Order::STATUS_CREATED)
            ->limit(1)->find();

        if (! $order->loaded())
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default'));
        }

        $request = Request::factory($this->base_url . 'integration/pay/2018-03-31')
            ->headers('Content-Type', 'application/json')
            ->method(Request::POST)
            ->body(json_encode([
                'currency' => 'usd',
                'description' => $order->ad->title,
                'return_url' => Route::url('default', ['controller'=>'escrow', 'action'=>'paid', 'id' => $order->id_order]),
                'items' => [
                    [
                        'title' => $order->ad->title,
                        'description' => Text::truncate_html(Text::removebbcode($order->ad->description, TRUE), 400, ''),
                        'type' => 'general_merchandise',
                        'quantity' => '1',
                        'inspection_period' => '259200',
                        'fees' => [
                            [
                                'payer_customer' => 'me',
                                'split' => '1',
                                'type' => 'escrow',
                            ],
                        ],
                        'schedule' => [
                            [
                                'amount' => $order->amount,
                                'beneficiary_customer' => 'me',
                                'payer_customer' => $order->user->email,
                            ],
                        ],
                    ],
                ],
                'parties' => [
                    [
                        'agreed' => 'true',
                        'customer' => $order->user->email,
                        'initiator' => 'false',
                        'role' => 'buyer',
                    ],
                    [
                        'agreed' => 'true',
                        'customer' => 'me',
                        'initiator' => 'true',
                        'role' => 'seller',
                    ],
                ],
            ]));

        $request->client()
            ->options([
                CURLOPT_USERPWD => $order->ad->user->escrow_email . ':' . $order->ad->user->escrow_api_key
            ]);

        $response = $request->execute();

        if ($response->status() !== 201)
        {
            Alert::set(Alert::ERROR, __('Escrow: ') . $response->body());
            $this->redirect(Route::url('default'));
        }

        $transaction = json_decode($response->body());

        $order->paymethod = 'escrow';
        $order->txn_id = json_encode([
            'landing_page' => $transaction->landing_page,
            'transaction_id' => $transaction->transaction_id,
        ]);

        $order->save();

        $this->redirect($transaction->landing_page);
	}

    public function action_paid()
    {
        $this->auto_render = FALSE;

        $order = (new Model_Order())
            ->where('id_order','=',$this->request->param('id'))
            ->where('status','=',Model_Order::STATUS_CREATED)
            ->limit(1)->find();

        if (! $order->loaded())
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default'));
        }

        $transaction = json_decode($order->txn_id);

        $request = Request::factory($this->base_url . '2017-09-01/transaction/' . $transaction->transaction_id)
            ->headers('Content-Type', 'application/json');

        $request->client()
            ->options([
                CURLOPT_USERPWD => $order->ad->user->escrow_email . ':' . $order->ad->user->escrow_api_key
            ]);

        $response = $request->execute();

        if($response->status() !== 200)
        {
            Alert::set(Alert::ERROR, 'We could not connect with Escrow.');
            $this->redirect(Route::url('default'));
        }

        $transaction = json_decode($response->body());

        if (! $transaction->items[0]->schedule[0]->status->secured)
        {
            Alert::set(Alert::WARNING, 'We noticed you did not complete payment yet.');
            $this->redirect(Route::url('oc-panel', ['controller'=>'profile', 'action'=>'orders']));
        }

        $order->confirm_payment('escrow', json_encode([
            'transaction_id' => $transaction->id,
            'status' => [
                'received' => $transaction->items[0]->status->received,
                'rejected_returned' => $transaction->items[0]->status->rejected_returned,
                'rejected' => $transaction->items[0]->status->rejected,
                'received_returned' => $transaction->items[0]->status->received_returned,
                'shipped' => $transaction->items[0]->status->shipped,
                'accepted' => $transaction->items[0]->status->accepted,
                'shipped_returned' => $transaction->items[0]->status->shipped_returned,
                'accepted_returned' => $transaction->items[0]->status->accepted_returned,
                'canceled' => $transaction->items[0]->status->canceled,
            ],
        ]));

        Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
        $this->redirect(Route::url('oc-panel', ['controller'=>'profile', 'action'=>'orders']));
    }
}
