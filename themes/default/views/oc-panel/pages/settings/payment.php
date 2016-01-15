<?php defined('SYSPATH') or die('No direct script access.');?>


<div class="modal fade" id="modalplan" tabindex="-1" role="dialog" aria-labelledby="modalplan" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title text-center"><?=__('Featured Plan')?></h5>
            </div>
            <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'config'))?>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="text" name="featured_days" placeholder="<?=__('Days')?>">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="featured_price" placeholder="<?=i18n::money_format(0)?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Close')?></button>
                    <button type="submit" class="btn btn-primary"><?=__('Save plan')?></button>
                </div>
                <input  type="hidden" name="featured_days_key">
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

	
<?=Form::errors()?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Payments Configuration')?></h1>
        <span class="page-description"><?=__('List of payment configuration values. Replace input fields with new desired values.')?> <a target="_blank" href="https://docs.yclas.com/setup-payment-gateways/"><?=__('Read more')?></a></span>
    </div>
</div>

<?if (Theme::get('premium')!=1):?>
    <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
        Authorize, Stripe, Paymill and Bitpay <?=__('only available with premium themes!').' '.__('Upgrade your Open Classifieds site to activate this feature.')?>
        <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
    </p>
<?endif?>


<div class="row">
    <div class="col-md-12 col-lg-8">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'config', 'enctype'=>'multipart/form-data'))?>
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__('General Configuration')?></h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <?foreach ($config as $c):?>
                        <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                    <?endforeach?>

                    <div class="form-group">
                        <?= FORM::label($forms['paypal_currency']['key'], __('Site currency'), array('class'=>'control-label col-sm-4', 'for'=>$forms['paypal_currency']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['paypal_currency']['key'], $forms['paypal_currency']['value'], array(
                            'placeholder' => $forms['paypal_currency']['value'], 
                            'class' => 'tips form-control col-sm-3', 
                            'id' => $forms['paypal_currency']['key'], 
                            'data-original-title'=> __("Currency"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Please be sure you are using a currency that your payment gateway supports."),
                            ));?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['to_featured']['key'], __('Featured Ads'), array('class'=>'control-label col-sm-4', 'for'=>$forms['to_featured']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['to_featured']['key'], 0);?>
                                <?= Form::checkbox($forms['to_featured']['key'], 1, (bool) $forms['to_featured']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['to_featured']['key'],
                                'data-original-title'=> __("Featured ads"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Featured ads will be highlighted for a defined number of days."), 
                                ))?>
                                <?= FORM::label($forms['to_featured']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['to_featured']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['to_top']['key'], "<a target='_blank' href='https://docs.yclas.com/how-to-create-featured-plan/'>".__('Featured Plans')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['to_top']['key']))?>
                        <div class="col-sm-8">
                            <?if (is_array($featured_plans)):?>
                                <ul class="list-unstyled">
                                    <?$i=0;foreach ($featured_plans as $days => $price):?>
                                        <li>
                                            <div class="btn-group" style="margin-right:10px;">
                                                <button type="button" class="btn btn-xs btn-warning plan-edit" data-days="<?=$days?>" data-price="<?=$price?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
                                                <?if($i>0):?>
                                                    <a class="btn btn-xs btn-danger plan-delete" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))?>?delete_plan=<?=$days?>"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
                                                <?endif?>
                                            </div>
                                            <?=$days?> <?=__('Days')?> - <?=i18n::money_format($price)?>
                                        </li>
                                    <?$i++;endforeach?>
                                </ul>
                            <?endif?>
                            <button type="button" class="btn btn-primary plan-add btn-icon-left" data-toggle="modal" data-target="#modalplan">
                                <i class="fa fa-plus-circle"></i><?=__('Add a plan')?>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['to_top']['key'], __('Bring to top Ad'), array('class'=>'control-label col-sm-4', 'for'=>$forms['to_top']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::checkbox($forms['to_top']['key'], 1, (bool) $forms['to_top']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['to_top']['key'],
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'', 
                                ))?>
                                <?= FORM::label($forms['to_top']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['to_top']['key']))?>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <?= FORM::label($forms['pay_to_go_on_top']['key'], __('To top price'), array('class'=>'control-label col-sm-4', 'for'=>$forms['pay_to_go_on_top']['key']))?>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <?= FORM::input($forms['pay_to_go_on_top']['key'], $forms['pay_to_go_on_top']['value'], array(
                                    'placeholder' => "", 
                                    'class' => 'tips form-control col-sm-3', 
                                    'id' => $forms['pay_to_go_on_top']['key'],
                                    'data-original-title'=> __("Pricing"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-content'=>__("How much the user needs to pay to top up an Ad"),  
                                    'data-rule-number' => 'true',
                                    ));?> 
                                <span class="input-group-addon"><?=core::config('payment.paypal_currency')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['alternative']['key'], __('Alternative Payment'), array('class'=>'col-md-4 control-label', 'for'=>$forms['alternative']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::select($forms['alternative']['key'], $pages, $forms['alternative']['value'], array( 
                            'class' => 'tips form-control', 
                            'id' => $forms['alternative']['key'], 
                            'data-content'=> __("A button with the page title appears next to other pay button, onclick model opens with description."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Alternative Payment"),
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Paypal</h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    
                    <div class="form-group">
                        <?= FORM::label($forms['paypal_account']['key'], __('Paypal account'), array('class'=>'control-label col-sm-4', 'for'=>$forms['paypal_account']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::hidden($forms['paypal_account']['key'], 0);?>
                            <?= FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
                            'placeholder' => "some@email.com", 
                            'class' => 'tips form-control', 
                            'id' => $forms['paypal_account']['key'],
                            'data-original-title'=> __("Paypal mail address"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("The paypal email address where the payments will be sent"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['sandbox']['key'], __('Sandbox'), array('class'=>'control-label col-sm-4', 'for'=>$forms['sandbox']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['sandbox']['key'], 0);?>
                                <?= Form::checkbox($forms['sandbox']['key'], 1, (bool) $forms['sandbox']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['sandbox']['key'],
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'', 
                                ))?>
                                <?= FORM::label($forms['sandbox']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['sandbox']['key']))?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['paypal_seller']['key'], "<a target='_blank' href='https://docs.yclas.com/pay-directly-from-ad/'>".__('User paypal link')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['paypal_seller']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['paypal_seller']['key'], 0);?>
                                <?= Form::checkbox($forms['paypal_seller']['key'], 1, (bool) $forms['paypal_seller']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['paypal_seller']['key'],
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'', 
                                ))?>
                                <?= FORM::label($forms['paypal_seller']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['paypal_seller']['key']))?>
                            </div>
                        </div>
                    </div>
                        
                    <div class="form-group">
                        <?= FORM::label($forms['stock']['key'], "<a target='_blank' href='https://docs.yclas.com/pay-directly-from-ad/'>".__('Paypal link stock control')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['stock']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['stock']['key'], 0);?>
                                <?= Form::checkbox($forms['stock']['key'], 1, (bool) $forms['stock']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['stock']['key'],
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'', 
                                ))?>
                                <?= FORM::label($forms['stock']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['stock']['key']))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='https://docs.yclas.com/2checkout-configuration/'>".__('2checkout')."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p><?=sprintf(__('To get paid via Credit card you need a %s account'),'2checkout')?>.</p>
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://www.2checkout.com/referral?r=6008d8b2c2">
                                <i class="glyphicon glyphicon-pencil"></i>Register for free at 2checkout</a>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['twocheckout_sandbox']['key'], __('Sandbox'), array('class'=>'control-label col-sm-4', 'for'=>$forms['twocheckout_sandbox']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['twocheckout_sandbox']['key'], 1, (bool) $forms['twocheckout_sandbox']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['twocheckout_sandbox']['key'],
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'', 
                                ))?>
                                <?= FORM::label($forms['twocheckout_sandbox']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['authorize_sandbox']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['twocheckout_sid']['key'], __('Account Number'), array('class'=>'col-md-4 control-label', 'for'=>$forms['twocheckout_sid']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['twocheckout_sid']['key'], $forms['twocheckout_sid']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['twocheckout_sid']['key'],
                            'data-content'=> __('Account Number'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['twocheckout_secretword']['key'], __('Secret Word'), array('class'=>'col-md-4 control-label', 'for'=>$forms['twocheckout_secretword']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['twocheckout_secretword']['key'], $forms['twocheckout_secretword']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['twocheckout_secretword']['key'],
                            'data-content'=> __('Secret Word'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><h4>Authorize.net</h4></div>
            <div class="panel-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p><?=sprintf(__('To get paid via Credit card you need a %s account'),'Authorize.net')?>. <?=__('You will need also a SSL certificate')?>, <a href="https://www.ssl.com/code/49" target="_blank"><?=__('buy your SSL certificate here')?></a>.</p>
                            <?=__('Register')?>
                            <a class="btn btn-success" target="_blank" href="http://reseller.authorize.net/application/signupnow/?id=AUAffiliate&rid=26776">
                                </i> US/Canada</a>
                            <a class="btn btn-success" target="_blank" href="http://reseller.authorize.net/application/?id=5561123">
                                UK/Europe</a>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['authorize_sandbox']['key'], __('Sandbox'), array('class'=>'control-label col-sm-4', 'for'=>$forms['authorize_sandbox']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['authorize_sandbox']['key'], 1, (bool) $forms['authorize_sandbox']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['authorize_sandbox']['key'],
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'', 
                                ))?>
                                <?= FORM::label($forms['authorize_sandbox']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['authorize_sandbox']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['authorize_login']['key'], __('Authorize API Login'), array('class'=>'col-md-4 control-label', 'for'=>$forms['authorize_login']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['authorize_login']['key'], $forms['authorize_login']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['authorize_login']['key'],
                            'data-content'=> __('Authorize API Login'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['authorize_key']['key'], __('Authorize transaction Key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['authorize_key']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['authorize_key']['key'], $forms['authorize_key']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['authorize_key']['key'],
                            'data-content'=> __("Authorize transaction Key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Paymill</h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p>To get paid via Credit card you need a Paymill account. It's free to register. They charge 2'95% of any sale.</p>
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://app.paymill.com/en-en/auth/register?referrer=openclassifieds">
                                <i class="glyphicon glyphicon-pencil"></i>Register for free at Paymill</a>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['paymill_private']['key'], __('Paymill private key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['paymill_private']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['paymill_private']['key'], $forms['paymill_private']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['paymill_private']['key'],
                            'data-content'=> __("Paymill private key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>
    
                    <div class="form-group">
                        <?= FORM::label($forms['paymill_public']['key'], __('Paymill public key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['paymill_public']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['paymill_public']['key'], $forms['paymill_public']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['paymill_public']['key'],
                            'data-content'=> __("Paymill public key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Stripe</h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p>To get paid via Credit card you can also use a Stripe account. It's free to register. They charge 2'95% of any sale.</p>
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://stripe.com">
                                <i class="glyphicon glyphicon-pencil"></i>Register for free at Stripe</a>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['stripe_private']['key'], __('Stripe private key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['stripe_private']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['stripe_private']['key'], $forms['stripe_private']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['stripe_private']['key'],
                            'data-content'=> __("Stripe private key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['stripe_public']['key'], __('Stripe public key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['stripe_public']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['stripe_public']['key'], $forms['stripe_public']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['stripe_public']['key'],
                            'data-content'=> __("Stripe public key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['stripe_address']['key'], __('Requires address to pay for extra security'), array('class'=>'col-md-4 control-label', 'for'=>$forms['stripe_address']['key']))?>
                        <div class="col-md-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['stripe_address']['key'], 1, (bool) $forms['stripe_address']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['stripe_address']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',                     
                                ))?>
                                <?= FORM::label($forms['stripe_address']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['stripe_address']['key']))?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['stripe_alipay']['key'], __('Accept Alipay payments'), array('class'=>'col-md-4 control-label', 'for'=>$forms['stripe_alipay']['key']))?>
                        <div class="col-md-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['stripe_alipay']['key'], 1, (bool) $forms['stripe_alipay']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['stripe_alipay']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',                     
                                ))?>
                                <?= FORM::label($forms['stripe_alipay']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['stripe_alipay']['key']))?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4>Bitpay</h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p>Accept bitcoins using Bitpay</p>
                            <a class="btn btn-success btn-icon-left" target="_blank" href="https://bitpay.com">
                                <i class="glyphicon glyphicon-pencil"></i>Register for free at Bitpay</a>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['bitpay_apikey']['key'], __('Bitpay api key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['bitpay_apikey']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['bitpay_apikey']['key'], $forms['bitpay_apikey']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['bitpay_apikey']['key'],
                            'data-content'=> __("Bitpay api key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Paysbuy</div>
            <div class="panel-body">
                <div class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p>Accept BAHT using Paysbuy</p>
                            <a class="btn btn-success" target="_blank" href="https://paysbuy.com">
                                <i class="glyphicon glyphicon-pencil"></i> Register for free at Paysbuy</a>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['paysbuy']['key'], __('Paysbuy account'), array('class'=>'col-md-4 control-label', 'for'=>$forms['paysbuy']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['paysbuy']['key'], $forms['paysbuy']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['paysbuy']['key'],
                            'data-content'=> __("Paysbuy account email"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['paysbuy_sandbox']['key'], __('Sandbox'), array('class'=>'col-md-4 control-label', 'for'=>$forms['paysbuy_sandbox']['key']))?>
                        <div class="col-md-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['paysbuy_sandbox']['key'], 1, (bool) $forms['paysbuy_sandbox']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['paysbuy_sandbox']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',                     
                                ))?>
                                <?= FORM::label($forms['paysbuy_sandbox']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['paysbuy_sandbox']['key']))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Prevent Fraud</div>
            <div class="panel-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <label class="col-md-8 col-md-offset-4">
                            <p><?=__('Help prevent card fraud with FraudLabsPro, for Stripe, 2co, Paymill and Authorize.')?></p>
                            <a class="btn btn-success btn-icon-left" target="_blank" href="http://www.fraudlabspro.com/?ref=1429">
                                <i class="glyphicon glyphicon-pencil"></i><?=sprintf(__('Register for free at %s'),'FraudLabsPro')?></a>
                        </label>
                    </div>
                    <div class="form-group">
                        
                        <?= FORM::label($forms['fraudlabspro']['key'], __('FraudLabsPro api key'), array('class'=>'col-md-4 control-label', 'for'=>$forms['fraudlabspro']['key']))?>
                        <div class="col-md-8">
                            <?= FORM::input($forms['fraudlabspro']['key'], $forms['fraudlabspro']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['fraudlabspro']['key'],
                            'data-content'=> __("FraudLabsPro api key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'', 
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-8 col-sm-offset-4">
                    <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
                </div>
            </div>
        </div>
        </form>
    </div><!--end col-md-8-->
</div>
