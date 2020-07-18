<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-userlocation mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <? if($widget->location !== FALSE): ?>
            <p>
                <a href="<?= Route::url('list', ['location' => $widget->location->seoname]) ?>">
                    <?= $widget->location->name ?>
                </a>
                <small>
                    (<a href="<?= Route::url('default') ?>?user_location=0">
                        <?= _e('Change Location') ?>
                    </a>)
                </small>
            </p>
        <? endif ?>
    </div>
</div>
