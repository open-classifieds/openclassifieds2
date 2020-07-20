<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?= View::factory('auth/_social') ?>

<?= FORM::open(Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'login']), ['method' => 'post', 'class' => 'auth']) ?>

    <div class="form-group">
        <?= Form::label('login-email', _e('Email')) ?>
        <?= Form::input('email', NULL, [
            'type' => 'email',
            'id' => 'login-email',
            'class' => 'form-control',
            'placeholder' => __('Email'),
            'required'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Form::label('login-password', _e('Password')) ?>
        <?= Form::password('password', NULL, [
            'id' => 'login-password',
            'class' => 'form-control',
            'placeholder' => __('Password'),
            'required'
        ]) ?>
        <small>
            <a data-toggle="modal"
                data-dismiss="modal"
                data-target="#forgot-modal"
                href="<?= Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'forgot']) ?>#forgot-modal">
                <?= _e('Forgot password?') ?>
            </a>
        </small>
    </div>

    <div class="form-check">
        <?= Form::checkbox('remember', 1, TRUE, ['id' => 'login-remember', 'class' => 'form-check-input']) ?>
        <label class="form-check-label" for="login-remember"><?= _e('Remember me') ?></label>
    </div>

    <div class="text-center mt-3">
        <?= Form::button('login-submit', _e('Login'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>

    <div class="text-center mt-3">
        <small>
            <?= _e('Don’t Have an Account?') ?>
            <a data-toggle="modal"
                data-dismiss="modal"
                data-target="#register-modal"
                href="<?= Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'register']) ?>">
                <?= _e('Register') ?>
            </a>
        </small>
    </div>

    <?= Form::redirect() ?>
    <?= Form::CSRF('login') ?>
<?= Form::close() ?>

<?if (Core::config('general.sms_auth') == TRUE ):?>
    <?= View::factory('auth/phone-login') ?>
<?endif?>
