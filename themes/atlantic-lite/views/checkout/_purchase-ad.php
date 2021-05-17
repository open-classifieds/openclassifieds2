<li class="list-group-item d-flex justify-content-between lh-condensed">
    <div>
        <h6 class="my-0">#<?= $order->id_product ?> <?=$order->description?></h6>
        <small class="text-muted"><?= Model_Order::product_desc($order->id_product) ?></small>
    </div>
    <span class="text-muted">
        <?if ($order->ad->shipping_pickup() AND core::get('shipping_pickup')):?>
            <?= i18n::money_format($order->amount, $order->currency) ?>
        <?else:?>
            <?= i18n::money_format($order->amount - $order->ad->cf_shipping, $order->currency) ?>
        <?endif?>
    </span>
</li>

<li class="list-group-item d-flex justify-content-between lh-condensed">
    <div>
        <h6 class="my-0">
            <?if ($order->ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                <?=_e('Customer Pickup')?>
            <?else:?>
                <?=_e('Shipping')?>
            <?endif?>
        </h6>

        <?if ($order->ad->shipping_pickup()):?>
            <div class="text-muted mt-1">
                <div class="dropdown" style="display:inline-block;">
                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                        <?=_e('Change')?>
                    </button>
                    <ul class="dropdown-menu">
                        <h6 class="dropdown-header"><?=_e('Shipping method')?></h6>
                        <a class="dropdown-item" href="<?=Route::url('default', ['controller'=>'ad', 'action'=>'checkout','id'=>$order->id_order])?>?shipping_pickup=1"><?=_e('Customer Pickup - Free')?></a>
                        <a class="dropdown-item" href="<?=Route::url('default', ['controller'=>'ad', 'action'=>'checkout','id'=>$order->id_order])?>"><?=_e('Shipping')?> – <?=i18n::money_format($order->ad->shipping_price(), $order->currency)?></a>
                    </ul>
                </div>
            </div>
        <?endif?>
    </div>

    <span class="text-muted">
        <?if ($order->ad->shipping_pickup() AND core::get('shipping_pickup')):?>
            <?= i18n::money_format(0, $order->currency) ?>
        <?else:?>
            <?= i18n::money_format($order->ad->cf_shipping, $order->currency) ?>
        <?endif?>
    </span>
</li>
