<?php defined('SYSPATH') or die('No direct script access.');?>
<form name="paysbuy" id="paysbuy" method="post" action="<?=$form_action?>?c=true&m=true&j=true&a=true&p=true&psb=true">
<input type="Hidden" Name="psb" value="psb"/>
<input Type="Hidden" Name="biz" value="<?=Core::config('payment.paysbuy')?>"/>
<input Type="Hidden" Name="inv" value="<?=$order->id_order?>"/>
<input Type="Hidden" Name="itm" value="<?=$order->description?>"/>
<input Type="Hidden" Name="amt" value="<?=$order->amount?>"/>
<input Type="Hidden" Name="postURL" value="<?=Route::url('default',array('controller'=>'paysbuy','action'=>'pay','id'=>$order->id_order))?>"/>
<input type="image" src="https://www.paysbuy.com/imgs/S_click2buy.gif" border="0" name="submit" alt="Make it easier,PaySbuy - it's fast,free and secure!"/>
</form >