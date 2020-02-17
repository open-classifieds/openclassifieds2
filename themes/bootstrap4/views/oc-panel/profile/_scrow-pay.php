<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">
            <?=_e('Escrow Pay')?>
        </h5>

        <p class="text-muted">
            <?=__('Buy and sell items with Escrow.')?>
        </p>

        <? if($user->escrow_api_key != ''): ?>
            <div class="alert alert-success">
                <?= __('Escrow connected.') ?>
            </div>
        <? endif ?>

        <?= FORM::open(Route::url('oc-panel', ['controller' => 'escrow', 'action' => 'update_api_key']), ['method' => 'post', 'class' => '', 'enctype'=>'multipart/form-data']) ?>
            <div class="form-group">
                <?= FORM::label('escrow_email', _e('Escrow email'), ['class' => 'control-label', 'for' => 'escrow_email']) ?>

                <?= FORM::input('escrow_email', $user->escrow_email, ['class' => 'form-control', 'id' => 'escrow_email', 'type' => 'escrow_email', 'required', 'placeholder' => __('Email')]) ?>

                <small class="form-text text-muted">
                    <a href="https://www.escrow.com/signup-page" target="_blank"><?= __('Create an Escrow account.') ?></a>
                </small>
            </div>

            <div class="form-group">
                <?= FORM::label('escrow_api_key', _e('API Key'), ['class' => 'control-label', 'for' => 'escrow_api_key']) ?>

                <?= FORM::input('escrow_api_key', $user->escrow_api_key, ['class' => 'form-control', 'id' => 'escrow_api_key', 'required', 'placeholder' => __('API Key')])?>

                <small class="form-text text-muted">
                    <a href="https://www.escrow.com/integrations/portal/api" target="_blank"><?= __('Create an API key.') ?></a>
                </small>
            </div>

            <button type="submit" class="btn btn-primary">
                <?if ($user->escrow_api_key == ''):?>
                    <?=_e('Connect')?>
                <?else:?>
                    <?=_e('Reconnect')?>
                <?endif?>
            </button>
        <?= FORM::close()?>
    </div>
</div>
