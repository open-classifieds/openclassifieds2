<?php defined('SYSPATH') or die('No direct script access.');?>
<form name="payfast" id="payfast" method="post" action="<?=$form_action?>">
    <input type="hidden" Name="merchant_id" value="<?=$info['merchant_id']?>"/>
    <input type="hidden" Name="merchant_key" value="<?=$info['merchant_key']?>"/>
    <input type="hidden" Name="return_url" value="<?=$info['return_url']?>"/>
    <input type="hidden" Name="cancel_url" value="<?=$info['cancel_url']?>"/>
    <input type="hidden" Name="notify_url" value="<?=$info['notify_url']?>"/>
    <input type="hidden" Name="m_payment_id" value="<?=$info['m_payment_id']?>"/>
    <input type="hidden" Name="amount" value="<?=$info['amount']?>"/>
    <input type="hidden" Name="item_name" value="<?=$info['item_name']?>"/>
    <input type="hidden" Name="item_description" value="<?=$info['item_description']?>"/>
    <input type="hidden" Name="signature" value="<?=$info['signature']?>"/>
    <button class="btn btn-info pay-btn tw-w-full" type="submit">
        <span class="fa fa-cart"></span> <?=_e('Pay Now')?>
    </button>
</form >
