<?=StripeKO::button_guest_connect($ad)?>
<?=StripeCheckout::button_guest_connect($ad)?>

<?if (Core::config('payment.paypal_account')!=''):?>
    <p class="text-right">
        <a class="btn btn-success btn-lg" href="<?=Route::url('default', array('controller'=> 'paypal','action'=>'guestpay' , 'id' => $ad->id_ad))?>?<?=http_build_query(['shipping_pickup' => core::get('shipping_pickup'), 'quantity' => core::get('quantity')])?>">
            <?=_e('Pay with Paypal')?> <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </p>
<?endif?>

<?if (Core::config('payment.escrow_pay')):?>
    <p class="text-right">
        <a class="btn btn-success btn-lg" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
            <?=_e('Pay with Escrow')?> <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </p>
<?endif?>
