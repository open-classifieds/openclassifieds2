<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-8">
            <div class="mb-3">
                <h1 class="h2 text-center"><?=_e('Checkout')?></h1>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <address class="d-flex flex-column">
                        <strong><?=Core::config('general.site_name')?></strong>
                        <?=Core::config('general.base_url')?>
                        <?if(isset($order->VAT) AND $order->VAT > 0):?>
                            <em><?=_e('VAT Number')?>: <?=$order->VAT_country?> <?=$order->VAT_number?></em>
                        <?endif?>
                        <em><?=_e('Date')?>: <?= Date::format($order->created, core::config('general.date_format'))?></em>
                        <em><?=_e('Checkout')?> #: <?=$order->id_order?></em>
                    </address>
                </div>

                <div>
                    <div class="d-flex flex-column">
                        <strong><?=$order->user->name?></strong>
                        <span><?=$order->user->email?></span>
                        <?if($order->user->address != NULL):?>
                            <span><?=$order->user->address?></span>
                        <?endif?>
                    </div>
                </div>
            </div>

            <ul class="list-group mb-3">
                <? if($order->id_product == Model_Order::PRODUCT_AD_SELL AND $order->ad->shipping_price()): ?>
                    <?= View::factory('checkout/_purchase-ad', compact('order')) ?>
                <? else: ?>
                    <?= View::factory('checkout/_purchase-product', compact('order')) ?>
                <? endif ?>

                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <h6 class="my-0">#<?=$order->ad->id_ad?> <?=$order->ad->title?></h6>
                    </div>
                </li>

                <?if(isset($order->VAT) AND $order->VAT > 0):?>
                    <?= View::factory('checkout/_vat', compact('order', 'discount')) ?>
                <?endif?>

                <li class="list-group-item d-flex justify-content-between bg-light">
                    <span><?=_e('Total')?></span>
                    <strong>
                        <?if($order->id_product == Model_Order::PRODUCT_AD_SELL):?>
                            <?=i18n::money_format($order->amount, $order->currency)?>
                        <?else:?>
                            <?=i18n::format_currency($order->amount, $order->currency)?>
                        <?endif?>
                    </strong>
                </li>
            </ul>

            <div class="d-flex justify-content-end">
                <?if ($order->amount>0):?>
                    <?= View::factory('checkout/_pay-buttons', compact('order')) ?>
                <?else:?>
                    <a title="<?=__('Click to proceed')?>" class="btn btn-success" href="<?=Route::url('default', array('controller'=> 'ad', 'action'=>'checkoutfree','id'=>$order->id_order))?>">
                        <?=_e('Click to proceed')?>
                    </a>

                    <?=View::factory('coupon')?>
                <?endif?>
            </div>
        </div>
    </div>
</div>

<?if (core::config('payment.fraudlabspro') != ''): ?>
    <?= View::factory('checkout/_fraudlabs-pro') ?>
<?endif?>
