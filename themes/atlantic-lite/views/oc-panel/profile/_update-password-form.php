<div class="card mb-4">
    <div class="card-body">
        <?= FORM::open(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'changepass']), ['method' => 'post', 'class' => '']) ?>
            <h5 class="card-title"><?=_e('Change password')?></h5>

            <?=Form::errors()?>

            <div class="form-group">
                <?= Form::label('edit-profile-password1', _e('New password')) ?>
                <?= Form::input('password1', $user->address, [
                    'type' => 'password',
                    'id' => 'edit-profile-password1',
                    'class' => 'form-control',
                    'placeholder' => __('Password'),
                ]) ?>
            </div>

            <div class="form-group">
                <?= Form::label('edit-profile-password2', _e('Repeat password')) ?>
                <?= Form::input('password2', $user->address, [
                    'type' => 'password',
                    'id' => 'edit-profile-password2',
                    'class' => 'form-control',
                    'placeholder' => __('Type your password twice to change'),
                ]) ?>
            </div>

            <div class="mt-3">
                <?= Form::button('password-submit', _e('Update'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
            </div>
        <?= FORM::close() ?>
    </div>
</div>
