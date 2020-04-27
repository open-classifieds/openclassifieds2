<?php defined('SYSPATH') or die('No direct script access.');?>

<!DOCTYPE HTML>
<html>
<body>
<div style="font-family: Arial; font-size: 20px; text-align: center; margin-top: 200px;">
    <?=_e('Please wait while we transfer you to Paypal');?><br /><br />
    <form name="form1" id="form1" action="<?=$paypal_url;?>" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="cbt" value="Return To <?=$site_name?>">
        <input type="hidden" name="business" value="<?=$paypal_account?>">
        <input type="hidden" name="item_name" value="<?=$item_name?>">
        <input type="hidden" name="item_number" value="<?=$order_id?>">
        <input type="hidden" name="amount" value="<?=$amount?>">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="undefined_quantity" value="1">
        <input type="hidden" name="no_shipping" value="0">
        <input type="hidden" name="shipping" value="0">
        <input type="hidden" name="shipping2" value="0">
        <input type="hidden" name="handling" value="0.00">
        <input type="hidden" name="return" value="<?=$site_url?>">
        <input type="hidden" name="notify_url" value="<?=$notify_url?>">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="custom" value="">
        <input type="hidden" name="currency_code" value="<?=$paypal_currency?>">
        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="bn" value="OpenClassifieds_SP">
        <input type="submit" value="<?=__('Click here if you are not redirected');?>">
    </form>
</div>
<script type="text/javascript">form1.submit();</script>
</body>
</html>
