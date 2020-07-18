<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="mb-4">
    <h1 class="h2"><?=_e('Orders')?></h1>
</div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?=_e('Status') ?></th>
                    <th><?=_e('Product') ?></th>
                    <th><?=_e('Amount') ?></th>
                    <th><?=_e('Ad') ?></th>
                    <th><?=_e('Date') ?></th>
                    <th><?=_e('Date Paid') ?></th>
                    <th><?=_e('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach($orders as $order):?>
                    <?= View::factory('oc-panel/profile/orders/_order', compact('order')) ?>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>
