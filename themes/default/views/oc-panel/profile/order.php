<section id="print"> 
	<div class="well col-xs-12 col-sm-12 col-md-12">
	    <div class="row">
	        <div class="col-xs-6 col-sm-6 col-md-6">
	            <address>
	                <strong><?=Core::config('general.site_name')?></strong>
	                <br>
	                <?=Core::config('general.base_url')?>
	                <br>
		            <?if(isset($order->VAT) AND $order->VAT > 0):?>
		                <em><?=_e('VAT Number')?>: <?=$order->VAT_country?> <?=$order->VAT_number?></em>
		                <br>
		            <?endif?>
		            <em><?=_e('Date')?>: <?= Date::format($order->created, core::config('general.date_format'))?></em>
		            <br>
		            <em><?=_e('Checkout')?> #: <?=$order->id_order?></em>
	            </address>
	        </div>
	        <div class="col-xs-6 col-sm-6 col-md-6 text-right">
	            <p>
	            	<strong><?=$order->user->name?></strong>
	            	<br>
	            	<span><?=$order->user->email?></span>
	            	<br>
	            	<?if($order->user->address != NULL):?>
		            	<span><?=$order->user->address?></span>
		            	<br>
	            	<?endif?>
	            </p>
	        </div>
	    </div>
	    <div class="row">
	        <div class="text-center">
	            <h1><?=_e('Checkout')?></h1>
	        </div>
	        <table class="table table-hover">
	            <thead>
	                <tr>
	                    <th style="text-align: center">#</th>
	                    <th><?=_e('Product')?></th>
	                    <th class="text-center"><?=_e('Price')?></th>
	                </tr>
	            </thead>
	            <tbody>
	                <?if($order->id_product == Model_Order::PRODUCT_AD_SELL AND $order->ad->shipping_price()):?>
	                    <tr>
	                        <td class="col-md-1" style="text-align: center"><?=$order->id_product?></td>
	                        <td class="col-md-9"><?=$order->description?> <em>(<?=Model_Order::product_desc($order->id_product)?>)</em></td>
	                        <td class="col-md-2 text-center"><?=i18n::money_format($order->amount, $order->currency)?></td>
	                    </tr>
	                    <tr>
	                        <td class="col-md-1" style="text-align: center"></td>
	                        <td class="col-md-9"><?=_e('Shipping')?></td>
	                        <td class="col-md-2 text-center"><?=i18n::money_format($order->ad->shipping_price(), $order->currency)?></td>
	                    </tr>
	                <?else:?>
	                    <tr>
	                        <td class="col-md-1" style="text-align: center"><?=$order->id_product?></td>
	                        <?if (Core::extra_features() == TRUE):?>
	                            <td class="col-md-9">
	                                <?=$order->description?> 
	                                <em>(<?=Model_Order::product_desc($order->id_product)?> 
	                                    <?if ($order->id_product == Model_Order::PRODUCT_TO_FEATURED):?>
	                                        <?=$order->featured_days?> <?=_e('Days')?>
	                                    <?endif?>
	                                    )
	                                </em>
	                            </td>
	                        <?else :?>
	                            <td class="col-md-9"><?=$order->description?> <em>(<?=Model_Order::product_desc($order->id_product)?>)</em></td>
	                        <?endif?>
	                        <td class="col-md-2 text-center"><?=($order->id_product == Model_Order::PRODUCT_AD_SELL)?i18n::money_format(($order->coupon->loaded())?$order->original_price():$order->original_price(), $order->currency):i18n::format_currency(($order->coupon->loaded())?$order->original_price():$order->original_price(), $order->currency)?></td>
	                    </tr>
	                    <?if (Core::extra_features() == TRUE AND $order->coupon->loaded()):?>
	                        <?$discount = ($order->coupon->discount_amount==0)?($order->original_price() * $order->coupon->discount_percentage/100):$order->coupon->discount_amount;?>
	                        <tr>
	                            <td class="col-md-1" style="text-align: center">
	                                <?=$order->id_coupon?>
	                            </td>
	                            <td class="col-md-9">
	                                <?=_e('Coupon')?> '<?=$order->coupon->name?>'
	                                <?=sprintf(__('valid until %s'), Date::format($order->coupon->valid_date, core::config('general.date_format')))?>.
	                            </td>
	                            <td class="col-md-2 text-center text-danger">
	                                -<?=i18n::format_currency($discount, $order->currency)?>
	                            </td>
	                        </tr>  
	                    <?endif?>
	                <?endif?>
	                <tr>
	                    <td class="col-md-1" style="text-align: center"><?=$order->ad->id_ad?></td>
	                    <td colspan=2 class="col-md-12">
	                        <em><?=$order->ad->title?></em>
	                    </td>
	                </tr>

	                <?if(isset($order->VAT) AND $order->VAT > 0):?>
	                    <td class="col-md-1" style="text-align: center"></td>
	                    <td class="col-md-9">
	                        <em><?=_e('VAT')?> <?=number_format($order->VAT,2)?>%</em>
	                    </td>
	                    <td class="col-md-2 text-center text-danger">
	                        <?if($order->id_product == Model_Order::PRODUCT_AD_SELL):?>
	                            <?=i18n::money_format($order->original_price()*$order->VAT/100, $order->currency)?>
	                        <?else:?>
	                            <?if(isset($discount)):?>
	                                <?=i18n::format_currency(($order->original_price()-$discount)*$order->VAT/100, $order->currency)?>
	                            <?else:?>
	                                <?=i18n::format_currency($order->original_price()*$order->VAT/100, $order->currency)?>
	                            <?endif?>
	                        <?endif?>
	                    </td>
	                <?endif?>

	                <tr>
	                    <td>   </td>
	                    <td class="text-right"><h4><strong><?=_e('Total')?>: </strong></h4></td>
	                    <?if($order->id_product == Model_Order::PRODUCT_AD_SELL AND $order->ad->shipping_price()):?>
	                        <td class="text-center text-danger"><h4><strong><?=i18n::money_format($order->amount + $order->ad->shipping_price(), $order->currency)?></strong></h4></td>
	                    <?else:?>
	                        <td class="text-center text-danger"><h4><strong><?=($order->id_product == Model_Order::PRODUCT_AD_SELL)?i18n::money_format($order->amount, $order->currency):i18n::format_currency($order->amount, $order->currency)?></strong></h4></td>
	                    <?endif?>
	                </tr>
	            </tbody>
	        </table>

	        <?if( ! core::get('print')):?>
	            <div class="pull-right">
	                <a target="_blank" class="btn btn-xs btn-success" title="<?=__('Print this')?>" href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'order','id'=>$order->id_order)).URL::query(array('print'=>1))?>"><i class="glyphicon glyphicon-print"></i><?=_e('Print this')?></a>
	            </div>
	        <?endif;?>

	    </div>
	</div>
</section>