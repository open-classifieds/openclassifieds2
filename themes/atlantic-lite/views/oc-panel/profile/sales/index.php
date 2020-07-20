<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="mb-4">
    <h1 class="h2"><?=_e('Sales')?></h1>
</div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?=_e('Amount') ?></th>
                    <th><?=_e('Buyer') ?></th>
                    <th><?=_e('Date') ?></th>
                    <th><?=_e('Ad') ?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach($orders as $sale):?>
                    <?= View::factory('oc-panel/profile/sales/_sale', compact('sale')) ?>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>
