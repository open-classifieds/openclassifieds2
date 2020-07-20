<div class="card bg-light mb-4 border-0">
    <div class="card-body">
        <p class="card-text">
            <?= _e('Your Advertisement can go on top again! For only ') . i18n::format_currency(core::config('payment.pay_to_go_on_top'), core::config('payment.paypal_currency')) ?>
        </p>

        <a class="btn btn-primary" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>">
            <?=_e('Go Top!')?>
        </a>
    </div>

    <div class="card-body border-top">
        <p class="card-text">
            <?= _e('Your Advertisement can go to featured! For only ') . i18n::format_currency(Model_Order::get_featured_price(), core::config('payment.paypal_currency')) ?>
        </p>

        <a class="btn btn-primary" href="<?= Route::url('default', ['action' => 'to_featured', 'controller' => 'ad', 'id' => $ad->id_ad]) ?>">
            <?= _e('Go Featured!') ?>
        </a>
    </div>
</div>
