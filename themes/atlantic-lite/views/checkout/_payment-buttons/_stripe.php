<?php defined('SYSPATH') or die('No direct script access.');?>

<form action="<?=Route::url('default',['controller'=>'stripe','action'=>'pay','id'=>$order->id_order])?>" method="post">
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="<?=Core::config('payment.stripe_public')?>"
        data-label="<?=__('Pay with Card')?>"
        data-name="<?=Model_Order::product_desc($order->id_product)?>"
        data-description="<?=Text::limit_chars(Text::removebbcode($order->description), 30, NULL, TRUE)?>"
        <?if (Auth::instance()->logged_in()):?>
            data-email="<?=Auth::instance()->get_user()->email?>"
        <?endif?>
        data-amount="<?=StripeKO::money_format($order->amount)?>"
        data-currency="<?=$order->currency?>"
        data-locale="auto"
        <?=(Core::config('payment.stripe_address')==TRUE)?'data-address = "true"':''?>
        <?=(Core::config('payment.stripe_alipay')==TRUE)?'data-alipay="true"':''?>
        >
    </script>
</form>
