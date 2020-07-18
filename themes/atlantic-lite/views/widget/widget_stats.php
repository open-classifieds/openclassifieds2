<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-stats mb-3">
    <?if ($widget->text_title!=''):?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <?endif?>

    <div class="card-body">
        <? if(!is_null($widget->info)): ?>
            <p>
                <?= $widget->info->views ?> <strong><?= _e('views') ?></strong>
            </p>
            <p>
                <?= $widget->info->ads ?> <strong><?= _e('ads') ?></strong>
            </p>
            <p>
                <?= $widget->info->users ?> <strong><?= _e('users') ?></strong>
            </p>
        <? endif ?>
    </div>
</div>
