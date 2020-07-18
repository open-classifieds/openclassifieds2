<?= FORM::open($form_action, ['method' => 'post', 'class' => 'auth']) ?>
    <?=Form::errors()?>

    <div class="form-group">
        <?= Form::label('name', _e('Name')) ?>
        <?= Form::input('name', HTML::chars(Core::request('name')), [
            'type' => 'text',
            'id' => 'name',
            'class' => 'form-control',
            'placeholder' => __('Name'),
            'required',
        ]) ?>
    </div>

    <div class="form-group">
        <?= Form::label('email', _e('Email')) ?>
        <?= Form::input('email', HTML::chars(Core::post('email')), [
            'type' => 'email',
            'id' => 'email',
            'class' => 'form-control',
            'placeholder' => __('Email'),
            'required',
        ]) ?>
    </div>

    <div class="text-center mt-3">
        <?= Form::button('register-social', _e('Register'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>

    <?=Form::redirect()?>
    <?=Form::CSRF('register_social')?>
<?= Form::close() ?>
