<tr>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900"><?= $coupon->name ?></div>
        <div class="text-sm leading-5 text-gray-500">
            <?if (isset($coupon->produt)):?>
                <?=$coupon->product->title?>
            <?elseif(method_exists('Model_Order','product_desc')):?>
                <?=(($product_desc = Model_Order::product_desc($coupon->id_product)) == '') ? __('Any') : $product_desc?>
            <?else:?>
                <?=$coupon->id_product?>
            <?endif?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?=($coupon->discount_amount==0)?round($coupon->discount_percentage,0).'%':i18n::money_format($coupon->discount_amount)?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?=$coupon->number_coupons?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?=Date::format($coupon->valid_date, core::config('general.date_format'))?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?=Date::format($coupon->created, core::config('general.date_format'))?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
        <a href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$coupon->pk()))?>" class="text-blue-600 hover:text-blue-900">
            <?= __('Edit') ?>
        </a>
    </td>
</tr>
