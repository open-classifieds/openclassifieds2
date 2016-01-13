<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h3><?= __('User Profile')?></h3>
</div>

<div class="row">
    <div class="col-md-3">
        <a class="thumbnail">
            <img src="<?=$user->get_profile_image()?>" class="img-rounded" alt="<?=__('Profile Picture')?>" height='200px'>
        </a>
    </div>
    <div class="col-md-9">
        <div class="text-description">
            <?=$user->description?>
        </div>
    </div>
</div>

<div class="page-header">
    <article class="well">
        <h3><?=$user->name?></h3>
        <ul class="list-unstyled">
            <?if (Core::config('advertisement.reviews')==1):?>
                <li>
                    <?if ($user->rate!==NULL):?>
                        <?for ($i=0; $i < round($user->rate,1); $i++):?>
                            <span class="glyphicon glyphicon-star"></span>
                        <?endfor?>
                    <?endif?>
                </li>
            <?endif?>
            <li><strong><?=__('Created')?>:</strong> <?= Date::format($user->created, core::config('general.date_format')) ?></li>
            <?if ($user->last_login!=NULL):?>
            <li><strong><?=__('Last Login')?>:</strong> <?= Date::format($user->last_login, core::config('general.date_format'))?></li>
            <?endif?>
            <?if (Theme::get('premium')==1):?>
            <?foreach ($user->custom_columns(TRUE) as $name => $value):?>
                <li>
                    <strong><?=$name?>:</strong>
                    <?if($value=='checkbox_1'):?>
                        <i class="fa fa-check"></i>
                    <?elseif($value=='checkbox_0'):?>
                        <i class="fa fa-times"></i>
                    <?else:?>
                        <?=$value?>
                    <?endif?>
                </li>
            <?endforeach?>
            <?endif?>
        </ul>

        <!-- Popup contact form -->
        <?if (core::config('general.messaging') == TRUE AND !Auth::instance()->logged_in()) :?>
            <a class="btn btn-success" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                <i class="glyphicon glyphicon-envelope"></i>
                <?=__('Send Message')?>
            </a>
        <?else :?>
            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i> <?=__('Send Message')?></button>
        <?endif?>
        <div id="contact-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                         <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                        <h3><?=__('Contact')?></h3>
                    </div>
                    
                    <div class="modal-body">
                        <?=Form::errors()?>
                        
                        <?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact', 'id'=>$user->id_user)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
                            <fieldset>
                                <?if (!Auth::instance()->get_user()):?>
                                    <div class="form-group">
                                        <?= FORM::label('name', __('Name'), array('class'=>'col-md-2 control-label', 'for'=>'name'))?>
                                        <div class="col-md-4 ">
                                            <?= FORM::input('name', Core::request('name'), array('placeholder' => __('Name'), 'class' => 'form-control', 'id' => 'name', 'required'))?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?= FORM::label('email', __('Email'), array('class'=>'col-md-2 control-label', 'for'=>'email'))?>
                                        <div class="col-md-4 ">
                                            <?= FORM::input('email', Core::request('email'), array('placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'email', 'type'=>'email','required'))?>
                                        </div>
                                    </div>
                                <?endif?>
                                <?if(core::config('general.messaging') != TRUE):?>
                                    <div class="form-group">
                                        <?= FORM::label('subject', __('Subject'), array('class'=>'col-md-2 control-label', 'for'=>'subject'))?>
                                        <div class="col-md-4 ">
                                            <?= FORM::input('subject', Core::request('subject'), array('placeholder' => __('Subject'), 'class' => 'form-control', 'id' => 'subject'))?>
                                        </div>
                                    </div>
                                <?endif?>
                                <div class="form-group">
                                    <?= FORM::label('message', __('Message'), array('class'=>'col-md-2 control-label', 'for'=>'message'))?>
                                    <div class="col-md-6">
                                        <?= FORM::textarea('message', Core::post('subject'), array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>
                                        </div>
                                </div>
                                <?if (core::config('advertisement.captcha') != FALSE):?>
                                    <div class="form-group">
                                        <?= FORM::label('captcha', __('Captcha'), array('class'=>'col-md-2 control-label', 'for'=>'captcha'))?>
                                        <div class="col-md-4">
                                            <?if (Core::config('general.recaptcha_active')):?>
                                                <?=Captcha::recaptcha_display()?>
                                                <div id="recaptcha1"></div>
                                            <?else:?>
                                                <?=captcha::image_tag('contact')?><br />
                                                <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
                                            <?endif?>
                                        </div>
                                    </div>
                                <?endif?>
                                <div class="modal-footer">	
                                    <?= FORM::button('submit', __('Send Message'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact' , 'id'=>$user->id_user))))?>
                                </div>
                            </fieldset>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>
	</article>
</div>
<div class="page-header">
    <h3><?=$user->name.' '.__(' advertisements')?></h3>

    <?if($profile_ads!==NULL):?>
        <?foreach($profile_ads as $ads):?>			 
            <?if($ads->featured >= Date::unix2mysql(time())):?>
                <article class="well featured">
            <?else:?>
                <article class="well">
            <?endif?>
                
                <h4><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>"><?=$ads->title?></a></h4>
                <p><strong><?=__('Description')?>: </strong><?=Text::removebbcode($ads->description)?><p>
                <p><b><?=__('Publish Date');?>:</b> <?= Date::format($ads->published, core::config('general.date_format'))?><p>
                
                <?$visitor = Auth::instance()->get_user()?>
                
                <?if ($visitor != FALSE && $visitor->id_role == 10):?>
                    <br>
                    <a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ads->id_ad))?>"><?=__("Edit");?></a> |
                    <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ads->id_ad))?>" 
                        onclick="return confirm('<?=__('Deactivate?')?>');"><?=__("Deactivate");?>
                    </a> |
                    <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ads->id_ad))?>" 
                        onclick="return confirm('<?=__('Spam?')?>');"><?=__("Spam");?>
                    </a> |
                    <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ads->id_ad))?>" 
                        onclick="return confirm('<?=__('Delete?')?>');"><?=__("Delete");?>
                    </a>
                <?elseif($visitor != FALSE && $visitor->id_user == $ads->id_user):?>
                    <br>
                    <a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ads->id_ad))?>"><?=__("Edit");?></a> 
                <?endif?>
            </article>
        <?endforeach?>
        <?=$pagination?>
    <?endif?>
</div>
	
