<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title"><?=__('Email settings')?></h1>
<hr>

<?=View::factory('oc-panel/elasticemail')?>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <?=FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email')), array('class'=>'config ajax-load', 'enctype'=>'multipart/form-data'))?>
            <div>
                <div>
                    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left" id="tab-settings">
                        <li class="active">
                            <a data-toggle="tab" href="#tabSettingsEmailGeneral" aria-expanded="true"><?=__('General Email Configuation')?></a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsElasticEmail" aria-expanded="false">ElasticEmail</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#tabSettingsSMTPconfiguration" aria-expanded="false"><?=__('SMTP Configuration')?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tabSettingsEmailGeneral" class="tab-pane active fade">
                            <h4>
                                <?=__('General Email Configuration')?>
                                <a target="_blank" href="https://docs.yclas.com/general-email-configuration/">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                            </h4>
                            <hr>
                        <?foreach ($config as $c):?>
                            <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                        <?endforeach?>
                    
                        <div class="form-group">
                            <?=FORM::label($forms['notify_email']['key'], __('Notify email'), array('class'=>'control-label', 'for'=>$forms['notify_email']['key']))?>
                            <?=FORM::input($forms['notify_email']['key'], $forms['notify_email']['value'], array(
                                'placeholder' => "youremail@mail.com", 
                                'class' => 'tips form-control', 
                                'id' => $forms['notify_email']['key'], 
                                'data-rule-email' => 'true',
                            ))?> 
                            <span class="help-block">
                                <?=__("Email from where we send the emails, also used for software communications.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['notify_name']['key'], __('Notify name'), array('class'=>'control-label', 'for'=>$forms['notify_name']['key']))?>
                            <?=FORM::input($forms['notify_name']['key'], $forms['notify_name']['value'], array(
                                'placeholder' => "no-reply ".core::config('general.site_name'), 
                                'class' => 'tips form-control', 
                                'id' => $forms['notify_name']['key'], 
                            ))?> 
                            <span class="help-block">
                                <?=__("Name from where we send the emails, also used for software communications.")?>
                            </span>
                        </div>
                    
                        <div class="form-group">
                            <?= FORM::label($forms['new_ad_notify']['key'], __("Notify me on new ad"), array('class'=>'control-label', 'for'=>$forms['new_ad_notify']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['new_ad_notify']['key'], 1, (bool) $forms['new_ad_notify']['value'], array('id' => $forms['new_ad_notify']['key'].'1'))?>
                                <?=Form::label($forms['new_ad_notify']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['new_ad_notify']['key'], 0, ! (bool) $forms['new_ad_notify']['value'], array('id' => $forms['new_ad_notify']['key'].'0'))?>
                                <?=Form::label($forms['new_ad_notify']['key'].'0', __('Disabled'))?>
                            </div>
                        </div>

                        <hr>

                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email'))))?>
                    </div>

                        <div id="tabSettingsElasticEmail" class="tab-pane fade">
                        <h4>
                            <?=__('ElasticEmail Configuration')?>
                            <a target="_blank" href="https://docs.yclas.com/configure-elasticemail-open-classifieds/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                        </h4>
                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['elastic_active']['key'], "ElasticEmail", array('class'=>'control-label', 'for'=>$forms['elastic_active']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['elastic_active']['key'], 1, (bool) $forms['elastic_active']['value'], array('id' => $forms['elastic_active']['key'].'1'))?>
                                <?=Form::label($forms['elastic_active']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['elastic_active']['key'], 0, ! (bool) $forms['elastic_active']['value'], array('id' => $forms['elastic_active']['key'].'0'))?>
                                <?=Form::label($forms['elastic_active']['key'].'0', __('Disabled'))?>
                            </div>
                        </div>
                                        
                        <div class="form-group">
                            <a class="btn btn-success" href="http://j.mp/elasticemailoc" target="_blank" onclick='setCookie("elastic_alert",1,365)' ><?=__('Sign Up ElasticEmail 150K emails free per month')?></a>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['elastic_username']['key'], __('API Key'), array('class'=>'control-label', 'for'=>$forms['elastic_username']['key']))?>
                            <?=FORM::input($forms['elastic_username']['key'], $forms['elastic_username']['value'], array(
                                'placeholder' => '', 
                                'class' => 'tips form-control', 
                                'id' => $forms['elastic_username']['key'],          
                            ))?> 
                        </div>
                    
                        <div class="form-group">
                            <?=FORM::label($forms['elastic_password']['key'], __('Public Account ID'), array('class'=>'control-label', 'for'=>$forms['elastic_password']['key']))?>
                            <?=FORM::input($forms['elastic_password']['key'], $forms['elastic_password']['value'], array(
                                'placeholder' => "", 
                                'class' => 'tips form-control', 
                                'id' => $forms['elastic_password']['key'],      
                            ))?>
                        </div>

                        <hr>

                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email'))))?>
                    </div>

                        <div id="tabSettingsSMTPconfiguration" class="tab-pane fade">
                        <h4>
                            <?=__('SMTP Configuration')?>
                            <a target="_blank" href="https://docs.yclas.com/smtp-configuration/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                        </h4>
                        <hr>
                        
                        <div class="form-group">
                            <?=FORM::label($forms['smtp_active']['key'], __("Smtp active"), array('class'=>'control-label', 'for'=>$forms['smtp_active']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['smtp_active']['key'], 1, (bool) $forms['smtp_active']['value'], array('id' => $forms['smtp_active']['key'].'1'))?>
                                <?=Form::label($forms['smtp_active']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['smtp_active']['key'], 0, ! (bool) $forms['smtp_active']['value'], array('id' => $forms['smtp_active']['key'].'0'))?>
                                <?=Form::label($forms['smtp_active']['key'].'0', __('Disabled'))?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['smtp_secure']['key'], __('Smtp secure'), array('class'=>'control-label', 'for'=>$forms['smtp_secure']['key']))?>
                            <?=FORM::select($forms['smtp_secure']['key'], array(''=>__("None"),'ssl'=>'SSL','tls'=>'TLS'), $forms['smtp_secure']['value'], array(
                                'placeholder' => $forms['smtp_secure']['value'], 
                                'class' => 'tips form-control input-sm ', 
                                'id' => $forms['smtp_secure']['key'],
                            ))?> 
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['smtp_host']['key'], __('Smtp host'), array('class'=>'control-label', 'for'=>$forms['smtp_host']['key']))?>
                            <?=FORM::input($forms['smtp_host']['key'], $forms['smtp_host']['value'], array(
                                'placeholder' => '', 
                                'class' => 'tips form-control', 
                                'id' => $forms['smtp_host']['key'],          
                            ))?> 
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['smtp_port']['key'], __('Smtp port'), array('class'=>'control-label col-sm-4', 'for'=>$forms['smtp_port']['key']))?>
                            <?=FORM::input($forms['smtp_port']['key'], $forms['smtp_port']['value'], array(
                                'placeholder' => "", 
                                'class' => 'tips form-control', 
                                'id' => $forms['smtp_port']['key'], 
                                'type'=> 'number',
                            ))?> 
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['smtp_auth']['key'], __("Smtp auth"), array('class'=>'control-label', 'for'=>$forms['smtp_auth']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['smtp_auth']['key'], 1, (bool) $forms['smtp_auth']['value'], array('id' => $forms['smtp_auth']['key'].'1'))?>
                                <?=Form::label($forms['smtp_auth']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['smtp_auth']['key'], 0, ! (bool) $forms['smtp_auth']['value'], array('id' => $forms['smtp_auth']['key'].'0'))?>
                                <?=Form::label($forms['smtp_auth']['key'].'0', __('Disabled'))?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['smtp_user']['key'], __('Smtp user'), array('class'=>'control-label', 'for'=>$forms['smtp_user']['key']))?>
                            <?=FORM::input($forms['smtp_user']['key'], $forms['smtp_user']['value'], array(
                                'placeholder' => "", 
                                'class' => 'tips form-control', 
                                'id' => $forms['smtp_user']['key'],          
                            ))?> 
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['smtp_pass']['key'], __('Smtp password'), array('class'=>'control-label', 'for'=>$forms['smtp_pass']['key']))?>
                            <?=FORM::input($forms['smtp_pass']['key'], $forms['smtp_pass']['value'], array(
                                'placeholder' => "",
                                'type' => "password", 
                                'class' => 'tips form-control', 
                                'id' => $forms['smtp_pass']['key'],        
                            ))?> 
                        </div>

                        <hr>

                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email'))))?>
                    </div>
                </div>
                </div>
            </div>
        </form>
	</div>
</div>
