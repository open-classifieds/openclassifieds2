<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="mb-4">
    <h1 class="h2"><?=_e('My Advertisements')?></h1>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="border-top-0"><?=_e('Name')?></th>
                    <th class="border-top-0"><?=_e('Category')?></th>
                    <th class="border-top-0"><?=_e('Location')?></th>
                    <th class="border-top-0"><?=_e('Status')?></th>
                    <th class="border-top-0"><?=_e('Date')?></th>
                    <?if( core::config('payment.to_featured')):?>
                        <th class="border-top-0"><?=_e('Featured')?></th>
                    <?endif?>
                    <th class="border-top-0"></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($ads as $ad):?>
                    <?= View::factory('oc-panel/myads/_ad', compact('ad')) ?>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4"><?=$pagination?></div>
