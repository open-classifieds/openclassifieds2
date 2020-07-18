<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="text-right">
    <form action="<?=Route::url('default',array('controller'=>'stripe','action'=>'payguest','id'=>$ad->id_ad))?><?=core::get('shipping_pickup') ? '?shipping_pickup=1' : NULL?>" method="post">
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?=Core::config('payment.stripe_public')?>"
            data-label="<?=__('Pay with Card')?>"
            data-name="<?=$ad->title?>"
            data-description="<?=Text::limit_chars(Text::removebbcode($ad->description), 30, NULL, TRUE)?>"
            data-amount="<?=StripeKO::money_format($ad->price)?>"
            data-currency="<?=$ad->currency()?>"
            data-locale="auto"
            <?=(Core::config('payment.stripe_address')==TRUE)?'data-address = "true"':''?>
            >
        </script>
    </form>
</div>
