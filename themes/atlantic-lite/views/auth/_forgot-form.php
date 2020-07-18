<?= FORM::open(Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'forgot']), ['method' => 'post', 'class' => 'auth']) ?>

    <div class="form-group">
        <?= Form::label('forgot-email', _e('Email')) ?>
        <?= Form::input('email', NULL, [
            'type' => 'email',
            'id' => 'forgot-email',
            'class' => 'form-control',
            'placeholder' => __('Email'),
            'required'
        ]) ?>
    </div>

    <div class="text-center mt-3">
        <?= Form::button('forgot-submit', _e('Send'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
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
    <?= Form::CSRF('forgot') ?>
<?= Form::close() ?>
