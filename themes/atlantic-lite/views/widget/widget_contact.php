<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-contact mb-3">
    <? if ($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body py-3">
        <?= Form::open(Route::url('default', [
            'controller' => 'contact',
            'action' => 'user_contact',
            'id' => $widget->id_ad,
        ]), ['enctype'=>'multipart/form-data']) ?>
            <? if(Core::config('general.messaging') == TRUE AND ! Auth::instance()->logged_in()): ?>
                <div class="alert alert-warning">
                    <?=_e('Please, login before contact the advertiser!')?>
                </div>

                <div class="form-group">
                    <?= Form::label('message', _e('Message'), [
                        'class' => 'control-label',
                        'for' => 'message',
                    ])?>
                    <?= Form::textarea('message', '', [
                        'class' => 'form-control',
                        'placeholder' => __('Message'),
                        'name' => 'message',
                        'id' => 'message',
                        'rows' => 2,
                        'required',
                        'disabled'
                    ])?>
                </div>

                <? if (Core::config('advertisement.price') AND Core::config('advertisement.contact_price')) : ?>
                    <div class="form-group">
                        <?= Form::label('price', _e('Price'), [
                            'class' => 'control-label',
                            'for' => 'price',
                        ])?>
                        <?= Form::input('price', Core::post('price'), [
                            'placeholder' => html_entity_decode(i18n::money_format(1, $widget->currency )),
                            'class' => 'form-control',
                            'id' => 'price',
                            'type'=>'text',
                            'disabled',
                        ])?>
                    </div>
                <? endif ?>

                <div class="form-group">
                    <?= Form::button('submit', _e('Send Message'), [
                        'type' => 'submit',
                        'class' => 'btn btn-block btn-success disabled',
                    ])?>
                </div>
            <?else: ?>
                <? if (!Auth::instance()->logged_in()): ?>
                    <div class="form-group">
                        <?= Form::label('name', _e('Name'), [
                            'class' => 'control-label',
                            'for' => 'name',
                        ])?>
                        <?= Form::input('name', '', [
                            'placeholder' => __('Name'),
                            'class' => 'form-control',
                            'id' => 'name',
                            'required'
                        ])?>
                    </div>

                    <div class="form-group">
                        <?= Form::label('email', _e('Email'), [
                            'class'=>'control-label',
                            'for'=>'email',
                        ])?>
                        <?= Form::input('email', '', [
                            'placeholder' => __('Email'),
                            'class' => 'form-control',
                            'id' => 'email',
                            'type' => 'email',
                            'required',
                        ])?>
                    </div>
                <?endif?>

                <? if(Core::config('general.messaging') != TRUE): ?>
                    <div class="form-group">
                        <?= Form::label('subject', _e('Subject'), [
                            'class' => 'control-label',
                            'for' => 'subject',
                        ])?>
                        <?= Form::input('subject', '', [
                            'placeholder' => __('Subject'),
                            'class' => 'form-control',
                            'id' => 'subject',
                        ])?>
                    </div>
                <?endif?>

                <div class="form-group">
                    <?= Form::label('message', _e('Message'), [
                        'class' => 'control-label',
                        'for' => 'message',
                    ])?>
                    <?= Form::textarea('message', '', [
                        'class' => 'form-control',
                        'placeholder' => __('Message'),
                        'name' => 'message',
                        'id' => 'message',
                        'rows' => 2,
                        'required',
                    ])?>
                </div>

                <? if(Core::config('general.messaging') AND
                    Core::config('advertisement.price') AND
                    Core::config('advertisement.contact_price')): ?>
                    <div class="form-group">
                        <?= Form::label('price', _e('Price'), [
                            'class' => 'control-label',
                            'for' => 'price'
                        ])?>
                        <?= Form::input('price', Core::post('price'), [
                            'placeholder' => html_entity_decode(i18n::money_format(1, $widget->currency )),
                            'class' => 'form-control',
                            'id' => 'price',
                            'type' => 'text'
                        ])?>
                    </div>
                <?endif?>

                <? if(Core::config('advertisement.upload_file') AND Core::config('general.messaging') != TRUE): ?>
                    <div class="form-group">
                        <?= Form::label('file', _e('File'), [
                            'class' => 'control-label',
                            'for' => 'file'])?>
                        <?= Form::file('file', [
                            'placeholder' => __('File'),
                            'class' => 'form-control',
                            'id' => 'file'
                        ])?>
                    </div>
                <?endif?>

                <? if(Core::config('advertisement.captcha') != FALSE): ?>
                    <div class="form-group">
                        <? if (Core::config('general.recaptcha_active')): ?>
                            <?= View::factory('recaptcha', ['id' => 'recaptcha2']) ?>
                        <?else: ?>
                            <?= _e('Captcha')?>*:<br />
                            <?= Captcha::image_tag('contact') ?><br />
                            <?= Form::input('captcha', '', [
                                'class' => 'form-control',
                                'id' => 'captcha',
                                'required'
                            ])?>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="form-group">
                    <?= Form::button(NULL, _e('Send Message'), [
                        'type'=>'submit',
                        'class'=>'btn btn-block btn-success',
                    ])?>
                </div>
            <?endif?>
        <?= Form::close() ?>
    </div>
</div>
