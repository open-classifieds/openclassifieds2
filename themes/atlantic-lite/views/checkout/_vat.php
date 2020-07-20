<li class="list-group-item d-flex justify-content-between bg-light">
    <div>
        <h6 class="my-0"><?=_e('VAT')?> <?=number_format($order->VAT,2)?>%</h6>
    </div>
    <span class="text-success">
        <?if($order->id_product == Model_Order::PRODUCT_AD_SELL):?>
            <?=i18n::money_format($order->original_price()*$order->VAT/100, $order->currency)?>
        <?else:?>
            <?if(isset($discount)):?>
                <?=i18n::format_currency(($order->original_price()-$discount)*$order->VAT/100, $order->currency)?>
            <?else:?>
                <?=i18n::format_currency($order->original_price()*$order->VAT/100, $order->currency)?>
            <?endif?>
        <?endif?>
    </span>
</li>
