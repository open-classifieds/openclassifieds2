<?= FORM::open(Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'request']), ['method' => 'post', 'class' => 'auth']) ?>
    <?= Form::errors() ?>

    <div class="form-group">
        <?= Form::label('name', _e('Name')) ?>
        <?= Form::input('name', NULL, [
            'type' => 'text',
            'id' => 'name',
            'class' => 'form-control',
            'placeholder' => __('Name'),
            'required'
        ]) ?>
    </div>

    <div class="form-group">
        <?= Form::label('email', _e('Email')) ?>
        <?= Form::input('email', NULL, [
            'type' => 'email',
            'id' => 'email',
            'class' => 'form-control',
            'placeholder' => __('Email'),
            'required'
        ]) ?>
    </div>

    <div class="text-center mt-3">
        <?= Form::button('send', _e('Send'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>

    <?= Form::CSRF('request') ?>
<?= Form::close() ?>
