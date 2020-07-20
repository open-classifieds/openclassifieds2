<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?= View::factory('auth/_social') ?>

<?= FORM::open(Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'register']), ['method' => 'post', 'class' => 'auth']) ?>
    <div class="form-group">
        <?= Form::label('register-name', _e('Name')) ?>
        <?= Form::input('name', Request::current()->post('name'), [
            'type' => 'text',
            'id' => 'register-name',
            'class' => 'form-control',
            'placeholder' => __('Name'),
            'required'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Form::label('register-email', _e('Email')) ?>
        <?= Form::input('email', Request::current()->post('email'), [
            'type' => 'email',
            'id' => 'register-email',
            'class' => 'form-control',
            'placeholder' => __('Email'),
            'required'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Form::label('register-password', _e('New password')) ?>
        <?= Form::password('password1', NULL, [
            'id' => 'register-password',
            'class' => 'form-control',
            'placeholder' => __('Password'),
            'required'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Form::label('register-password2', _e('Repeat password')) ?>
        <?= Form::password('password2', NULL, [
            'id' => 'register-password2',
            'class' => 'form-control',
            'placeholder' => __('Password'),
            'required'
        ]) ?>
        <small class="form-text text-muted">
            <?= _e('Type your password twice') ?>
        </small>
    </div>

    <? if (core::config('advertisement.tos') != '') : ?>
        <div class="form-check">
            <?= Form::checkbox('tos', 1, FALSE, ['id' => 'register-tos', 'class' => 'form-check-input', 'required']) ?>
            <label class="form-check-label" for="register-tos">
                <a target="_blank" href="<?= Route::url('page', ['seotitle' => core::config('advertisement.tos')]) ?>"> <?= _e('Terms of service') ?></a>
            </label>
        </div>
    <? endif ?>

    <?= View::factory('auth/_register-custom-fields') ?>

    <? if (core::config('advertisement.captcha') != FALSE or core::config('general.captcha') != FALSE) : ?>
        <? if (Core::config('general.recaptcha_active')) : ?>
            <div class="form-group">
                <?= Captcha::recaptcha_display() ?>
                <div id="<?= isset($recaptcha_placeholder) ? $recaptcha_placeholder : 'recaptcha3' ?>"></div>
            </div>
        <? else : ?>
            <?= FORM::label('contact-captcha', _e('Captcha')) ?>
            <div class="form-row">
                <div class="form-group col-6 col-md-3">
                    <?= FORM::input('captcha', NULL, [
                        'class' => 'form-control',
                        'id' => 'register-captcha',
                        'required'
                    ]) ?>
                </div>
                <div class="form-group col-6 col-md-3">
                    <?= Captcha::image_tag('register') ?><br />
                </div>
            </div>
        <? endif ?>
    <? endif ?>

    <div class="text-center mt-3">
        <?= Form::button('register-submit', _e('Register'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>

    <div class="text-center mt-3">
        <small>
            <?= _e('Already Have an Account?') ?>
            <a data-toggle="modal"
                data-dismiss="modal"
                data-target="#login-modal"
                href="<?= Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'login']) ?>">
                <?= _e('Login') ?>
            </a>
        </small>
    </div>

    <?= Form::redirect() ?>
    <?= Form::CSRF('register') ?>
<?= Form::close() ?>

<? if (Core::config('general.sms_auth') == TRUE) : ?>
    <div class="page-header">
        <h2 class="h3"><?= _e('Phone Register') ?></h2>
    </div>
    <?= View::factory('auth/_phone-register-form') ?>
<? endif ?>
