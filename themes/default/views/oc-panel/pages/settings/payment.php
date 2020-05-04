<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="modal fade" id="modalplan" tabindex="-1" role="dialog" aria-labelledby="modalplan" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title text-center"><?=__('Featured Plan')?></h5>
            </div>
            <?=FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'config'))?>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="text" name="featured_days" placeholder="<?=__('Days')?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="featured_price" placeholder="<?=i18n::format_currency(0,core::config('payment.paypal_currency'))?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=__('Save plan')?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Close')?></button>
                </div>
                <input  type="hidden" name="featured_days_key">
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?=Form::errors()?>

<h1 class="page-header page-title">
    <?=__('Payments Configuration')?>
    <a target="_blank" href="https://docs.yclas.com/setup-payment-gateways/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
<hr>

<?if (Core::extra_features() == FALSE):?>
    <div class="alert alert-info fade in">
        <p>
            <strong><?=__('Heads Up!')?></strong>
            <?=__('Payment gateways are only available with PRO version!').'<br>'.__('Upgrade your Yclas site to activate this feature.')?>
        </p>
        <p>
            <a class="btn btn-info" href="https://yclas.com/self-hosted.html">
                <?=__('Upgrade to PRO now')?>
            </a>
        </p>
    </div>
<?endif?>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <?=FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'config', 'enctype'=>'multipart/form-data'))?>
            <div>
                <div>
                    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left" id="tab-settings">
                        <li class="active">
                            <a data-toggle="tab" href="#tabSettingsPaymentGeneral" aria-expanded="true"><?=__('General')?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentEscrow" aria-expanded="false">Escrow</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentStripe" aria-expanded="false">Stripe</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPaypal" aria-expanded="false">Paypal</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPayment2checkout" aria-expanded="false">2checkout</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentAuthorize" aria-expanded="false">Authorize</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPaymill" aria-expanded="false">Paymill</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentBitpay" aria-expanded="false">Bitpay</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPaysbuy" aria-expanded="false">Paysbuy</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentSecurepay" aria-expanded="false">Securepay</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentRobokassa" aria-expanded="false">Robokassa</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPaguelofacil" aria-expanded="false">Paguelofacil</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPaytabs" aria-expanded="false">Paytabs</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPayfast" aria-expanded="false">Payfast</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentMercadoPago" aria-expanded="false">MercadoPago</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentZenith" aria-expanded="false">Zenith</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPayline" aria-expanded="false">Payline</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentSerfinsa" aria-expanded="false">Serfinsa</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsPaymentPreventFraud" aria-expanded="false"><?=__('Prevent Fraud')?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tabSettingsPaymentGeneral" class="tab-pane active fade">
                            <h4>
                                <?=__('General payment configuration')?>
                            </h4>
                            <hr>
                    <?foreach ($config as $c):?>
                        <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                    <?endforeach?>

                    <div class="form-group">
                        <?=FORM::label($forms['paypal_currency']['key'], __('Payment currency'), array('class'=>'control-label', 'for'=>$forms['paypal_currency']['key']))?>
                        <?=Form::select($forms['paypal_currency']['key'], i18n::currencies(), $forms['paypal_currency']['value'])?>
                        <span class="help-block">
                            <?=__("Please be sure you are using a currency that your payment gateway supports.")?>
                        </span>
                    </div>

                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['to_featured']['key'], __('Featured Ads'), array('class'=>'control-label', 'for'=>$forms['to_featured']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['to_featured']['key'], 1, (bool) $forms['to_featured']['value'], array('id' => $forms['to_featured']['key'].'1'))?>
                            <?=Form::label($forms['to_featured']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['to_featured']['key'], 0, ! (bool) $forms['to_featured']['value'], array('id' => $forms['to_featured']['key'].'0'))?>
                            <?=Form::label($forms['to_featured']['key'].'0', __('Disabled'))?>
                        </div>
                        <span class="help-block">
                            <?=__("Featured ads will be highlighted for a defined number of days.")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['to_top']['key'], __('Featured Plans'), array('class'=>'control-label', 'for'=>$forms['to_top']['key']))?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-create-featured-plan/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <?if (is_array($featured_plans)):?>
                            <ul class="list-unstyled">
                                <?$i=0;foreach ($featured_plans as $days => $price):?>
                                    <div class="form-control-static">
                                        <div class="btn-group" style="margin-right:10px;">
                                            <button type="button" class="btn btn-xs btn-warning plan-edit" data-days="<?=$days?>" data-price="<?=$price?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
                                            <?if($i>0):?>
                                                <a class="btn btn-xs btn-danger plan-delete" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))?>?delete_plan=<?=$days?>" data-toggle="confirmation" data-btnOkLabel="<?=__('Yes, definitely!')?>" data-btnCancelLabel="<?=__('No way!')?>" title="<?=__('Delete?')?>" data-text="<?=__('Are you sure you want to delete this plan??')?>"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
                                            <?endif?>
                                        </div>
                                        <?=$days?> <?=__('Days')?> - <?=i18n::format_currency($price,core::config('payment.paypal_currency'))?>
                                    </div>
                                <?$i++;endforeach?>
                            </ul>
                        <?endif?>
                        <button type="button" class="btn btn-primary btn-xs plan-add btn-icon-left" data-toggle="modal" data-target="#modalplan">
                            <i class="fa fa-plus-circle"></i><?=__('Add a plan')?>
                        </button>
                    </div>

                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['to_top']['key'], __('Bring to top Ad'), array('class'=>'control-label', 'for'=>$forms['to_top']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['to_top']['key'], 1, (bool) $forms['to_top']['value'], array('id' => $forms['to_top']['key'].'1'))?>
                            <?=Form::label($forms['to_top']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['to_top']['key'], 0, ! (bool) $forms['to_top']['value'], array('id' => $forms['to_top']['key'].'0'))?>
                            <?=Form::label($forms['to_top']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['pay_to_go_on_top']['key'], __('To top price'), array('class'=>'control-label', 'for'=>$forms['pay_to_go_on_top']['key']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['pay_to_go_on_top']['key'], $forms['pay_to_go_on_top']['value'], array(
                                    'placeholder' => "",
                                    'class' => 'tips form-control col-sm-3',
                                    'id' => $forms['pay_to_go_on_top']['key'],
                                    'data-rule-number' => 'true',
                                ));?>
                            <span class="input-group-addon"><?=core::config('payment.paypal_currency')?></span>
                        </div>
                        <span class="help-block">
                            <?=__("How much the user needs to pay to top up an Ad")?>
                        </span>
                    </div>

                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['alternative']['key'], __('Alternative Payment'), array('class'=>'control-label', 'for'=>$forms['alternative']['key']))?>
                        <?=FORM::select($forms['alternative']['key'], $pages, $forms['alternative']['value'], array(
                            'class' => 'tips form-control',
                            'id' => $forms['alternative']['key'],
                            ))?>
                        <span class="help-block">
                            <?=__("A button with the page title appears next to other pay button, onclick model opens with description.")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['vat_country']['key'], __('VAT Country'), array('class'=>'control-label', 'for'=>$forms['vat_country']['key']))?>
                        <?=Form::select($forms['vat_country']['key'], EUVAT::countries(), $forms['vat_country']['value'])?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['vat_number']['key'], __('VAT Number'), array('class'=>'control-label', 'for'=>$forms['vat_number']['key']))?>
                        <?=FORM::input($forms['vat_number']['key'], $forms['vat_number']['value'], array(
                            'placeholder' => "VAT number",
                            'title' => "Enter the VAT number without the two-letter country code",
                            'class' => 'tips form-control',
                            'id' => $forms['vat_number']['key'],
                        ))?>
                        <span class="help-block">
                            <?=__("Enter the VAT number without the two-letter country code.")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['vat_non_eu']['key'], __('VAT rate only for Non-EU countries'), array('class'=>'control-label', 'for'=>$forms['vat_non_eu']['key']))?>
                        <?=FORM::input($forms['vat_non_eu']['key'], $forms['vat_non_eu']['value'], array(
                            'placeholder' => "Enter the VAT rate",
                            'title' => "Enter the VAT rate, only if VAT Country is not an EU country",
                            'class' => 'tips form-control',
                            'id' => $forms['vat_non_eu']['key'],
                        ))?>
                        <span class="help-block">
                            <?=__("Enter the VAT rate, only if VAT Country is not an EU country. VAT rate needs to be a decimal number, with or without a decimal point.")?>
                        </span>
                    </div>

                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['stock']['key'], __('Stock control'), array('class'=>'control-label', 'for'=>$forms['stock']['key']))?>
                        <a target="_blank" href="https://docs.yclas.com/pay-directly-from-ad/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['stock']['key'], 1, (bool) $forms['stock']['value'], array('id' => $forms['stock']['key'].'1'))?>
                            <?=Form::label($forms['stock']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['stock']['key'], 0, ! (bool) $forms['stock']['value'], array('id' => $forms['stock']['key'].'0'))?>
                            <?=Form::label($forms['stock']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentEscrow" class="tab-pane fade">
                    <h4>
                        <?=__('Escrow')?>
                    </h4>
                    <hr>

                    <div class="form-group">

                        <?=FORM::label($forms['escrow_pay']['key'], __('Escrow Pay'), array('class'=>'control-label', 'for'=>$forms['escrow_pay']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['escrow_pay']['key'], 1, (bool) $forms['escrow_pay']['value'], array('id' => $forms['escrow_pay']['key'].'1'))?>
                            <?=Form::label($forms['escrow_pay']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['escrow_pay']['key'], 0, ! (bool) $forms['escrow_pay']['value'], array('id' => $forms['escrow_pay']['key'].'0'))?>
                            <?=Form::label($forms['escrow_pay']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['escrow_sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['escrow_sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['escrow_sandbox']['key'], 1, (bool) $forms['escrow_sandbox']['value'], array('id' => $forms['escrow_sandbox']['key'].'1'))?>
                            <?=Form::label($forms['escrow_sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['escrow_sandbox']['key'], 0, ! (bool) $forms['escrow_sandbox']['value'], array('id' => $forms['escrow_sandbox']['key'].'0'))?>
                            <?=Form::label($forms['escrow_sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentStripe" class="tab-pane fade">
                    <h4>
                        <?=__('Stripe')?>
                        <a target="_blank" href="https://docs.yclas.com/stripe/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            To get paid via Credit card you can also use a Stripe account. It's free to register. They charge 2'95% of any sale.
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success" target="_blank" href="https://stripe.com">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at Stripe
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_private']['key'], __('Stripe private key'), array('class'=>'control-label', 'for'=>$forms['stripe_private']['key']))?>
                        <?=FORM::input($forms['stripe_private']['key'], $forms['stripe_private']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['stripe_private']['key'],
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_public']['key'], __('Stripe public key'), array('class'=>'control-label', 'for'=>$forms['stripe_public']['key']))?>
                        <?=FORM::input($forms['stripe_public']['key'], $forms['stripe_public']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['stripe_public']['key'],
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_address']['key'], __('Requires address to pay for extra security'), array('class'=>'control-label', 'for'=>$forms['stripe_address']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['stripe_address']['key'], 1, (bool) $forms['stripe_address']['value'], array('id' => $forms['stripe_address']['key'].'1'))?>
                            <?=Form::label($forms['stripe_address']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['stripe_address']['key'], 0, ! (bool) $forms['stripe_address']['value'], array('id' => $forms['stripe_address']['key'].'0'))?>
                            <?=Form::label($forms['stripe_address']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_alipay']['key'], __('Accept Alipay payments'), array('class'=>'control-label', 'for'=>$forms['stripe_alipay']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['stripe_alipay']['key'], 1, (bool) $forms['stripe_alipay']['value'], array('id' => $forms['stripe_alipay']['key'].'1'))?>
                            <?=Form::label($forms['stripe_alipay']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['stripe_alipay']['key'], 0, ! (bool) $forms['stripe_alipay']['value'], array('id' => $forms['stripe_alipay']['key'].'0'))?>
                            <?=Form::label($forms['stripe_alipay']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_3d_secure']['key'], __('Requires 3D security').' - BETA', array('class'=>'control-label', 'for'=>$forms['stripe_3d_secure']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['stripe_3d_secure']['key'], 1, (bool) $forms['stripe_3d_secure']['value'], array('id' => $forms['stripe_3d_secure']['key'].'1'))?>
                            <?=Form::label($forms['stripe_3d_secure']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['stripe_3d_secure']['key'], 0, ! (bool) $forms['stripe_3d_secure']['value'], array('id' => $forms['stripe_3d_secure']['key'].'0'))?>
                            <?=Form::label($forms['stripe_3d_secure']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_legacy']['key'], __('Legacy Checkout'), array('class'=>'control-label', 'for'=>$forms['stripe_legacy']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['stripe_legacy']['key'], 1, (bool) $forms['stripe_legacy']['value'], array('id' => $forms['stripe_legacy']['key'].'1'))?>
                            <?=Form::label($forms['stripe_legacy']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['stripe_legacy']['key'], 0, ! (bool) $forms['stripe_legacy']['value'], array('id' => $forms['stripe_legacy']['key'].'0'))?>
                            <?=Form::label($forms['stripe_legacy']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            Get a fee from each sale made by your customers, using <a target="_blank" href="https://stripe.com/connect">Stripe Connect</a>
                        </p>
                    </div>
                    <div class="form-group">

                        <?=FORM::label($forms['stripe_connect']['key'], __('Activate Stripe Connect'), array('class'=>'control-label', 'for'=>$forms['stripe_connect']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['stripe_connect']['key'], 1, (bool) $forms['stripe_connect']['value'], array('id' => $forms['stripe_connect']['key'].'1'))?>
                            <?=Form::label($forms['stripe_connect']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['stripe_connect']['key'], 0, ! (bool) $forms['stripe_connect']['value'], array('id' => $forms['stripe_connect']['key'].'0'))?>
                            <?=Form::label($forms['stripe_connect']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_clientid']['key'], __('Stripe client id').' <a target="_blank" href="https://dashboard.stripe.com/account/applications/settings">Get Key</a>', array('class'=>'control-label', 'for'=>$forms['stripe_clientid']['key']))?>
                        <?=FORM::input($forms['stripe_clientid']['key'], $forms['stripe_clientid']['value'], array(
                                    'placeholder' => "",
                                    'class' => 'tips form-control',
                                    'id' => $forms['stripe_clientid']['key'],
                                    ))?>
                        <span class="help-block">
                            <?=__("Stripe client id").' Redirect URL: '.Route::url('default', array('controller'=>'stripe','action'=>'connect','id'=>'now'))?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['stripe_appfee']['key'], __('Application fee %'), array('class'=>'control-label', 'for'=>$forms['stripe_appfee']['key']))?>
                        <?=FORM::input($forms['stripe_appfee']['key'], $forms['stripe_appfee']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['stripe_appfee']['key'],
                            'type' => 'number',
                        ))?>
                        <span class="help-block">
                            <?=__("How much you charge the seller in percentage.")?>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                        <div id="tabSettingsPaymentPaypal" class="tab-pane fade">
                    <h4>Paypal</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['paypal_account']['key'], __('Paypal account'), array('class'=>'control-label', 'for'=>$forms['paypal_account']['key']))?>
                        <?=FORM::hidden($forms['paypal_account']['key'], 0);?>
                        <?=FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
                            'placeholder' => "some@email.com",
                            'class' => 'tips form-control',
                            'id' => $forms['paypal_account']['key'],
                            'data-original-title'=> __("Paypal mail address"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("The paypal email address where the payments will be sent"),
                        ))?>
                        <span class="help-block">
                            <?=__("The paypal email address where the payments will be sent")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['sandbox']['key'], 1, (bool) $forms['sandbox']['value'], array('id' => $forms['sandbox']['key'].'1'))?>
                            <?=Form::label($forms['sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['sandbox']['key'], 0, ! (bool) $forms['sandbox']['value'], array('id' => $forms['sandbox']['key'].'0'))?>
                            <?=Form::label($forms['sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paypal_seller']['key'], __('Buy Now button'), array('class'=>'control-label', 'for'=>$forms['paypal_seller']['key']))?>
                        <a target="_blank" href="https://docs.yclas.com/pay-directly-from-ad/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['paypal_seller']['key'], 1, (bool) $forms['paypal_seller']['value'], array('id' => $forms['paypal_seller']['key'].'1'))?>
                            <?=Form::label($forms['paypal_seller']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['paypal_seller']['key'], 0, ! (bool) $forms['paypal_seller']['value'], array('id' => $forms['paypal_seller']['key'].'0'))?>
                            <?=Form::label($forms['paypal_seller']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                    <div id="tabSettingsPayment2checkout" class="tab-pane fade">
                    <h4>2checkout
                        <a target="_blank" href="https://docs.yclas.com/2checkout-configuration/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            <?=sprintf(__('To get paid via Credit card you need a %s account'),'2checkout')?>
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://www.2checkout.com/referral?r=6008d8b2c2">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at 2checkout
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['twocheckout_sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['twocheckout_sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['twocheckout_sandbox']['key'], 1, (bool) $forms['twocheckout_sandbox']['value'], array('id' => $forms['twocheckout_sandbox']['key'].'1'))?>
                            <?=Form::label($forms['twocheckout_sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['twocheckout_sandbox']['key'], 0, ! (bool) $forms['twocheckout_sandbox']['value'], array('id' => $forms['twocheckout_sandbox']['key'].'0'))?>
                            <?=Form::label($forms['twocheckout_sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['twocheckout_sid']['key'], __('Account Number'), array('class'=>'control-label', 'for'=>$forms['twocheckout_sid']['key']))?>
                        <?=FORM::input($forms['twocheckout_sid']['key'], $forms['twocheckout_sid']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['twocheckout_sid']['key'],
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['twocheckout_secretword']['key'], __('Secret Word'), array('class'=>'control-label', 'for'=>$forms['twocheckout_secretword']['key']))?>
                        <?=FORM::input($forms['twocheckout_secretword']['key'], $forms['twocheckout_secretword']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['twocheckout_secretword']['key']
                        ))?>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                        <div id="tabSettingsPaymentAuthorize" class="tab-pane fade">
                    <h4>Authorize.net</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            <?=sprintf(__('To get paid via Credit card you need a %s account'),'Authorize.net')?>. <?=__('You will need also a SSL certificate')?>, <a href="https://www.ssl.com/code/49" target="_blank"><?=__('buy your SSL certificate here')?></a>.
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label(NULL, __('Register'), array('class'=>'control-label'))?>
                        <p class="form-control-static">
                            <a class="btn btn-success" target="_blank" href="http://reseller.authorize.net/application/signupnow/?id=AUAffiliate">
                                </i> US/Canada
                            </a>
                            <a class="btn btn-success" target="_blank" href="http://reseller.authorize.net/application/">
                                UK/Europe
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['authorize_sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['authorize_sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['authorize_sandbox']['key'], 1, (bool) $forms['authorize_sandbox']['value'], array('id' => $forms['authorize_sandbox']['key'].'1'))?>
                            <?=Form::label($forms['authorize_sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['authorize_sandbox']['key'], 0, ! (bool) $forms['authorize_sandbox']['value'], array('id' => $forms['authorize_sandbox']['key'].'0'))?>
                            <?=Form::label($forms['authorize_sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['authorize_login']['key'], __('Authorize API Login'), array('class'=>'control-label', 'for'=>$forms['authorize_login']['key']))?>
                        <?=FORM::input($forms['authorize_login']['key'], $forms['authorize_login']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['authorize_login']['key'],
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['authorize_key']['key'], __('Authorize transaction Key'), array('class'=>'control-label', 'for'=>$forms['authorize_key']['key']))?>
                        <?=FORM::input($forms['authorize_key']['key'], $forms['authorize_key']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['authorize_key']['key'],
                        ))?>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                        <div id="tabSettingsPaymentPaymill" class="tab-pane fade">
                    <h4>Paymill</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            To get paid via Credit card you need a Paymill account. It's free to register. They charge 2'95% of any sale.
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://app.paymill.com/en-en/auth/register?referrer=openclassifieds">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at Paymill
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paymill_private']['key'], __('Paymill private key'), array('class'=>'control-label', 'for'=>$forms['paymill_private']['key']))?>
                        <?=FORM::input($forms['paymill_private']['key'], $forms['paymill_private']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['paymill_private']['key'],
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paymill_public']['key'], __('Paymill public key'), array('class'=>'control-label', 'for'=>$forms['paymill_public']['key']))?>
                        <?=FORM::input($forms['paymill_public']['key'], $forms['paymill_public']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['paymill_public']['key'],
                        ))?>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentBitpay" class="tab-pane fade">
                    <h4>Bitpay</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            Accept bitcoins using Bitpay
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://bitpay.com">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at Bitpay
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['bitpay_sandbox']['key'], __('Sandbox'), array('class' => 'control-label', 'for' => $forms['bitpay_sandbox']['key'])) ?>
                        <div class="radio radio-primary">
                            <?= Form::radio($forms['bitpay_sandbox']['key'], 1, (bool)$forms['bitpay_sandbox']['value'], array('id' => $forms['bitpay_sandbox']['key'] . '1')) ?>
                            <?= Form::label($forms['bitpay_sandbox']['key'] . '1', __('Enabled')) ?>
                            <?= Form::radio($forms['bitpay_sandbox']['key'], 0, !(bool)$forms['bitpay_sandbox']['value'], array('id' => $forms['bitpay_sandbox']['key'] . '0')) ?>
                            <?= Form::label($forms['bitpay_sandbox']['key'] . '0', __('Disabled')) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['bitpay_pairing_code']['key'], __('Bitpay pairing code'), array('class' => 'control-label', 'for' => $forms['bitpay_pairing_code']['key'])) ?>
                        <?= FORM::input($forms['bitpay_pairing_code']['key'], $forms['bitpay_pairing_code']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['bitpay_pairing_code']['key'],
                            'data-content' => __("Bitpay pairing code"),
                        )) ?>
                    </div>

                    <hr>
                    <?= FORM::button('submit', __('Save'), array('type' => 'submit', 'class' => 'btn btn-primary', 'action' => Route::url('oc-panel', array('controller' => 'settings', 'action' => 'payment')))) ?>
                </div>

                        <div id="tabSettingsPaymentPaysbuy" class="tab-pane fade">
                    <h4>Paysbuy</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            Accept BAHT using Paysbuy
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success" target="_blank" href="https://paysbuy.com">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at Paysbuy
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paysbuy']['key'], __('Paysbuy account'), array('class'=>'control-label', 'for'=>$forms['paysbuy']['key']))?>
                        <?=FORM::input($forms['paysbuy']['key'], $forms['paysbuy']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['paysbuy']['key']
                        ))?>
                        <span class="help-block">
                            <?=__("Paysbuy account email")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paysbuy_sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['paysbuy_sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['paysbuy_sandbox']['key'], 1, (bool) $forms['paysbuy_sandbox']['value'], array('id' => $forms['paysbuy_sandbox']['key'].'1'))?>
                            <?=Form::label($forms['paysbuy_sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['paysbuy_sandbox']['key'], 0, ! (bool) $forms['paysbuy_sandbox']['value'], array('id' => $forms['paysbuy_sandbox']['key'].'0'))?>
                            <?=Form::label($forms['paysbuy_sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentSecurepay" class="tab-pane fade">
                    <h4>SecurePay</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            Accept card payments with SecurePay, specialized in Australia
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success" target="_blank" href="https://www.securepay.com.au/">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at SecurePay
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['securepay_merchant']['key'], __('Merchant ID'), array('class'=>'control-label', 'for'=>$forms['securepay_merchant']['key']))?>
                        <?=FORM::input($forms['securepay_merchant']['key'], $forms['securepay_merchant']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['securepay_merchant']['key']
                        ))?>
                        <span class="help-block">
                            <?=__("Merchant ID")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['securepay_password']['key'], __('Password'), array('class'=>'control-label', 'for'=>$forms['securepay_password']['key']))?>
                        <?=FORM::input($forms['securepay_password']['key'], $forms['securepay_password']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['securepay_password']['key']
                        ))?>
                        <span class="help-block">
                            <?=__("Password")?>
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['securepay_testing']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['securepay_testing']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['securepay_testing']['key'], 1, (bool) $forms['securepay_testing']['value'], array('id' => $forms['securepay_testing']['key'].'1'))?>
                            <?=Form::label($forms['securepay_testing']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['securepay_testing']['key'], 0, ! (bool) $forms['securepay_testing']['value'], array('id' => $forms['securepay_testing']['key'].'0'))?>
                            <?=Form::label($forms['securepay_testing']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentRobokassa" class="tab-pane fade">
                    <h4>Robokassa</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            <?=__('Accept payments with Robokassa, specialized in Russia market')?>
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success" target="_blank" href="http://robokassa.ru/">
                                <i class="glyphicon glyphicon-pencil"></i> <?=__('Register for free at Robokassa')?>
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['robokassa_login']['key'], 'Shop identifier', array('class'=>'control-label', 'for'=>$forms['robokassa_login']['key']))?>
                        <?=FORM::input($forms['robokassa_login']['key'], $forms['robokassa_login']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['robokassa_login']['key']
                        ))?>
                        <span class="help-block">
                            Shop identifier
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['robokassa_pass1']['key'], __('Password').' 1', array('class'=>'control-label', 'for'=>$forms['robokassa_pass1']['key']))?>
                        <?=FORM::input($forms['robokassa_pass1']['key'], $forms['robokassa_pass1']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['robokassa_pass1']['key']
                        ))?>
                        <span class="help-block">
                            <?=__("Password")?> 1
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['robokassa_pass2']['key'], __('Password').' 2', array('class'=>'control-label', 'for'=>$forms['robokassa_pass2']['key']))?>
                        <?=FORM::input($forms['robokassa_pass2']['key'], $forms['robokassa_pass2']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['robokassa_pass2']['key']
                        ))?>
                        <span class="help-block">
                            <?=__("Password")?> 2
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['robokassa_testing']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['robokassa_testing']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['robokassa_testing']['key'], 1, (bool) $forms['robokassa_testing']['value'], array('id' => $forms['robokassa_testing']['key'].'1'))?>
                            <?=Form::label($forms['robokassa_testing']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['robokassa_testing']['key'], 0, ! (bool) $forms['robokassa_testing']['value'], array('id' => $forms['robokassa_testing']['key'].'0'))?>
                            <?=Form::label($forms['robokassa_testing']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label('', __('Integration URIs'), array('class'=>'control-label'))?>
                        <span class="help-block">
                            Result URL <pre><?=Route::url('default',array('controller'=>'robokassa', 'action'=>'result','id'=>'1'))?></pre>
                            Success URL <pre><?=Route::url('default',array('controller'=>'robokassa', 'action'=>'success','id'=>'1'))?></pre>
                            Fail URL <pre><?=Route::url('default',array('controller'=>'robokassa', 'action'=>'fail','id'=>'1'))?></pre>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentPaguelofacil" class="tab-pane fade">
                    <h4>Paguelofacil</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['paguelofacil_cclw']['key'], 'CCLW', array('class'=>'control-label', 'for'=>$forms['paguelofacil_cclw']['key']))?>
                        <?=FORM::input($forms['paguelofacil_cclw']['key'], $forms['paguelofacil_cclw']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['paguelofacil_cclw']['key']
                        ))?>
                        <span class="help-block">
                            CCLW
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paguelofacil_testing']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['paguelofacil_testing']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['paguelofacil_testing']['key'], 1, (bool) $forms['paguelofacil_testing']['value'], array('id' => $forms['paguelofacil_testing']['key'].'1'))?>
                            <?=Form::label($forms['paguelofacil_testing']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['paguelofacil_testing']['key'], 0, ! (bool) $forms['paguelofacil_testing']['value'], array('id' => $forms['paguelofacil_testing']['key'].'0'))?>
                            <?=Form::label($forms['paguelofacil_testing']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label('', __('Integration URIs'), array('class'=>'control-label'))?>
                        <span class="help-block">
                            Result URL <pre><?=Route::url('default',array('controller'=>'paguelofacil', 'action'=>'result','id'=>core::config('general.api_key')))?></pre>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentPaytabs" class="tab-pane fade">
                    <h4>Paytabs</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['paytabs_merchant_email']['key'], 'Merchant Email', array('class'=>'control-label', 'for'=>$forms['paytabs_merchant_email']['key']))?>
                        <?=FORM::input($forms['paytabs_merchant_email']['key'], $forms['paytabs_merchant_email']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['paytabs_merchant_email']['key']
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['paytabs_secret_key']['key'], 'Secret Key', array('class'=>'control-label', 'for'=>$forms['paytabs_secret_key']['key']))?>
                        <?=FORM::input($forms['paytabs_secret_key']['key'], $forms['paytabs_secret_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['paytabs_secret_key']['key']
                        ))?>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentPayfast" class="tab-pane fade">
                    <h4>Payfast</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['payfast_merchant_id']['key'], 'Merchant ID', array('class'=>'control-label', 'for'=>$forms['payfast_merchant_id']['key']))?>
                        <?=FORM::input($forms['payfast_merchant_id']['key'], $forms['payfast_merchant_id']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['payfast_merchant_id']['key']
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['payfast_merchant_key']['key'], 'Merchant Key', array('class'=>'control-label', 'for'=>$forms['payfast_merchant_key']['key']))?>
                        <?=FORM::input($forms['payfast_merchant_key']['key'], $forms['payfast_merchant_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['payfast_merchant_key']['key']
                        ))?>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['payfast_sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['payfast_sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['payfast_sandbox']['key'], 1, (bool) $forms['payfast_sandbox']['value'], array('id' => $forms['payfast_sandbox']['key'].'1'))?>
                            <?=Form::label($forms['payfast_sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['payfast_sandbox']['key'], 0, ! (bool) $forms['payfast_sandbox']['value'], array('id' => $forms['payfast_sandbox']['key'].'0'))?>
                            <?=Form::label($forms['payfast_sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentMercadoPago" class="tab-pane fade">
                    <h4>MercadoPago</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            Get your <strong>CLIENT_ID</strong> and <strong>CLIENT_SECRET</strong> <a href="https://www.mercadopago.com/mla/account/credentials?type=basic">here</a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['mercadopago_client_id']['key'], 'Client ID', array('class'=>'control-label', 'for'=>$forms['mercadopago_client_id']['key']))?>
                        <?=FORM::input($forms['mercadopago_client_id']['key'], $forms['mercadopago_client_id']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['mercadopago_client_id']['key']
                        ))?>
                        <span class="help-block">
                            Client ID
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['mercadopago_client_secret']['key'], 'Client Secret', array('class'=>'control-label', 'for'=>$forms['mercadopago_client_secret']['key']))?>
                        <?=FORM::input($forms['mercadopago_client_secret']['key'], $forms['mercadopago_client_secret']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['mercadopago_client_secret']['key']
                        ))?>
                        <span class="help-block">
                            Client Secret
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentZenith" class="tab-pane fade">
                    <h4>Zenith</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['zenith_merchantid']['key'], 'merchantid', array('class'=>'control-label', 'for'=>$forms['zenith_merchantid']['key']))?>
                        <?=FORM::input($forms['zenith_merchantid']['key'], $forms['zenith_merchantid']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['zenith_merchantid']['key']
                        ))?>
                        <span class="help-block">
                            merchantid
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['zenith_uid']['key'], 'Web service username', array('class'=>'control-label', 'for'=>$forms['zenith_uid']['key']))?>
                        <?=FORM::input($forms['zenith_uid']['key'], $forms['zenith_uid']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['zenith_uid']['key']
                        ))?>
                        <span class="help-block">
                            Web service username
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['zenith_pwd']['key'], 'Web service password', array('class'=>'control-label', 'for'=>$forms['zenith_pwd']['key']))?>
                        <?=FORM::input($forms['zenith_pwd']['key'], $forms['zenith_pwd']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['zenith_pwd']['key']
                        ))?>
                        <span class="help-block">
                            Web service password
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['zenith_testing']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['zenith_testing']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['zenith_testing']['key'], 1, (bool) $forms['zenith_testing']['value'], array('id' => $forms['zenith_testing']['key'].'1'))?>
                            <?=Form::label($forms['zenith_testing']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['zenith_testing']['key'], 0, ! (bool) $forms['zenith_testing']['value'], array('id' => $forms['zenith_testing']['key'].'0'))?>
                            <?=Form::label($forms['zenith_testing']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['zenith_merchant_name']['key'], 'Merchant Name', array('class'=>'control-label', 'for'=>$forms['zenith_merchant_name']['key']))?>
                        <?=FORM::input($forms['zenith_merchant_name']['key'], $forms['zenith_merchant_name']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['zenith_merchant_name']['key']
                        ))?>
                        <span class="help-block">
                            Merchant Name
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['zenith_merchant_phone']['key'], 'Merchant Phone', array('class'=>'control-label', 'for'=>$forms['zenith_merchant_phone']['key']))?>
                        <?=FORM::input($forms['zenith_merchant_phone']['key'], $forms['zenith_merchant_phone']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['zenith_merchant_phone']['key']
                        ))?>
                        <span class="help-block">
                            Merchant Phone
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label('', __('Return URL'), array('class'=>'control-label'))?>
                        <span class="help-block">
                            Result URL <pre><?=Route::url('default',array('controller'=>'zenith', 'action'=>'result','id'=>1))?></pre>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentPayline" class="tab-pane fade">
                    <h4>Payline</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['payline_merchant_id']['key'], 'Merchant ID', array('class'=>'control-label', 'for'=>$forms['payline_merchant_id']['key']))?>
                        <?=FORM::input($forms['payline_merchant_id']['key'], $forms['payline_merchant_id']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['payline_merchant_id']['key']
                        ))?>
                        <span class="help-block">
                            Merchant ID
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['payline_access_key']['key'], 'Access Key', array('class'=>'control-label', 'for'=>$forms['payline_access_key']['key']))?>
                        <?=FORM::input($forms['payline_access_key']['key'], $forms['payline_access_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['payline_access_key']['key']
                        ))?>
                        <span class="help-block">
                            Access Key
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['payline_contract_number']['key'], 'Contract Number', array('class'=>'control-label', 'for'=>$forms['payline_contract_number']['key']))?>
                        <?=FORM::input($forms['payline_contract_number']['key'], $forms['payline_contract_number']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['payline_contract_number']['key']
                        ))?>
                        <span class="help-block">
                            Contract Number
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['payline_testing']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['payline_testing']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['payline_testing']['key'], 1, (bool) $forms['payline_testing']['value'], array('id' => $forms['payline_testing']['key'].'1'))?>
                            <?=Form::label($forms['payline_testing']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['payline_testing']['key'], 0, ! (bool) $forms['payline_testing']['value'], array('id' => $forms['payline_testing']['key'].'0'))?>
                            <?=Form::label($forms['payline_testing']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label('', __('Return URL'), array('class'=>'control-label'))?>
                        <span class="help-block">
                            Result URL <pre><?=Route::url('default',array('controller'=>'payline', 'action'=>'result','id'=>1))?></pre>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentSerfinsa" class="tab-pane fade">
                    <h4>Serfinsa</h4>
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['serfinsa_token']['key'], 'Serfinsa Token', array('class'=>'control-label', 'for'=>$forms['serfinsa_token']['key']))?>
                        <?=FORM::input($forms['serfinsa_token']['key'], $forms['serfinsa_token']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['serfinsa_token']['key']
                        ))?>
                        <span class="help-block">
                            Token
                        </span>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['serfinsa_sandbox']['key'], __('Sandbox'), array('class'=>'control-label', 'for'=>$forms['serfinsa_sandbox']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['serfinsa_sandbox']['key'], 1, (bool) $forms['serfinsa_sandbox']['value'], array('id' => $forms['serfinsa_sandbox']['key'].'1'))?>
                            <?=Form::label($forms['serfinsa_sandbox']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['serfinsa_sandbox']['key'], 0, ! (bool) $forms['serfinsa_sandbox']['value'], array('id' => $forms['serfinsa_sandbox']['key'].'0'))?>
                            <?=Form::label($forms['serfinsa_sandbox']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?=FORM::label('', __('Return URL'), array('class'=>'control-label'))?>
                        <span class="help-block">
                            Result URL <pre><?=Route::url('default',array('controller'=>'serfinsa', 'action'=>'result', 'id'=>core::config('general.api_key')))?></pre>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>

                <div id="tabSettingsPaymentPreventFraud" class="tab-pane fade">
                    <h4>Prevent Fraud</h4>
                    <hr>

                    <div class="form-group">
                        <p class="form-control-static">
                            <?=__('Help prevent card fraud with FraudLabsPro, for Stripe, 2co, Paymill and Authorize.')?>
                        </p>
                        <p class="form-control-static">
                            <a class="btn btn-success btn-icon-left" target="_blank" href="http://www.fraudlabspro.com/?ref=1429">
                                <i class="glyphicon glyphicon-pencil"></i><?=sprintf(__('Register for free at %s'),'FraudLabsPro')?>
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <?=FORM::label($forms['fraudlabspro']['key'], __('FraudLabsPro api key'), array('class'=>'control-label', 'for'=>$forms['fraudlabspro']['key']))?>
                        <?=FORM::input($forms['fraudlabspro']['key'], $forms['fraudlabspro']['value'], array(
                            'placeholder' => "",
                            'class' => 'tips form-control',
                            'id' => $forms['fraudlabspro']['key'],
                        ))?>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>
            </div>
                </div>
            </div>
        </form>
    </div>
</div>
