<?php defined('SYSPATH') or die('No direct script access.'); ?>

<form method="post" action="<?= $url ?>">
    <input type="hidden" name="names" value="<?= $order->user->name ?>">
    <input type="hidden" name="amount" value="<?= $order->amount ?>">
    <input type="hidden" name="email_address" value="<?= $order->user->email ?>">
    <input type="hidden" name="currency" value="NGN">
    <input type="hidden" name="merch_txnref" value="<?= $order->id_order ?>">
    <input type="hidden" name="merchantid" value="<?= $merchantid ?>">
    <input type="hidden" name="redirect_url" value="<?= Route::url('default', ['controller' => 'zenith', 'action' => 'result', 'id' => 1]) ?>">
    <? if (empty($order->user->phone)) : ?>
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="phone_number" class="form-control" placeholder="<?= __('Phone') ?>">
                <span class="input-group-btn">
                    <input name="cmdsubmit" class="btn btn-success" type="submit" value="<?= __('Pay with Card') ?>" >
                </span>
            </div>
        </div>
    <? else : ?>
        <input type="hidden" name="phone_number" value="<?= $order->user->phone ?>">
        <input name="cmdsubmit" class="btn btn-success" type="submit" value="<?= __('Pay with Card') ?>" >
    <? endif ?>
</form>
