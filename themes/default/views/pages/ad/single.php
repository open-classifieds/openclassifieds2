<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($ad->status != Model_Ad::STATUS_PUBLISHED && $permission === FALSE && ($ad->id_user != $user)):?>

    <div class="page-header">
        <h1><?=_e('This advertisement doesn´t exist, or is not yet published!')?></h1>
    </div>

<?else:?>
    <?=Form::errors()?>
    <div class="page-header">
        <div class="pull-right favorite" id="fav-<?=$ad->id_ad?>">
            <?if (Auth::instance()->logged_in()):?>
                <?$fav = Model_Favorite::is_favorite(Auth::instance()->get_user(),$ad);?>
                <a data-id="fav-<?=$ad->id_ad?>" class="add-favorite <?=($fav)?'remove-favorite':''?>" title="<?=__('Add to Favorites')?>" href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'favorites','id'=>$ad->id_ad))?>">
                    <i class="glyphicon glyphicon-heart<?=($fav)?'':'-empty'?>"></i>
                </a>
            <?else:?>
                <a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                    <i class="glyphicon glyphicon-heart-empty"></i>
                </a>
            <?endif?>
        </div>
        <h1><?= $ad->title;?></h1>
    </div>

    <!-- PAYPAL buttons to featured and to top -->
    <?if ((Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == 10 ) OR
        (Auth::instance()->logged_in() AND $ad->user->id_user == Auth::instance()->get_user()->id_user)):?>
        <?if((core::config('payment.pay_to_go_on_top') > 0
                && core::config('payment.to_top') != FALSE )
                OR (core::config('payment.pay_to_go_on_feature') > 0
                && core::config('payment.to_featured') != FALSE)):?>
            <div id="recomentadion" class="well recomentadion clearfix">
                <?if(core::config('payment.pay_to_go_on_top') > 0 AND core::config('payment.to_top') != FALSE AND $ad->status == Model_Ad::STATUS_PUBLISHED):?>
                    <p class="text-info"><?=_e('Your Advertisement can go on top again! For only ').i18n::format_currency(core::config('payment.pay_to_go_on_top'),core::config('payment.paypal_currency'));?></p>
                    <a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>"><?=_e('Go Top!')?></a>
                <?endif?>
                <?if(core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql()):?>
                    <p class="text-info"><?=_e('Your Advertisement can go to featured! For only ').i18n::format_currency(Model_Order::get_featured_price(),core::config('payment.paypal_currency'));?></p>
                    <a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>"><?=_e('Go Featured!')?></a>
                <?endif?>
            </div>
        <?endif?>
    <?endif?>
    <!-- end paypal button -->

    <?$images = $ad->get_images()?>
    <?if($images):?>
        <div class="row">
            <div id="gallery" class="col-md-9">
                <div class="row">
                    <?foreach ($images as $path => $value):?>
                        <?if( isset($value['thumb']) AND isset($value['image']) ):?>
                            <div class="col-md-3">
                                <a href="<?=$value['image']?>" class="thumbnail gallery-item" data-gallery>
                                    <?=HTML::picture($value['image'], array('w' => 280, 'h' => 280), array('1200px' => array('w' => '125', 'h' => '125'), '992px' => array('w' => '96', 'h' => '96'), '768px' => array('w' => '939', 'h' => '939'), '480px' => array('w' => '727', 'h' => '727'), '320px' => array('w' => '440', 'h' => '440')), array('alt' => HTML::chars($ad->title), 'class' => 'img-responsive img-rounded'))?>
                                </a>
                            </div>
                        <?endif?>
                    <?endforeach?>
                </div>
            </div>
        </div>
    <?endif?>

    <div class="well ">
        <?if ($ad->price>0):?>
            <span class="label label-danger"><?=_e('Price');?> : <span class="price-curry"><?=i18n::money_format( $ad->price, $ad->currency())?></span></span>
        <?endif?>
        <?if ($ad->price==0 AND core::config('advertisement.free')==1):?>
            <span class="label label-danger"><?=_e('Price');?> : <?=_e('Free');?></span>
        <?endif?>
        <?if (core::config('advertisement.location') AND $ad->id_location != 1 AND $ad->location->loaded()):?>
            <span class="label label-default"><?=$ad->location->translate_name()?></span>
        <?endif?>
        <a class="label label-default" href="<?=Route::url('profile',  array('seoname'=>$ad->user->seoname))?>"><?=$ad->user->name?></a>
        <div class="pull-right">
            <span class="label label-info"><?= Date::format($ad->published, core::config('general.date_format'))?></span>
            <?if(core::config('advertisement.count_visits')==1):?>
                <span class="label label-info"><?=$hits?> <?=_e('Hits')?></span>
            <?endif?>
        </div>
    </div>

    <br/>

    <div>
        <?if(core::config('advertisement.description')!=FALSE):?>
          <div class="text-description"><?=Text::bb2html($ad->description,TRUE)?></div>
        <?endif?>
        <?if (Valid::url($ad->website)):?>
            <p><a href="<?=$ad->website?>" rel="nofollow" target="_blank">><?=$ad->website?></a></p>
        <?endif?>
        <?if (core::config('advertisement.address')):?>
            <p><?=$ad->address?></p>
        <?endif?>
     </div>

    <div class="btn-group btn-group-justified" role="group">
        <?if((core::config('payment.paypal_seller')==1 OR Core::config('payment.stripe_connect')==1 OR Core::config('payment.escrow_pay')==1) AND $ad->price != NULL AND $ad->price > 0):?>
            <?if(core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1)):?>
                <div class="btn-group" role="group">
                    <?if($ad->status != Model_Ad::STATUS_SOLD):?>
                        <a class="btn btn-primary" href="<?=Route::url('default', array('action'=>'buy','controller'=>'ad','id'=>$ad->id_ad))?>">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            &nbsp;&nbsp;<?=_e('Buy Now')?>
                        </a>
                    <?else:?>
                        <a class="btn btn-primary disabled">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            &nbsp;&nbsp;<?=_e('Sold')?>
                        </a>
                    <?endif?>
                </div>
            <?endif?>
        <?elseif (isset($ad->cf_file_download) AND !empty($ad->cf_file_download) AND  ( core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1))):?>
                <div class="btn-group" role="group">
                    <a class="btn btn-primary" type="button" href="<?=$ad->cf_file_download?>">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        &nbsp;&nbsp;<?=_e('Download')?>
                    </a>
                </div>
        <?endif?>
        <?if ($ad->can_contact()):?>
            <div class="btn-group" role="group">
                <?if ((core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE) AND !Auth::instance()->logged_in()) :?>
                    <a class="btn btn-success" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                        <i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;<?=_e('Send Message')?>
                    </a>
                <?else :?>
                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;<?=_e('Send Message')?></button>
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

                                <?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'user_contact', 'id'=>$ad->id_ad)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
                                    <fieldset>
                                        <?if (!Auth::instance()->get_user()):?>
                                            <div class="form-group">
                                                <?= FORM::label('name', _e('Name'), array('class'=>'col-sm-2 control-label', 'for'=>'name'))?>
                                                <div class="col-md-4 ">
                                                    <?= FORM::input('name', Core::request('name'), array('placeholder' => __('Name'), 'class'=>'form-control', 'id' => 'name', 'required'))?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <?= FORM::label('email', _e('Email'), array('class'=>'col-sm-2 control-label', 'for'=>'email'))?>
                                                <div class="col-md-4 ">
                                                    <?= FORM::input('email', Core::request('email'), array('placeholder' => __('Email'), 'class'=>'form-control', 'id' => 'email', 'type'=>'email','required'))?>
                                                </div>
                                            </div>
                                        <?endif?>
                                        <?if(core::config('general.messaging') != TRUE):?>
                                            <div class="form-group">
                                                <?= FORM::label('subject', _e('Subject'), array('class'=>'col-sm-2 control-label', 'for'=>'subject'))?>
                                                <div class="col-md-4 ">
                                                    <?= FORM::input('subject', Core::request('subject'), array('placeholder' => __('Subject'), 'class'=>'form-control', 'id' => 'subject'))?>
                                                </div>
                                            </div>
                                        <?endif?>
                                        <div class="form-group">
                                            <?= FORM::label('message', _e('Message'), array('class'=>'col-sm-2 control-label', 'for'=>'message'))?>
                                            <div class="col-md-6">
                                                <?= FORM::textarea('message', Core::request('message'), array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>
                                            </div>
                                        </div>
                                        <?if(core::config('general.messaging') AND
                                            core::config('advertisement.price') AND
                                            core::config('advertisement.contact_price')):?>
                                            <div class="form-group">
                                                <?= FORM::label('price', _e('Price'), array('class'=>'col-sm-2 control-label', 'for'=>'price'))?>
                                                <div class="col-md-6">
                                                    <?= FORM::input('price', Core::post('price'), array('placeholder' => html_entity_decode(i18n::money_format(1, $ad->currency())), 'class' => 'form-control', 'id' => 'price', 'type'=>'text'))?>
                                                </div>
                                            </div>
                                        <?endif?>
                                        <!-- file to be sent-->
                                        <?if(core::config('advertisement.upload_file') AND core::config('general.messaging') != TRUE):?>
                                            <div class="form-group">
                                                <?= FORM::label('file', _e('File'), array('class'=>'col-sm-2 control-label', 'for'=>'file'))?>
                                                <div class="col-md-6">
                                                    <?= FORM::file('file', array('placeholder' => __('File'), 'class'=>'form-control', 'id' => 'file'))?>
                                                </div>
                                            </div>
                                        <?endif?>
                                        <?if (core::config('advertisement.captcha') != FALSE):?>
                                            <div class="form-group">
                                                <?=FORM::label('captcha', _e('Captcha'), array('class'=>'col-sm-2 control-label', 'for'=>'captcha'))?>
                                                <div class="col-md-4">
                                                    <?if (Core::config('general.recaptcha_active')):?>
                                                        <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
                                                    <?else:?>
                                                        <?=captcha::image_tag('contact')?><br />
                                                        <?= FORM::input('captcha', "", array('class'=>'form-control', 'id' => 'captcha', 'required'))?>
                                                    <?endif?>
                                                </div>
                                            </div>
                                        <?endif?>
                                        <div class="modal-footer">
                                            <?= FORM::button(NULL, _e('Contact Us'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'user_contact' , 'id'=>$ad->id_ad))))?>
                                        </div>
                                    </fieldset>
                                <?= FORM::close()?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?if (core::config('advertisement.phone')==1 AND strlen($ad->phone)>1):?>
                <div class="btn-group" role="group">
                    <a class="btn btn-warning" href="tel:<?=$ad->phone?>">
                        <span class="glyphicon glyphicon-earphone"></span>&nbsp;&nbsp;
                        <?=_e('Phone').': '.$ad->phone?>
                    </a>
                </div>
            <?endif?>
        <?endif?>
    </div>

    <div class="clearfix"></div><br>
    <?if(core::config('advertisement.sharing')==1):?>
        <?=View::factory('share')?>
        <div class="clearfix"></div><br>
    <?endif?>
    <?=$ad->qr()?>
    <?=$ad->map()?>
    <?=$ad->instagram()?>
    <?=$ad->related()?>
    <?=$ad->comments()?>
    <?if(core::config('advertisement.report')==1):?>
        <?=$ad->flagad()?>
    <?endif?>
    <?=$ad->structured_data()?>

    <!-- modal-gallery is the modal dialog used for the image gallery -->
    <div class="modal fade" id="modal-gallery">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body"><div class="modal-image"></div></div>
                <div class="modal-footer">
                    <a class="btn btn-info modal-prev"><i class="glyphicon glyphicon-arrow-left glyphicon"></i> <?=_e('Previous')?></a>
                    <a class="btn btn-primary modal-next"><?=_e('Next')?> <i class="glyphicon glyphicon-arrow-right glyphicon"></i></a>
                    <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="glyphicon glyphicon-play glyphicon"></i> <?=_e('Slideshow')?></a>
                    <a class="btn modal-download" target="_blank"><i class="glyphicon glyphicon-download"></i> <?=_e('Download')?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div id="blueimp-gallery" class="blueimp-gallery">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>

        <div class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body next"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left prev">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-primary pull-left next">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?endif?>
