<li class="list-group-item d-flex justify-content-between lh-condensed">
    <div>
        <h6 class="my-0">#<?= $order->id_product ?> <?=$order->description?></h6>
        <small class="text-muted"><?= Model_Order::product_desc($order->id_product) ?></small>
    </div>
    <span class="text-muted">
        <?= i18n::money_format($order->amount, $order->currency) ?>
    </span>
</li>
<li class="list-group-item d-flex justify-content-between lh-condensed">
    <div>
        <small class="text-muted"><?=_e('Shipping')?></small>
    </div>
    <span class="text-muted">
        <?= i18n::money_format($order->ad->shipping_price(), $order->currency) ?>
    </span>
</li>
