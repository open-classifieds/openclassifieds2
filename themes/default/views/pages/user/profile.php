<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h3><?=_e('User Profile')?></h3>
</div>

<div id="user_profile_info" class="row">
    <div class="col-xs-3">
        <?$images = $user->get_profile_images(); if ($images):?>
            <div id="gallery">
                <?$i = 0; foreach ($images as $key => $image):?>
                    <a href="<?=$image?>" class="thumbnail gallery-item <?=$i > 0 ? 'hidden' : NULL?>" data-gallery>
                        <img class="img-rounded img-responsive" src="<?=Core::imagefly($image,200,200)?>" alt="<?=$user->name?>">
                    </a>
                <?$i++; endforeach?>
            </div>
            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">‹</a>
                <a class="next">›</a>
                <a class="close">×</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
            </div>
        <?endif?>
    </div>
    <div class="col-xs-12 col-sm-9">
        <h3><?=$user->name?> <?=$user->is_verified_user();?></h3>
        <?if (Core::config('advertisement.reviews')==1):?>
            <? if ($user->rate !== NULL) : ?>
                <a href="<?= Route::url('user-reviews', ['seoname' => $user->seoname]) ?>">
                    <? for ($i=0; $i < round($user->rate,1); $i++) : ?>
                        <span class="glyphicon glyphicon-star"></span>
                    <? endfor ?>
                </a>
            <? endif ?>
        <?endif?>
        <div class="text-description">
            <?=Text::bb2html($user->description,TRUE)?>
        </div>
    </div>
</div>

