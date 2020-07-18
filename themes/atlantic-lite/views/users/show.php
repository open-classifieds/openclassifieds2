<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=_e('User Profile')?></h1>
        </div>

        <div id="user_profile_info" class="row mb-4">
            <div class="col-3">
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

            <div class="col-12 col-sm-9">
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

                <div>
                    <?=Text::bb2html($user->description,TRUE)?>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <ul class="list-unstyled">
                <li>
                    <strong><?=_e('Created')?>:</strong> <?= Date::format($user->created, core::config('general.date_format')) ?>
                </li>
                <?if ($user->last_login!=NULL):?>
                    <li>
                        <strong><?=_e('Last Login')?>:</strong> <?= Date::format($user->last_login, core::config('general.date_format'))?>
                    </li>
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

            <?if (core::config('general.messaging') == TRUE AND !Auth::instance()->logged_in()) :?>
                <a class="btn btn-primary" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                    <i class="fas fa-envelope"></i>
                    <?=_e('Send Message')?>
                </a>
            <?else :?>
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#contact-modal"><i class="fas fa-envelope"></i> <?=_e('Send Message')?></button>
            <?endif?>

            <div id="contact-modal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?=_e('Contact')?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <?=Form::errors()?>

                        <?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact', 'id'=>$user->id_user)), array('enctype'=>'multipart/form-data'))?>
                            <div class="modal-body">
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
                            </div>

                            <div class="modal-footer">
                                <?= FORM::button(NULL, _e('Send Message'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact' , 'id'=>$user->id_user))))?>
                            </div>
                        <?= FORM::close()?>
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
        </div>

        <div class="mb-3">
            <h2 class="h3"><?=$user->name.' '._e(' advertisements')?></h3>
        </div>

        <?if($profile_ads!==NULL):?>
            <?foreach($profile_ads as $ad):?>
                <?= View::factory('ads/_ad', compact('ad', 'user')) ?>
            <?endforeach?>

            <?= $pagination ?>
        <?endif?>
    </div>
    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
