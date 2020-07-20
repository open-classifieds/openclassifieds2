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
                        <em><?=_e('Date')?>: <?=date(core::config('general.date_format'))?></em>
                        <em><?=_e('Checkout')?> #: <?=$ad->id_ad?></em>
                    </address>
                </div>
            </div>

            <ul class="list-group mb-3">
                <?if($ad->shipping_price()):?>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">#<?= $ad->id_ad ?> <?= $ad->title ?></h6>
                            <small class="text-muted">
                                <?= Model_Order::product_desc(Model_Order::PRODUCT_AD_SELL) ?>
                            </small>
                        </div>
                        <div>
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
                        </div>
                        <span class="text-muted">
                            <?=i18n::money_format($ad->price, $ad->currency())?>
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">
                                <?if ($ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                                    <?=_e('Customer Pickup')?>
                                <?else:?>
                                    <?=_e('Shipping')?>
                                <?endif?>
                            </h6>

                            <?if ($ad->shipping_pickup()):?>
                                <small class="text-muted">
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
                                </small>
                            <?endif?>
                        </div>

                        <span class="text-muted">
                            <?if ($ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                                <?=i18n::money_format(0, $ad->currency())?>
                            <?else:?>
                                <?=i18n::money_format($ad->shipping_price(), $ad->currency())?>
                            <?endif?>
                        </span>
                    </li>
                <? else : ?>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">#<?= $ad->id_ad ?> <?= $ad->title ?></h6>
                            <small class="text-muted">
                                <?= Model_Order::product_desc(Model_Order::PRODUCT_AD_SELL) ?>
                            </small>
                        </div>
                        <div>
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
                        </div>
                        <span class="text-muted">
                            <?=i18n::money_format($ad->price, $ad->currency())?>
                        </span>
                    </li>
                <? endif ?>

                <li class="list-group-item d-flex justify-content-between bg-light">
                    <span><?=_e('Total')?></span>
                    <strong>
                        <?if($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup')):?>
                            <?=i18n::money_format($ad->price, $ad->currency())?>
                        <?elseif($ad->shipping_price()):?>
                            <?=i18n::money_format($ad->price + $ad->shipping_price(), $ad->currency())?>
                        <?else:?>
                            <?=i18n::money_format($ad->price, $ad->currency())?>
                        <?endif?>
                    </strong>
                </li>
            </ul>

            <div class="d-flex justify-content-end">
                <?if ($ad->price>0):?>
                    <?= View::factory('ads/guestcheckout/_pay-buttons', compact('ad')) ?>
                <?else:?>
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
                        <button type="submit" class="btn btn-success"><?=_e('Click to proceed')?></button>
                    </form>
                <?endif?>
            </div>
        </div>
    </div>
</div>

<?if (core::config('payment.fraudlabspro') != ''): ?>
    <?= View::factory('ads/guestcheckout/_fraudlabs-pro') ?>
<?endif?>
