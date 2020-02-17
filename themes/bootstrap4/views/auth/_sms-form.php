<?= FORM::open($form_action, ['method' => 'post', 'class' => 'auth']) ?>
    <?= Form::errors() ?>

    <div class="form-group">
        <label>
            <?= _e('We have sent an SMS code to your phone number') ?>
            <?= substr($phone, 0, 3)?>*****<?= substr($phone, -4) ?>
        </label>
    </div>

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
        <?= Form::button('forgot-submit', _e('Send'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>

    <?= Form::redirect() ?>
    <?= Form::CSRF('sms') ?>
<?= Form::close() ?>
