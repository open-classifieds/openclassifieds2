<li class="list-group-item d-flex justify-content-between lh-condensed">
    <div>
        <? if(Core::extra_features() == TRUE): ?>
            <h6 class="my-0">#<?= $order->id_product ?> <?=$order->description?></h6>
            <small class="text-muted">
                <?= Model_Order::product_desc($order->id_product) ?>
                <? if($order->id_product == Model_Order::PRODUCT_TO_FEATURED): ?>
                    <?= $order->featured_days?> <?=_e('Days') ?>
                <? endif ?>
            </small>
            <small class="text-muted d-block">
                <div class="dropdown">
                    <?if ($order->id_product == Model_Order::PRODUCT_TO_FEATURED AND is_array($featured_plans=Model_Order::get_featured_plans()) AND core::count($featured_plans) > 1):?>
                        <button class="btn btn-xs btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                            <?=_e('Change plan')?>
                        </button>
                        <ul class="dropdown-menu">
                            <?foreach ($featured_plans as $days => $price):?>
                                <?if ($order->featured_days != $days):?>
                                    <li>
                                        <a href="<?=Route::url('default',array('controller'=>'ad', 'action'=>'checkout','id'=>$order->id_order))?>?featured_days=<?=$days?>">
                                            <small><?=$days?> <?=_e('Days')?> - <?=core::config('payment.paypal_currency')?> <?=$price?></small>
                                        </a>
                                    </li>
                                <?endif?>
                            <?endforeach?>
                        </ul>
                    <?endif?>
                </div>
            </small>
        <? else: ?>
            <h6 class="my-0">#<?= $order->id_product ?> <?= $order->description ?></h6>
            <small class="text-muted">
                <?= Model_Order::product_desc($order->id_product) ?>
            </small>
        <? endif ?>
    </div>
    <span class="text-muted">
        <?=($order->id_product == Model_Order::PRODUCT_AD_SELL)?i18n::money_format(($order->coupon->loaded())?$order->original_price():$order->original_price(), $order->currency):i18n::format_currency(($order->coupon->loaded())?$order->original_price():$order->original_price(), $order->currency)?>
    </span>
</li>

<?if (Core::extra_features() == TRUE AND Model_Coupon::current()->loaded()):?>
    <?$discount = ($order->coupon->discount_amount==0)?($order->original_price() * $order->coupon->discount_percentage/100):$order->coupon->discount_amount;?>
    <li class="list-group-item d-flex justify-content-between bg-light">
        <div class="text-success">
            <h6 class="my-0">#<?=$order->id_coupon?> <?=_e('Coupon')?> '<?=$order->coupon->name?>'</h6>
            <small class="text-muted"><?=sprintf(__('valid until %s'), Date::format($order->coupon->valid_date, core::config('general.date_format')))?>.</small>
        </div>
        <span class="text-success">
            -<?=i18n::format_currency($discount, $order->currency)?>
        </span>
    </li>
<?endif?>
