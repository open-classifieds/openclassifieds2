<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">
            <?= _e('Stripe Connect') ?>
        </h5>

        <p class="text-muted">
            <?= sprintf(__('Sell your items with credit card using stripe. Our platform charges %s percentage, per transaction.'), Core::config('payment.stripe_appfee')) ?>
        </p>

        <? if (Core::config('payment.stripe_connect_legacy')) : ?>
            <? if ($user->stripe_user_id != ''): ?>
                <p>
                    Stripe connected <?= $user->stripe_user_id ?>
                    <br>
                    Reconnect:
                    <br>
                </p>
            <? endif ?>

            <a class="btn btn-primary" href="<?= Route::url('default', ['controller' => 'stripe', 'action' => 'connect', 'id' => 'now']) ?>">
                <span class="glyphicon glyphicon-usd" aria-hidden="true"></span> Connect with Stripe
            </a>
        <? else : ?>
            <p class="mt-3">
                <? if (StripeKO::connected_account_with_charges_enabled($user)) : ?>
                    <a class="btn btn-primary" href="<?= Route::url('default', ['controller' => 'stripe', 'action' => 'log_into_connected_account', 'id' => 'now']) ?>">
                        <?= _e('View Stripe account') ?>
                    </a>
                <? else : ?>
                    <a class="btn btn-primary" href="<?= Route::url('default', ['controller' => 'stripe', 'action' => 'connect_express', 'id' => 'now']) ?>">
                        <?= _e('Connect with Stripe') ?>
                    </a>
                <? endif ?>
            </p>
        <? endif ?>
    </div>
</div>
