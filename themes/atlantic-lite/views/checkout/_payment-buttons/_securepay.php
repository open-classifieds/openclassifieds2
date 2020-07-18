<?php defined('SYSPATH') or die('No direct script access.');?>

<form method="post" action="<?=$url?>">
    <input type="hidden" name="display_receipt" value="no">
    <input type="hidden" name="bill_name" value="transact">
    <input type="hidden" name="merchant_id" value="<?=Core::config('payment.securepay_merchant')?>">
    <input type="hidden" name="primary_ref" value="<?=Securepay::id_order($order->id_order)?>">
    <input type="hidden" name="txn_type" value="0">
    <input type="hidden" name="amount" value="<?=Securepay::money_format($order->amount)?>">
    <input type="hidden" name="currency" value="<?=$order->currency?>">
    <input type="hidden" name="fp_timestamp" value="<?=$timestamp?>">
    <input type="hidden" name="fingerprint" value="<?=$fingerprint?>">
    <input type="hidden" name="return_url" value="<?=Route::url('default',['controller'=>'securepay','action'=>'pay','id'=>$order->id_order])?>">
    <input type="hidden" name="return_url_target" value="parent">
    <?if(Theme::get('logo_url')!=NULL):?>
    <input type="hidden" name="page_header_image" value="<?=Theme::get('logo_url')?>">
    <?endif?>
    <input type="hidden" name="title" value="<?=__('Pay')?> <?=i18n::format_currency($order->amount,$order->currency)?>">
    <input type="hidden" name="primary_ref_name" value="<?=Model_Order::product_desc($order->id_product)?>">
    <input name="submit" class="btn btn-success" type="submit" value="<?=__('Pay with Card')?>" >
</form>
