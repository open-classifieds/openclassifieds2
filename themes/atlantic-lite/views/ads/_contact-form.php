<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?= Form::errors() ?>

<?= FORM::open(Route::url('default', ['controller' => 'contact', 'action' => 'user_contact', 'id' => $ad->id_ad]), ['method' => 'post', 'class' => '', 'enctype'=>'multipart/form-data']) ?>
    <? if (!Auth::instance()->get_user()) : ?>
        <div class="form-group">
            <?= Form::label('contact-name', _e('Name')) ?>
            <?= Form::input('name', Request::current()->post('name'), [
                'type' => 'text',
                'id' => 'contact-name',
                'class' => 'form-control',
                'placeholder' => __('Name'),
            ]) ?>
        </div>

        <div class="form-group">
            <?= Form::label('email', _e('Email')) ?>
            <?= Form::input('contact-email', Request::current()->post('email'), [
                'type' => 'email',
                'id' => 'contact-email',
                'class' => 'form-control',
                'placeholder' => __('Email'),
                'required'
            ]) ?>
        </div>
    <? endif ?>

    <? if(core::config('general.messaging') != TRUE): ?>
        <div class="form-group">
            <?= Form::label('contact-subject', _e('Subject')) ?>
            <?= Form::input('subject', Request::current()->post('subject'), [
                'type' => 'text',
                'id' => 'contact-subject',
                'class' => 'form-control',
                'placeholder' => __('Subject'),
            ]) ?>
        </div>
    <? endif ?>

    <div class="form-group">
        <?= Form::label('message', _e('Message')) ?>
        <?= Form::textarea('contact-message', Request::current()->post('message'), [
            'id' => 'contact-message',
            'class' => 'form-control',
            'placeholder' => __('Message'),
            'rows' => 2,
            'required'
        ]) ?>
    </div>

    <? if(core::config('general.messaging') AND
        core::config('advertisement.price') AND
        core::config('advertisement.contact_price')): ?>
        <div class="form-group">
            <?= Form::label('contact-price', _e('Price')) ?>
            <?= Form::input('price', Request::current()->post('price'), [
                'type' => 'text',
                'id' => 'contact-price',
                'class' => 'form-control',
                'placeholder' => html_entity_decode(i18n::money_format(1, $ad->currency())),
            ]) ?>
        </div>
    <? endif ?>

    <? if(core::config('advertisement.upload_file') AND core::config('general.messaging') != TRUE): ?>
        <div class="form-group">
            <?= Form::label('contact-file', _e('File')) ?>
            <?= Form::file('file', [
                'id' => 'contact-file',
                'class' => 'form-control',
                'placeholder' => __('File'),
            ]) ?>
        </div>
    <? endif ?>

    <? if (core::config('advertisement.captcha') != FALSE or core::config('general.captcha') != FALSE) : ?>
        <? if (Core::config('general.recaptcha_active')) : ?>
            <div class="form-group">
                <?= Captcha::recaptcha_display() ?>
                <div id="<?= isset($recaptcha_placeholder) ? $recaptcha_placeholder : 'recaptcha1' ?>"></div>
            </div>
        <? else : ?>
            <?= FORM::label('contact-captcha', _e('Captcha')) ?>
            <div class="form-row">
                <div class="form-group col-6 col-md-3">
                    <?= FORM::input('contact-captcha', NULL, [
                        'class' => 'form-control',
                        'id' => 'contact-captcha',
                        'required'
                    ]) ?>
                </div>
                <div class="form-group col-6 col-md-3">
                    <?= Captcha::image_tag('contact') ?><br />
                </div>
            </div>
        <? endif ?>
    <? endif ?>

    <div class="mt-3">
        <?= Form::button('contact-submit', _e('Contact Us'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>
<?= FORM::close() ?>
