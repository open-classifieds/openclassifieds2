<?=StripeKO::button_connect($order)?>
<?=StripeCheckout::button_connect($order)?>

<?if (Core::config('payment.paypal_account')!=''):?>
    <a class="btn btn-primary ml-2" href="<?=Route::url('default', array('controller'=> 'paypal','action'=>'pay' , 'id' => $order->id_order))?>">
        <?=_e('Pay with Paypal')?> <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
<?endif?>

<?if (Core::config('payment.escrow_pay')):?>
    <a class="btn btn-primary ml-2" href="<?=Route::url('default', array('controller'=> 'escrow','action'=>'pay' , 'id' => $order->id_order))?>">
        <?=_e('Pay with Escrow')?> <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
<?endif?>

<?if ($order->id_product!=Model_Order::PRODUCT_AD_SELL):?>
    <?if ( ($user = Auth::instance()->get_user())!=FALSE AND ($user->is_admin() OR $user->is_moderator())):?>
        <a title="<?=__('Mark as paid')?>" class="btn btn-primary ml-2" href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'pay','id'=>$order->id_order))?>">
            <?=_e('Mark as paid')?>
        </a>
    <?endif?>

    <?if (Core::extra_features() == TRUE) :?>
        <?=Controller_Authorize::form($order)?>

        <?if(($pm = Paymill::button($order)) != ''):?>
            <?=$pm?>
        <?endif?>

        <?if(($sk = StripeKO::button($order)) != ''):?>
            <?=$sk?>
        <?endif?>

        <?if(($sk = StripeCheckout::button($order)) != ''):?>
            <?=$sk?>
        <?endif?>

        <? if (($bp_v2 = Bitpay::button($order)) != '') : ?>
            <?= $bp_v2 ?>
        <? endif ?>

        <?if(($two = twocheckout::form($order)) != ''):?>
            <?=$two?>
        <?endif?>

        <?if(($paysbuy = paysbuy::form($order)) != ''):?>
            <?=$paysbuy?>
        <?endif?>

        <?if(($securepay = securepay::button($order)) != ''):?>
            <?=$securepay?>
        <?endif?>

        <?if(($robokassa = robokassa::button($order)) != ''):?>
            <?=$robokassa?>
        <?endif?>

        <?if(($paguelofacil = paguelofacil::button($order)) != ''):?>
            <?=$paguelofacil?>
        <?endif?>

        <?if(($paytabs = paytabs::button($order)) != ''):?>
            <?=$paytabs?>
        <?endif?>

        <?if(($payfast = payfast::form($order)) != ''):?>
            <?=$payfast?>
        <?endif?>

        <?if(($mp = MercadoPago::button($order)) != ''):?>
            <?=$mp?>
        <?endif?>

        <?if(($zenith = zenith::button($order)) != ''):?>
            <?=$zenith?>
        <?endif?>

        <?if(($payline = payline::button($order)) != ''):?>
            <?=$payline?>
        <?endif?>

        <?if( ($alt = $order->alternative_pay_button()) != ''):?>
            <?=$alt?>
        <?endif?>

        <?= View::factory('checkout/_coupon') ?>
    <?elseif ( ($alt = $order->alternative_pay_button()) != '') :?>
        <?= $alt ?>
    <?endif?>
<?endif?>
