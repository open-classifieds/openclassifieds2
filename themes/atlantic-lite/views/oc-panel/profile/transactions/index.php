<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="mb-4">
    <div class="tw-flex tw-justify-between tw-items-center" style="justify-content: space-between;">
        <h1 class="h2"><?=_e('Transactions')?></h1>

        <? if (Core::config('general.ewallet_add_money')) : ?>
            <a
                href="<?= Route::url('oc-panel', ['controller' => 'ewallet', 'action' => 'add_money']) ?>"
                class="btn btn-primary">
                <?= __('Add money') ?>
            </a>
        <? endif ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?=_e('Amount') ?></th>
                    <th><?=_e('From') ?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach($transactions as $transaction):?>
                    <?= View::factory('oc-panel/profile/transactions/_transaction', compact('transaction')) ?>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>
