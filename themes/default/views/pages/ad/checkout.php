<?php defined('SYSPATH') or die('No direct script access.');?>
<?if (core::config('payment.fraudlabspro')!=''): ?>
<script type="text/javascript">
document.write(unescape("%3Cscript src='" + ('https:' == document.location.protocol ? 'https://' : 'http://') + "static.fraudlabspro.com/agent.js' type='text/javascript'%3E%3C/script%3E"));
</script>
 
<script type="text/javascript">
try{
    var flp = new FraudLabsPro;
    flp.start({session_id: '<?php echo session_id(); ?>'});
}
catch(e){}
</script>
<?endif?>

<div class="well col-xs-12 col-sm-12 col-md-12">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <address>
                <strong><?=Core::config('general.site_name')?></strong>
                <br>
                <?=Core::config('general.base_url')?>
            </address>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 text-right">
            <p>
                <em><?=__('Date')?>: <?= Date::format($order->created, core::config('general.date_format'))?></em>
                <br>
                <em><?=__('Checkout')?> :# <?=$order->id_order?></em>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            <h1><?=__('Checkout')?></h1>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">#</th>
                    <th><?=__('Product')?></th>
                    <th class="text-center"><?=__('Price')?></th>
                </tr>
            </thead>
            <tbody>
                <?if($order->id_product == Model_Order::PRODUCT_AD_SELL AND isset($order->ad->cf_shipping) AND Valid::numeric($order->ad->cf_shipping) AND $order->ad->cf_shipping > 0):?>
                    <tr>
                        <td class="col-md-1" style="text-align: center"><?=$order->id_product?></td>
                        <td class="col-md-9"><?=$order->description?> <em>(<?=Model_Order::product_desc($order->id_product)?>)</em></td>
                        <td class="col-md-2 text-center"><?=i18n::format_currency($order->amount - $order->ad->cf_shipping, $order->currency)?></td>
                    </tr>
                    <tr>
                        <td class="col-md-1" style="text-align: center"></td>
                        <td class="col-md-9"><?=__('Shipping')?></td>
                        <td class="col-md-2 text-center"><?=i18n::format_currency($order->ad->cf_shipping, $order->currency)?></td>
                    </tr>
                <?else:?>
                    <tr>
                        <td class="col-md-1" style="text-align: center"><?=$order->id_product?></td>
                        <?if (Theme::get('premium')==1):?>
                            <td class="col-md-9">
                                <?=$order->description?> 
                                <em>(<?=Model_Order::product_desc($order->id_product)?> 
                                    <?if ($order->id_product == Model_Order::PRODUCT_TO_FEATURED):?>
                                        <?=$order->featured_days?> <?=__('Days')?>
                                    <?endif?>
                                    )
                                </em>
                                <div class="dropdown" style="display:inline-block;">
                                <?if ($order->id_product == Model_Order::PRODUCT_TO_FEATURED AND is_array($featured_plans=Model_Order::get_featured_plans()) AND count($featured_plans) > 1):?>
                                    <button class="btn btn-xs btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                                        <?=__('Change plan')?> 
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?foreach ($featured_plans as $days => $price):?>
                                            <?if ($order->featured_days != $days):?>
                                                <li>
                                                    <a href="<?=Route::url('default',array('controller'=>'ad', 'action'=>'checkout','id'=>$order->id_order))?>?featured_days=<?=$days?>">
                                                        <small><?=$days?> <?=__('Days')?> - <?=i18n::money_format($price)?></small>
                                                    </a>
                                                </li>
                                            <?endif?>
                                        <?endforeach?>
                                    </ul>
                                <?endif?>
                            </td>
                        <?else :?>
                            <td class="col-md-9"><?=$order->description?> <em>(<?=Model_Order::product_desc($order->id_product)?>)</em></td>
                        <?endif?>
                        <td class="col-md-2 text-center"><?=i18n::format_currency(($order->coupon->loaded())?$order->original_price():$order->amount, $order->currency)?></td>
                    </tr>
                    <?if (Theme::get('premium')==1 AND $order->coupon->loaded()):?>
                        <?$discount = ($order->coupon->discount_amount==0)?($order->original_price() * $order->coupon->discount_percentage/100):$order->coupon->discount_amount;?>
                        <tr>
                            <td class="col-md-1" style="text-align: center">
                                <?=$order->id_coupon?>
                            </td>
                            <td class="col-md-9">
                                <?=__('Coupon')?> '<?=$order->coupon->name?>'
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
                <tr>
                    <td>   </td>
                    <td class="text-right"><h4><strong><?=__('Total')?>: </strong></h4></td>
                    <td class="text-center text-danger"><h4><strong><?=i18n::format_currency($order->amount, $order->currency)?></strong></h4></td>
                </tr>
            </tbody>
        </table>

        <?if ($order->amount>0):?>

        <?if (Core::config('payment.paypal_account')!=''):?>
            <p class="text-right">
                <a class="btn btn-success btn-lg" href="<?=Route::url('default', array('controller'=> 'paypal','action'=>'pay' , 'id' => $order->id_order))?>">
                    <?=__('Pay with Paypal')?> <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </p>
        <?endif?>
        
        <?if ($order->id_product!=Model_Order::PRODUCT_AD_SELL):?>
            <?if ( ($user = Auth::instance()->get_user())!=FALSE AND ($user->id_role == Model_Role::ROLE_ADMIN OR $user->id_role == Model_Role::ROLE_MODERATOR)):?>
                <ul class="list-inline text-right">
                    <li>
                        <a title="<?=__('Mark as paid')?>" class="btn btn-warning" href="<?=Route::url('oc-panel', array('controller'=> 'order', 'action'=>'pay','id'=>$order->id_order))?>">
                            <i class="glyphicon glyphicon-usd"></i> <?=__('Mark as paid')?>
                        </a>
                    </li>
                </ul>
            <?endif?>
            <?if (Theme::get('premium')==1) :?>
                <?=Controller_Authorize::form($order)?>
                <div class="text-right">
                    <ul class="list-inline">
                        <?if(($pm = Paymill::button($order)) != ''):?>
                            <li class="text-right"><?=$pm?></li>
                        <?endif?>
                    </ul>
                </div>
                <div class="text-right">
                    <ul class="list-inline">
                        <?if(($sk = StripeKO::button($order)) != ''):?>
                            <li class="text-right"><?=$sk?></li>
                        <?endif?>
                        <?if(($bp = Bitpay::button($order)) != ''):?>
                            <li class="text-right"><?=$bp?></li>
                        <?endif?>
                        <?if(($two = twocheckout::form($order)) != ''):?>
                            <li class="text-right"><?=$two?></li>
                        <?endif?>
                        <?if(($paysbuy = paysbuy::form($order)) != ''):?>
                            <li class="text-right"><?=$paysbuy?></li>
                        <?endif?>
                        <?if( ($alt = $order->alternative_pay_button()) != ''):?>
                            <li class="text-right"><?=$alt?></li>
                        <?endif?>
                    </ul>
                    <?=View::factory('coupon')?>
                </div>
            <?elseif ( ($alt = $order->alternative_pay_button()) != '') :?>
                <div class="text-right">
                    <ul class="list-inline">
                        <li class="text-right"><?=$alt?></li>
                    </ul>
                </div>
            <?endif?>
        <?endif?>

        <?else:?>
            <ul class="list-inline text-right">
                <li>
                    <a title="<?=__('Click to proceed')?>" class="btn btn-success" href="<?=Route::url('default', array('controller'=> 'ad', 'action'=>'checkoutfree','id'=>$order->id_order))?>">
                        <?=__('Click to proceed')?>
                    </a>
                </li>
                <?=View::factory('coupon')?>
            </ul>
        <?endif?>

    </div>
</div>

 