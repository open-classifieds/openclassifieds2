<?php defined('SYSPATH') or die('No direct script access.');?>

<form action="<?=Route::url('default', ['controller' => 'paymill', 'action' => 'pay', 'id' => $order->id_order])?>" method="post">
    <script
        src="https://button.paymill.com/v1/"
        id="button"
        data-label="<?=__('Pay with Card')?>"
        data-title="<?=Model_Order::product_desc($order->id_product)?>"
        data-description="<?=Text::limit_chars(Text::removebbcode($order->description),30,NULL, TRUE)?>"
        data-amount="<?=Paymill::money_format($order->amount)?>"
        data-currency="<?=$order->currency?>"
        data-submit-button="<?=__('Pay')?> <?=i18n::format_currency($order->amount,$order->currency)?>"
        data-elv="false"
        data-lang="en-GB"
        data-height="36"
        data-width="200"
        data-public-key="<?=Core::config('payment.paymill_public')?>"
        >
    </script>
</form>
