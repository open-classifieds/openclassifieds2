<?= FORM::open(Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'phonelogin']), ['method' => 'post', 'class' => 'auth']) ?>
    <div class="form-group">
        <?= Form::label('phone', _e('Phone')) ?>
        <?= Form::input('phone', '', [
            'type' => 'phone',
            'id' => 'phone',
            'class' => 'form-control',
            'placeholder' => __('Phone'),
            'required',
            'data-country' => core::config('general.country'),
        ]) ?>
    </div>

    <div class="text-center mt-3">
        <?= Form::button('', _e('Login'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
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

    <?=Form::redirect()?>
    <?=Form::CSRF('phonelogin')?>
<?= Form::close() ?>
