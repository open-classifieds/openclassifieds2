<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-follow mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <ul
            id="Widget_RecentlySearched"
            data-url="<?= Route::url('search') ?>"
            data-max-items="<?= $widget->max_items ?>"
        ></ul>
    </div>
</div>
