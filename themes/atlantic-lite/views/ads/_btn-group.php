<div class="btn-group" role="group">
    <?if((core::config('payment.paypal_seller')==1 OR Core::config('payment.stripe_connect')==1 OR Core::config('payment.escrow_pay')==1) AND $ad->price != NULL AND $ad->price > 0):?>
        <?if(core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1)):?>
            <?if($ad->status != Model_Ad::STATUS_SOLD):?>
                <a class="btn btn-primary" href="<?=Route::url('default', array('action'=>'buy','controller'=>'ad','id'=>$ad->id_ad))?>">
                    <i class="fas fa-money-bill" aria-hidden="true"></i> <?=_e('Buy Now')?>
                </a>
            <?else:?>
                <a class="btn btn-primary disabled">
                    <i class="fas fa-money-bill" aria-hidden="true"></i> <?=_e('Sold')?>
                </a>
            <?endif?>
        <?endif?>
    <?elseif (isset($ad->cf_file_download) AND !empty($ad->cf_file_download) AND  ( core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1))):?>
        <a class="btn btn-primary" href="<?=$ad->cf_file_download?>">
            <i class="fas fa-download" aria-hidden="true"></i> <?=_e('Download')?>
        </a>
    <?endif?>

    <?if ($ad->can_contact()):?>
        <?if ((core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE) AND !Auth::instance()->logged_in()) :?>
            <a class="btn btn-secondary" data-toggle="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>" data-target="#login-modal">
                <i class="fas fa-envelope"></i> <?=_e('Send Message')?>
            </a>
        <?else : ?>
            <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#contact-modal">
                <i class="fas fa-envelope"></i> <?=_e('Send Message')?>
            </button>
        <?endif?>

        <?= View::factory('components/modal', [
            'modal_id' => 'contact-modal',
            'modal_title' => __('Contact'),
            'modal_body' => View::factory('ads/_contact-form', compact('ad'))
        ]) ?>

        <?if (core::config('advertisement.phone')==1 AND strlen($ad->phone)>1):?>
            <a class="btn btn-secondary" href="tel:<?=$ad->phone?>">
                <span class="fas fa-phone"></span> <?=_e('Phone').': '.$ad->phone?>
            </a>
        <?endif?>
    <?endif?>
</div>
