<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <? if (Core::config('general.ewallet_add_money')) : ?>
        <a
            href="<?= Route::url('oc-panel', ['controller' => 'ewallet', 'action' => 'add_money']) ?>"
            class="pull-right btn btn-primary">
            <?= __('Add money') ?>
        </a>
    <? endif ?>

    <h1><?=_e('Transactions')?></h1>
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
                    <tr id="tr<?=$transaction->id_transaction?>">

                        <td><?= $transaction->id_transaction ?></td>

                        <td><?= i18n::money_format($transaction->amount, 'YCL') ?></td>

                        <td><?= $transaction->user_from->loaded() ? $transaction->user_from->name : NULL ?></td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>
