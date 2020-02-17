<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=$topic->title?></h1>

            <?if($previous->loaded()):?>
                <a class="badge badge-info" href="<?=Route::url('forum-topic',  array('seotitle'=>$previous->seotitle,'forum'=>$forum->seoname))?>" title="<?=HTML::chars($previous->title)?>">
                <i class="fa fa-backward"></i> <?=$previous->title?></i></a>
            <?endif?>
            <?if($next->loaded()):?>
                <a class="badge badge-info" href="<?=Route::url('forum-topic',  array('seotitle'=>$next->seotitle,'forum'=>$forum->seoname))?>" title="<?=HTML::chars($next->title)?>">
                <?=$next->title?> <i class="fa fa-forward"></i></a>
            <?endif?>
        </div>

        <div class="card mb-4">
            <ul class="list-group list-group-flush list-group-comments">
                <li class="list-group-item py-4">
                    <div class="media">
                        <img alt="<?=HTML::chars($topic->user->name)?>" src="<?=$topic->user->get_profile_image()?>" class="tw-w-10 tw-rounded-full mr-3">
                        <div class="media-body">
                            <div class="mb-2">
                                <span class="h6 mb-0">
                                    <?if (in_array('profile', Route::all())) :?>
                                        <a href="<?=Route::url('profile', array('seoname'=>$topic->user->seoname)) ?>">
                                            <?=$topic->user->name?>
                                        </a>
                                    <?else :?>
                                        <?=$topic->user->name?>
                                    <?endif?>
                                </span>
                            </div>
                            <p>
                                <?=Text::bb2html($topic->description,TRUE)?>
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <?if (Auth::instance()->logged_in()):?>
                                        <a class="btn btn-sm btn-outline-primary" href="#reply_form"><?=_e('Reply')?></a>
                                    <?else:?>
                                        <a class="btn btn-sm btn-outline-primary" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                                            <?=_e('Reply')?>
                                        </a>
                                    <?endif?>

                                    <?if(Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()):?>
                                        <a class="btn btn-sm btn-outline-secondary" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?endif?>
                                </div>
                                <small class="text-muted"><?=Date::fuzzy_span(Date::mysql2unix($topic->created))?> • <?=$topic->created?></small>
                            </div>
                        </div>
                    </div>
                </li>

                <?foreach ($replies as $reply):?>
                    <li class="list-group-item py-4">
                        <div class="media">
                            <img alt="<?=$reply->user->get_profile_image()?>" src="<?=$reply->user->get_profile_image()?>" class="tw-w-10 tw-rounded-full mr-3">
                            <div class="media-body">
                                <div class="mb-2">
                                    <span class="h6 mb-0">
                                        <?if (in_array('profile', Route::all())) :?>
                                            <a href="<?=Route::url('profile', array('seoname'=>$reply->user->seoname)) ?>">
                                                <?=$reply->user->name?>
                                            </a>
                                        <?else :?>
                                            <?=$reply->user->name?>
                                        <?endif?>
                                    </span>
                                </div>
                                <p>
                                    <?=Text::bb2html($reply->description,TRUE)?>
                                </p>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <?if (Auth::instance()->logged_in()):?>
                                            <a class="btn btn-sm btn-outline-primary" href="#reply_form"><?=_e('Reply')?></a>
                                        <?else:?>
                                            <a class="btn btn-sm btn-outline-primary" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                                                <?=_e('Reply')?>
                                            </a>
                                        <?endif?>

                                        <?if(Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()):?>
                                            <a class="btn btn-sm btn-outline-secondary" href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$reply->id_post)) ?>">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?endif?>
                                    </div>
                                    <small class="text-muted"><?=Date::fuzzy_span(Date::mysql2unix($reply->created))?> • <?=$reply->created?></small>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach ?>
            </ul>

            <? if ($pagination) : ?>
                <div class="card-body">
                    <?= $pagination ?>
                </div>
            <? endif ?>

            <?if($topic->status==Model_POST::STATUS_ACTIVE AND Auth::instance()->logged_in()):?>
                <form class="card-body" id="reply_form" method="post" action="<?=Route::url('forum-topic',array('seotitle'=>$topic->seotitle,'forum'=>$forum->seoname))?>">
                    <? if($errors): ?>
                        <p class="message"><?=_e('Some errors were encountered, please check the details you entered.')?></p>

                        <ul class="errors">
                            <? foreach ($errors as $message): ?>
                                <li><?= $message ?></li>
                            <? endforeach ?>
                        </ul>
                    <? endif?>

                    <div class="form-group">
                        <textarea name="description" rows="10" class="form-control" required><?=core::post('description',_e('Reply here'))?></textarea>
                    </div>

                    <?if (core::config('advertisement.captcha') != FALSE OR core::config('general.captcha') != FALSE):?>
                        <div class="form-group">
                                <div class="col-md-4">
                                    <?if (Core::config('general.recaptcha_active')):?>
                                        <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
                                    <?else:?>
                                        <?=_e('Captcha')?>*:<br />
                                        <?=captcha::image_tag('new-reply-topic')?><br />
                                        <?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
                                    <?endif?>
                                </div>
                        </div>
                    <?endif?>

                    <button type="submit" class="btn btn-primary"><?=_e('Reply')?></button>
                </form>
            <?else:?>
                <a
                    class="btn btn-success pull-right"
                    data-toggle="modal"
                    data-dismiss="modal"
                    href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal"
                >
                    <?=_e('Login to reply')?>
                </a>
            <?endif?>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