<div class="page-header">
    <article class="well">
        <ul class="list-unstyled">
            <li><strong><?=_e('Created')?>:</strong> <?= Date::format($user->created, core::config('general.date_format')) ?></li>
            <?if ($user->last_login!=NULL):?>
            <li><strong><?=_e('Last Login')?>:</strong> <?= Date::format($user->last_login, core::config('general.date_format'))?></li>
            <?endif?>
            <?if (Core::extra_features() == TRUE):?>
                <?foreach ($user->custom_columns(TRUE) as $name => $value):?>
                	<?if($value!=''):?>
                        <?if($name!='whatsapp' AND $name!='skype' AND $name!='telegram'):?>
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
                        <?endif?>
    	            <?endif?>
                <?endforeach?>
            <?endif?>
        </ul>
        <?if (Core::extra_features() == TRUE):?>
            <?if(isset($user->cf_whatsapp) AND strlen($user->cf_whatsapp) > 6):?>
                <a href="https://api.whatsapp.com/send?phone=<?=$user->cf_whatsapp?>" title="Chat with <?=$user->name?>" alt="Whatsapp"><i class="fa fa-2x fa-whatsapp" style="color:#43d854"></i></a>
            <?endif?>
            <?if(isset($user->cf_skype) AND $user->cf_skype!=''):?>
                <a href="skype:<?=$user->cf_skype?>?chat" title="Chat with <?=$user->name?>" alt="Skype"><i class="fa fa-2x fa-skype" style="color:#00aff0"></i></a>
            <?endif?>
            <?if(isset($user->cf_telegram) AND $user->cf_telegram!=''):?>
                <a href="tg://resolve?domain=<?=$user->cf_telegram?>" id="telegram" title="Chat with <?=$user->name?>" alt="Telegram"><i class="fa fa-2x fa-telegram" style="color:#0088cc"></i></a>
            <?endif?>
        <?endif?>
		<div class="clearfix">&nbsp;</div>
        <!-- Popup contact form -->
        <?if (core::config('general.messaging') == TRUE AND !Auth::instance()->logged_in()) :?>
            <a class="btn btn-success" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                <i class="glyphicon glyphicon-envelope"></i>
                <?=_e('Send Message')?>
            </a>
        <?else :?>
            <button class="btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i> <?=_e('Send Message')?></button>
        <?endif?>
        <div id="contact-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                         <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                        <h3><?=_e('Contact')?></h3>
                    </div>

                    <div class="modal-body">
                        <?=Form::errors()?>

                        <?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact', 'id'=>$user->id_user)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
                            <fieldset>
                                <?if (!Auth::instance()->get_user()):?>
                                    <div class="form-group">
                                        <?= FORM::label('name', _e('Name'), array('class'=>'col-md-2 control-label', 'for'=>'name'))?>
                                        <div class="col-md-4 ">
                                            <?= FORM::input('name', Core::request('name'), array('placeholder' => __('Name'), 'class' => 'form-control', 'id' => 'name', 'required'))?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?= FORM::label('email', _e('Email'), array('class'=>'col-md-2 control-label', 'for'=>'email'))?>
                                        <div class="col-md-4 ">
                                            <?= FORM::input('email', Core::request('email'), array('placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'email', 'type'=>'email','required'))?>
                                        </div>
                                    </div>
                                <?endif?>
                                <?if(core::config('general.messaging') != TRUE):?>
                                    <div class="form-group">
                                        <?= FORM::label('subject', _e('Subject'), array('class'=>'col-md-2 control-label', 'for'=>'subject'))?>
                                        <div class="col-md-4 ">
                                            <?= FORM::input('subject', Core::request('subject'), array('placeholder' => __('Subject'), 'class' => 'form-control', 'id' => 'subject'))?>
                                        </div>
                                    </div>
                                <?endif?>
                                <div class="form-group">
                                    <?= FORM::label('message', _e('Message'), array('class'=>'col-md-2 control-label', 'for'=>'message'))?>
                                    <div class="col-md-6">
                                        <?= FORM::textarea('message', Core::post('subject'), array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>
                                        </div>
                                </div>
                                <?if (core::config('advertisement.captcha') != FALSE):?>
                                    <div class="form-group">
                                        <?= FORM::label('captcha', _e('Captcha'), array('class'=>'col-md-2 control-label', 'for'=>'captcha'))?>
                                        <div class="col-md-4">
                                            <?if (Core::config('general.recaptcha_active')):?>
                                                <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
                                            <?else:?>
                                                <?=captcha::image_tag('contact')?><br />
                                                <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
                                            <?endif?>
                                        </div>
                                    </div>
                                <?endif?>
                                <div class="modal-footer">
                                    <?= FORM::button(NULL, _e('Send Message'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact' , 'id'=>$user->id_user))))?>
                                </div>
                            </fieldset>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>

        <?if (core::config('advertisement.gm_api_key')):?>
            <?if(Core::config('advertisement.map') AND $user->address !== NULL AND $user->latitude !== NULL AND $user->longitude !== NULL):?>
                <h3><?=_e('Map')?></h3>
                <p>
                    <img class="img-responsive" src="//maps.googleapis.com/maps/api/staticmap?language=<?=i18n::get_gmaps_language(i18n::$locale)?>&amp;zoom=<?=Core::config('advertisement.map_zoom')?>&amp;scale=false&amp;size=600x300&amp;maptype=roadmap&amp;format=png&amp;visual_refresh=true&amp;markers=size:large%7Ccolor:red%7Clabel:·%7C<?=$user->latitude?>,<?=$user->longitude?>&amp;key=<?=core::config('advertisement.gm_api_key')?>" alt="<?=HTML::chars($user->name)?> <?=_e('Map')?>" style="width:100%;">
                </p>
                <p>
                    <a class="btn btn-default btn-sm" href="<?=Route::url('map')?>?id_user=<?=$user->id_user?>" >
                        <span class="glyphicon glyphicon-globe"></span> <?=_e('Map View')?>
                    </a>
                </p>
            <?elseif (Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin() AND !Core::config('advertisement.map')) :?>
                <p>
                    <div class="alert alert-danger" role="alert">
                        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>" class="alert-link">
                            <?=__('Please enable "Google Maps in Ad and Profile page" to show user location on the map.')?>
                        </a>
                    </div>
                </p>
            <?elseif(Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_user == $user->id_user):?>
                <p>
                    <div class="alert alert-danger" role="alert">
                        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>" class="alert-link">
                            <?=__('Click here to enter your address.')?>
                        </a>
                    </div>
                </p>
            <?endif?>
        <?elseif (Core::config('advertisement.map') AND Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()) :?>
            <div class="alert alert-danger" role="alert">
                <a href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))?>" class="alert-link">
                    <?=__('Please set your Google API key on advertisement configuration.')?>
                </a>
            </div>
        <?endif?>

        <div class="clearfix">&nbsp;</div>
	</article>
</div>
<div class="page-header">
    <h3><?=$user->name.' '._e(' advertisements')?></h3>

    <?if($profile_ads!==NULL):?>
        <?foreach($profile_ads as $ad):?>
            <?if($ad->featured >= Date::unix2mysql(time())):?>
                <article id="user_profile_ads" class="well featured">
                    <span class="label label-danger pull-right"><?=_e('Featured')?></span>
            <?else:?>
                <article id="user_profile_ads" class="well">
            <?endif?>
                <div class="col-xs-12">
                    <h4><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?=$ad->title?></a></h4>
                    <?if ($ad->rate!==NULL):?>
                        <?for ($i=0; $i < round($user->rate,1); $i++):?>
                            <span class="glyphicon glyphicon-star"></span>
                        <?endfor?>
                    <?endif?>
                </div>
                <div class="col-xs-12 col-sm-3 picture">
                    <a title="<?=HTML::chars($ad->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                        <figure>
                            <?if($ad->get_first_image() !== NULL):?>
                                <img src="<?=Core::imagefly($ad->get_first_image('image'),160,160)?>" class="img-responsive center-block" alt="<?=HTML::chars($ad->title)?>" />
                            <?elseif(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                                <img src="<?=Core::imagefly($icon_src,160,160)?>" class="img-responsive center-block" alt="<?=HTML::chars($ad->title)?>" />
                            <?elseif(( $icon_src = $ad->location->get_icon() )!==FALSE ):?>
                                <img src="<?=Core::imagefly($icon_src,160,160)?>" class="img-responsive center-block" alt="<?=HTML::chars($ad->title)?>" />
                            <?else:?>
                                <img data-src="holder.js/160x160?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->translate_name(), 'size' => 14, 'auto' => 'yes')))?>" class="img-responsive center-block" alt="<?=HTML::chars($ad->title)?>">
                            <?endif?>
                        </figure>
                    </a>
                </div>

                <p><strong><?=_e('Description')?>: </strong><?=Text::removebbcode($ad->description)?><p>
                <p><b><?=_e('Publish Date');?>:</b> <?= Date::format($ad->published, core::config('general.date_format'))?><p>

                <?$visitor = Auth::instance()->get_user()?>

                <?if ($visitor != FALSE && $visitor->id_role == 10):?>
                    <br>
                    <a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><?=_e("Edit");?></a> |
                    <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>"
                        onclick="return confirm('<?=__('Deactivate?')?>');"><?=_e("Deactivate");?>
                    </a> |
                    <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>"
                        onclick="return confirm('<?=__('Spam?')?>');"><?=_e("Spam");?>
                    </a> |
                    <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>"
                        onclick="return confirm('<?=__('Delete?')?>');"><?=_e("Delete");?>
                    </a>
                <?elseif($visitor != FALSE && $visitor->id_user == $ad->id_user):?>
                    <br>
                    <a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><?=_e("Edit");?></a>
                <?endif?>
                <div class="clearfix"></div>
            </article>
        <?endforeach?>
        <?=$pagination?>
    <?endif?>

</div>
