<?php defined('SYSPATH') or die('No direct script access.');?>

<?=View::factory('oc-panel/elasticemail')?>

<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('Email Configuration')?></h1>
    <p><?=__('Email Setup and emails configuration values. Replace input fields with new desired values')?></p>
    <p><?=sprintf(__('How to configure %s'),'<a href="http://docs.yclas.com/configure-elasticemail-open-classifieds/" target="_blank">ElasticEmail</a>')?></p>
</div>

<div class="row">
    <div class="col-md-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email')), array('class'=>'form-horizontal config', 'enctype'=>'multipart/form-data'))?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-horizontal">
                    <?foreach ($config as $c):?>
                        <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                    <?endforeach?>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['notify_email']['key'], __('Notify email'), array('class'=>'control-label col-sm-4', 'for'=>$forms['notify_email']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['notify_email']['key'], $forms['notify_email']['value'], array(
                            'placeholder' => "youremail@mail.com", 
                            'class' => 'tips form-control', 
                            'id' => $forms['notify_email']['key'], 
                            'data-content'=> __("Email from where we send the emails, also used for software communications."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Email From"),
                            'data-rule-email' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['new_ad_notify']['key'], __('Notify me on new ad'), array('class'=>'control-label col-sm-4', 'for'=>$forms['new_ad_notify']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['new_ad_notify']['key'], 0);?>
                                <?= Form::checkbox($forms['new_ad_notify']['key'], 1, (bool) $forms['new_ad_notify']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['new_ad_notify']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',                     
                                ))?>
                                <?= FORM::label($forms['new_ad_notify']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['new_ad_notify']['key']))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/configure-elasticemail-open-classifieds/'>".__('ElasticEmail Configuration')."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">

                    <div class="form-group">
                        <div class="col-sm-8 col-sm-offset-4">
                            <a class="btn btn-success" href="http://j.mp/elasticemailoc" target="_blank" onclick='setCookie("elastic_alert",1,365)' >Sign Up ElasticEmail $5 Free</a>
                        </div>  
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['elastic_active']['key'], __('ElasticEmail active'), array('class'=>'control-label col-sm-4', 'for'=>$forms['elastic_active']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['elastic_active']['key'], 0);?>
                                <?= Form::checkbox($forms['elastic_active']['key'], 1, (bool) $forms['elastic_active']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['elastic_active']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',
                                ))?>
                                <?= FORM::label($forms['elastic_active']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['elastic_active']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['elastic_username']['key'], __('API Username'), array('class'=>'control-label col-sm-4', 'for'=>$forms['elastic_username']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['elastic_username']['key'], $forms['elastic_username']['value'], array(
                            'placeholder' => '', 
                            'class' => 'tips form-control', 
                            'id' => $forms['elastic_username']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'',              
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['elastic_password']['key'], __('API Password'), array('class'=>'control-label col-sm-4', 'for'=>$forms['elastic_password']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['elastic_password']['key'], $forms['elastic_password']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['elastic_password']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'',          
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><?=__('SMTP Configuration')?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_active']['key'], __('Smtp active'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_active']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['smtp_active']['key'], 0);?>
                                <?= Form::checkbox($forms['smtp_active']['key'], 1, (bool) $forms['smtp_active']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['smtp_active']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',
                                ))?>
                                <?= FORM::label($forms['smtp_active']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['smtp_active']['key']))?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_ssl']['key'], __('Smtp ssl'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_ssl']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['smtp_ssl']['key'], 0);?>
                                <?= Form::checkbox($forms['smtp_ssl']['key'], 1, (bool) $forms['smtp_ssl']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['smtp_ssl']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',                     
                                ))?>
                                <?= FORM::label($forms['smtp_ssl']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['smtp_ssl']['key']))?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_host']['key'], __('Smtp host'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_host']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['smtp_host']['key'], $forms['smtp_host']['value'], array(
                            'placeholder' => '', 
                            'class' => 'tips form-control', 
                            'id' => $forms['smtp_host']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'',              
                            ))?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_port']['key'], __('Smtp port'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_port']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['smtp_port']['key'], $forms['smtp_port']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['smtp_port']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'',  
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_auth']['key'], __('Smtp auth'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_auth']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['smtp_auth']['key'], 0);?>
                                <?= Form::checkbox($forms['smtp_auth']['key'], 1, (bool) $forms['smtp_auth']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['smtp_auth']['key'], 
                                'data-content'=> '',
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>'',                     
                                ))?>
                                <?= FORM::label($forms['smtp_auth']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['smtp_auth']['key']))?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_user']['key'], __('Smtp user'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_user']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['smtp_user']['key'], $forms['smtp_user']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['smtp_user']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'',              
                            ))?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['smtp_pass']['key'], __('Smtp password'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_pass']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['smtp_pass']['key'], $forms['smtp_pass']['value'], array(
                            'placeholder' => "",
                            'type' => "password", 
                            'class' => 'tips form-control', 
                            'id' => $forms['smtp_pass']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
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
                    <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email'))))?>
                </div>
            </div>
        </div>
        </form>
	</div><!--end col-md-8-->
</div>