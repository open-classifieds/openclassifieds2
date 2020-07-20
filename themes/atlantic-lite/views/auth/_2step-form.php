<?= FORM::open($form_action, ['method' => 'post', 'class' => 'auth']) ?>
    <?= Form::errors() ?>

    <div class="form-group">
        <?= Form::label('code', _e('Verification Code')) ?>
        <?= Form::input('code', NULL, [
            'type' => 'text',
            'id' => 'code',
            'class' => 'form-control',
            'placeholder' => __('Code'),
            'required'
        ]) ?>
    </div>

    <div class="text-center mt-3">
        <?= Form::button('2step-submit', _e('Send'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>

    <?= Form::redirect() ?>
    <?= Form::CSRF('2step') ?>
<?= Form::close() ?>
