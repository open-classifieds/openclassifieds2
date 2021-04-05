<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="well col-xs-12 col-sm-12 col-md-12">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <address>
                <strong><?=Core::config('general.site_name')?></strong>
                <br>
                <?=Core::config('general.base_url')?>
                <br>
                <?if(isset($vat) AND $vat > 0):?>
                    <em><?=_e('VAT Number')?>: <?=$vatcountry?> <?=$vatnumber?></em>
                    <br>
                <?endif?>
            </address>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 text-right">
            <p>
                <em><?=_e('Date')?>: <?=date(core::config('general.date_format'))?></em>
                <br>
                <em><?=_e('Checkout')?> :# <?=$ad->id_ad?></em>
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
                    <th>
                        <? if(core::config('payment.stock') == 1) : ?>
                            <?=_e('Quantity')?>
                        <? endif ?>
                    </th>
                    <th class="text-center"><?=_e('Price')?></th>
                </tr>
            </thead>
            <tbody>
                <?if($ad->shipping_price()):?>
                    <tr>
                        <td class="col-md-1" style="text-align: center"><?=$ad->id_ad?></td>
                        <td class="col-md-9"><?=$ad->title?> <em>(<?=Model_Order::product_desc(Model_Order::PRODUCT_AD_SELL)?>)</em></td>
                        <td class="col-md-1 text-center">
                            <? if(core::config('payment.stock') == 1) : ?>
                                <form action="<?=Route::url('default', ['action' => 'guestcheckout', 'controller' => 'ad', 'id' => $ad->id_ad])?>" method="GET">
                                    <select class="disable-select2" name="quantity" id="quantity" onchange="this.form.submit()">
                                        <?foreach(range(1, min($ad->stock, 20)) as $quantity):?>
                                            <option value="<?= $quantity ?>" <?= $quantity == core::get('quantity') ? 'selected' : '' ?>>
                                                <?= $quantity ?>
                                            </option>
                                        <?endforeach?>
                                    </select>
                                </form>
                            <? endif ?>
                        </td>
                        <td class="col-md-1 text-center"><?=i18n::money_format($ad->price, $ad->currency())?></td>
                    </tr>
                    <tr>
                        <td class="col-md-1" style="text-align: center"></td>
                        <td class="col-md-9">
                            <?if ($ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                                <?=_e('Customer Pickup')?>
                            <?else:?>
                                <?=_e('Shipping')?>
                            <?endif?>
                            <?if ($ad->shipping_pickup()):?>
                                <div class="dropdown" style="display:inline-block;">
                                    <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                        <?=_e('Change')?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-header"><?=_e('Shipping method')?></li>
                                        <li><a href="<?=Route::url('default',array('controller'=>'ad', 'action'=>'guestcheckout','id'=>$ad->id_ad))?>?shipping_pickup=1"><?=_e('Customer Pickup - Free')?></a></li>
                                        <li><a href="<?=Route::url('default',array('controller'=>'ad', 'action'=>'guestcheckout','id'=>$ad->id_ad))?>"><?=_e('Shipping')?> – <?=i18n::money_format($ad->shipping_price())?></a></li>
                                    </ul>
                                </div>
                            <?endif?>
                        </td>
                        <td class="col-md-1"></td>
                        <td class="col-md-2 text-center">
                            <?if ($ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                                <?=i18n::money_format(0, $ad->currency())?>
                            <?else:?>
                                <?=i18n::money_format($ad->shipping_price(), $ad->currency())?>
                            <?endif?>
                        </td>
                    </tr>
                <?else:?>
                    <tr>
                        <td class="col-md-1" style="text-align: center"><?=$ad->id_ad?></td>
                        <td class="col-md-9"><?=$ad->title?> <em>(<?=Model_Order::product_desc(Model_Order::PRODUCT_AD_SELL)?>)</em></td>
                        <td class="col-md-1 text-center">
                            <? if(core::config('payment.stock') == 1) : ?>
                                <form action="<?=Route::url('default', ['action' => 'guestcheckout', 'controller' => 'ad', 'id' => $ad->id_ad])?>" method="GET">
                                    <select class="disable-select2" name="quantity" id="quantity" onchange="this.form.submit()">
                                        <?foreach(range(1, min($ad->stock, 20)) as $quantity):?>
                                            <option value="<?= $quantity ?>" <?= $quantity == core::get('quantity') ? 'selected' : '' ?>>
                                                <?= $quantity ?>
                                            </option>
                                        <?endforeach?>
                                    </select>
                                </form>
                            <? endif ?>
                        </td>
                        <td class="col-md-1 text-center">
                            <?=i18n::money_format($ad->price, $ad->currency())?>
                        </td>
                    </tr>
                <?endif?>

                <?if(isset($vat) AND $vat > 0):?>
                    <td class="col-md-1" style="text-align: center"></td>
                    <td class="col-md-9">
                        <em><?=_e('VAT')?> <?=number_format($vat,2)?>%</em>
                    </td>
                    <td class="col-md-2 text-center">
                        <?=i18n::money_format($ad->price*$vat/100, $ad->currency())?>
                    </td>
                <?endif?>

                <?if(isset($vat) AND $vat > 0):?>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><h4><strong><?=_e('Total')?>: </strong></h4></td>
                        <?if($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                            <td class="text-center text-danger"><h4><strong style="white-space: nowrap;"><?=i18n::money_format($ad->price + $ad->price*$vat/100, $ad->currency())?></strong></h4></td>
                        <?elseif($ad->shipping_price()):?>
                            <td class="text-center text-danger"><h4><strong style="white-space: nowrap;"><?=i18n::money_format($ad->price + $ad->shipping_price() + $ad->price*$vat/100, $ad->currency())?></strong></h4></td>
                        <?else:?>
                            <td class="text-center text-danger"><h4><strong style="white-space: nowrap;"><?=i18n::money_format($ad->price + $ad->price*$vat/100, $ad->currency())?></strong></h4></td>
                        <?endif?>
                    </tr>
                <?else:?>
                        <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><h4><strong><?=_e('Total')?>: </strong></h4></td>
                        <?if($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                            <td class="text-center text-danger"><h4><strong style="white-space: nowrap;"><?=i18n::money_format($ad->price, $ad->currency())?></strong></h4></td>
                        <?elseif($ad->shipping_price()):?>
                            <td class="text-center text-danger"><h4><strong style="white-space: nowrap;"><?=i18n::money_format($ad->price + $ad->shipping_price(), $ad->currency())?></strong></h4></td>
                        <?else:?>
                            <td class="text-center text-danger"><h4><strong style="white-space: nowrap;"><?=i18n::money_format($ad->price, $ad->currency())?></strong></h4></td>
                        <?endif?>
                    </tr>
                <?endif?>
            </tbody>
        </table>

        <?if ($ad->price>0):?>

            <?=StripeKO::button_guest_connect($ad)?>
            <?=StripeCheckout::button_guest_connect($ad)?>

            <?if (Core::config('payment.paypal_account')!=''):?>
                <p class="text-right">
                    <a class="btn btn-success btn-lg" href="<?=Route::url('default', array('controller'=> 'paypal','action'=>'guestpay' , 'id' => $ad->id_ad))?>?<?=http_build_query(['shipping_pickup' => core::get('shipping_pickup'), 'quantity' => core::get('quantity')])?>">
                        <?=_e('Pay with Paypal')?> <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </p>
            <?endif?>

            <?if (Core::config('payment.escrow_pay')):?>
                <p class="text-right">
                    <a class="btn btn-success btn-lg" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
                        <?=_e('Pay with Escrow')?> <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </p>
            <?endif?>

        <?else:?>
            <ul class="list-inline text-right">
                <li>
                    <form method="post" action="<?=Route::url('default', array('controller'=> 'ad', 'action'=>'checkoutfree','id'=>$ad->id_ad))?>" class="form-inline">
                        <div class="form-group">
                            <label class="control-label"><?=_e('Email')?></label>
                            <input
                                class="form-control"
                                type="text"
                                name="email"
                                value="<?=Request::current()->post('email')?>"
                                placeholder="<?=__('Email')?>"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary"><?=_e('Click to proceed')?></button>
                    </form>
                </li>
            </ul>
        <?endif?>

    </div>
</div>

<?if (core::config('payment.fraudlabspro')!=''): ?>
<script>
    (function(){
        function s() {
            var e = document.createElement('script');
            e.type = 'text/javascript';
            e.async = true;
            e.src = ('https:' === document.location.protocol ? 'https://' : 'http://') + 'cdn.fraudlabspro.com/s.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(e, s);
        }
        (window.attachEvent) ? window.attachEvent('onload', s) : window.addEventListener('load', s, false);
    })();
</script>
<?endif?>
