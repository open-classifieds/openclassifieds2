<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-interactivemap mb-3">
    <? if($widget->languages_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->languages_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= i18n::get_display_language(i18n::$locale) ?> <span class="caret"></span>
        </button>
        <div class="dropdown-menu">
            <? foreach($widget->languages as $language): ?>
                <? if(i18n::$locale != $language): ?>
                    <a class="dropdown-item" href="<?= Route::url('default') ?>?language=<?= $language ?>">
                        <?= i18n::get_display_language($language) ?>
                    </a>
                <? endif ?>
            <? endforeach ?>
        </div>
    </div>
</div>
