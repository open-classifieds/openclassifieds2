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
            <small class="text-muted">
                <div class="dropdown" style="display:inline-block;">
                    <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                        <?=_e('Change')?>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header"><?=_e('Shipping method')?></li>
                        <li><a href="<?=Route::url('default',array('controller'=>'ad', 'action'=>'checkout','id'=>$order->id_order))?>?shipping_pickup=1"><?=_e('Customer Pickup - Free')?></a></li>
                        <li><a href="<?=Route::url('default',array('controller'=>'ad', 'action'=>'checkout','id'=>$order->id_order))?>"><?=_e('Shipping')?> – <?=i18n::money_format($order->ad->shipping_price(), $order->currency)?></a></li>
                    </ul>
                </div>
            </small>
        <?endif?>
    </div>

    <span class="text-muted">
        <?if ($order->ad->shipping_pickup() AND core::get('shipping_pickup')):?>
            <?= i18n::money_format($order->amount, $order->currency) ?>
        <?else:?>
            <?= i18n::money_format($order->amount - $order->ad->cf_shipping, $order->currency) ?>
        <?endif?>
    </span>
</li>
