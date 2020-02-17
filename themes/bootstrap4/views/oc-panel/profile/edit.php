<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-12 col-md-7">
        <?= View::factory('oc-panel/profile/_profile-form', compact('user', 'id_location', 'custom_fields')) ?>

        <?= View::factory('oc-panel/profile/_update-password-form', compact('user')) ?>

        <? if(Core::config('payment.stripe_connect') == 1): ?>
            <?= View::factory('oc-panel/profile/_stripe-connect', compact('user')) ?>
        <? endif ?>

        <? if(Core::config('payment.escrow_pay') == 1): ?>
            <?= View::factory('oc-panel/profile/_scrow-pay', compact('user')) ?>
        <? endif ?>

        <? if(Core::config('general.google_authenticator') == TRUE): ?>
            <?= View::factory('oc-panel/profile/_google-authenticator', compact('user')) ?>
        <? endif ?>

        <?= View::factory('oc-panel/profile/_profile-pictures', compact('user')) ?>

        <? if(Core::config('general.subscriptions') == 1): ?>
            <?= View::factory('oc-panel/profile/_subscription', compact('user')) ?>
        <? endif ?>
    </div>
</div>
