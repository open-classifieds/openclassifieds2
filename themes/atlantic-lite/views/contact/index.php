<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <? if(core::config('general.contact_page') != ''): ?>
            <? $content = Model_Content::get_by_title(core::config('general.contact_page')) ?>

            <div class="mb-3">
                <h1 class="h2"><?= $content->title ?></h1>
            </div>

            <p><?=$content->description?></p>
        <?else:?>
            <div class="mb-3">
                <h1 class="h2"><?=_e('Contact Us')?></h1>
            </div>
        <?endif?>

        <div class="mb-4">
            <?= Form::errors() ?>

            <?= Form::open(Route::url('contact'), ['class'=>'form-horizontal', 'enctype'=>'multipart/form-data'])?>
                <?if (!Auth::instance()->logged_in()):?>
                    <div class="form-group">
                        <?= Form::label('name', _e('Name'), ['for'=>'name'])?>
                        <?= Form::input('name', Core::request('name'), ['placeholder' => __('Name'), 'class' => 'form-control', 'id' => 'name', 'required']) ?>
                    </div>
                    <div class="form-group">
                        <?= Form::label('email', _e('Email'), ['for'=>'email'])?>
                        <?= Form::input('email', Core::request('email'), ['placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'email', 'type'=>'email', 'required']) ?>
                    </div>
                <?endif?>

                <div class="form-group">
                    <?= Form::label('subject', _e('Subject'), ['for'=>'subject'])?>
                    <?= Form::input('subject', Core::request('subject'), ['placeholder' => __('Subject'), 'class' => 'form-control', 'id' => 'subject']) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('message', _e('Message'), ['for'=>'message'])?>
                    <?= Form::textarea('message', Core::request('message'), ['class'=>'form-control', 'placeholder' => __('Message'), 'name' => 'message', 'id' => 'message', 'rows' => 7, 'required']) ?>
                </div>

                <?if (core::config('advertisement.captcha') != FALSE):?>
                    <div class="form-group">
                        <?if (Core::config('general.recaptcha_active')):?>
                            <?= View::factory('recaptcha', ['id' => 'recaptcha1']) ?>
                        <?else:?>
                            <?=_e('Captcha')?>*:<br>
                            <?= Captcha::image_tag('contact')?><br>
                            <?= Form::input('captcha', "", ['class' => 'form-control', 'id' => 'captcha', 'required']) ?>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="form-group">
                    <?= Form::button(NULL, _e('Contact Us'), ['type'=>'submit', 'class'=>'btn btn-success'])?>
                </div>
            </fieldset>
            <?= Form::close()?>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
