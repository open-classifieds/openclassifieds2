<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="mb-4">
    <h1 class="h2"><?=_e('Inbox')?></h1>
</div>

<div class="btn-group tw-mb-4">
    <a
        href="<?= Route::url('oc-panel', ['controller' => 'messages', 'action' => 'index']) ?>"
        class="btn btn-light <?= (!is_numeric(core::get('status'))) ? 'active' : '' ?>"
    >
        <?=_e('All')?>
    </a>

    <a
        href="?status=<?=Model_Message::STATUS_NOTREAD?>"
        class="btn btn-light <?=(core::get('status', -1) == Model_Message::STATUS_NOTREAD)?'active':''?>"
    >
        <i class="fas fa-eye"></i> <?=_e('Unread')?>
    </a>

    <a
        href="?status=<?=Model_Message::STATUS_ARCHIVED?>"
        class="btn btn-light <?=(core::get('status',-1)==Model_Message::STATUS_ARCHIVED)?'active':''?>"
    >
        <i class="fas fa-archive"></i> <?=_e('Archieved')?>
    </a>

    <a href="?status=<?=Model_Message::STATUS_SPAM?>" class="btn btn-light <?=(core::get('status',-1)==Model_Message::STATUS_SPAM)?'active':''?>">
        <i class="fas fa-fire"></i> <?=_e('Spam')?>
    </a>
</div>

<? if (core::count($messages) > 0): ?>
    <table class="table table-striped">
        <thead>
             <tr>
                <th>
                    <?=_e('Title')?> / <?=_e('From')?>
                </th>
                <th>
                    <?=_e('Date')?>
                </th>
                <th>
                    <?=_e('Last Answer')?>
                </th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?foreach ($messages as $message):?>
                <?= View::factory('oc-panel/messages/_message', compact('message', 'user')) ?>
            <?endforeach?>
        </tbody>
    </table>
<?else:?>
    <div class="jumbotron text-center">
        <h2>
            <?=_e('You don’t have any messages yet.')?>
        </h2>
    </div>
<?endif?>

<?if(isset($pagination)):?>
    <div class="text-center">
        <?=$pagination?>
    </div>
<?endif?>
