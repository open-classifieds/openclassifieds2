<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-interactivemap mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <?=Core::Config('appearance.map_jscode')?>
    </div>
</div>
