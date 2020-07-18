<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-ads mb-3">
    <? if ($widget->ads_title != '') : ?>
        <div class="card-header">
            <span class="h6"><?= $widget->ads_title ?></span>
        </div>
    <? endif ?>

    <div class="list-group list-group-flush">
        <? foreach($widget->ads as $ad): ?>
            <a href="<?= Route::url('ad', ['seotitle' => $ad->seotitle, 'category' => $ad->category->seoname]) ?>"
                class="list-group-item list-group-item-action d-flex justify-content-between"
                title="<?= HTML::chars($ad->title) ?>">
                <div>
                    <span><?= $ad->title ?></span>
                </div>
                <div>
                    <i class="fas fa-angle-right"></i>
                </div>
            </a>
        <? endforeach ?>
    </div>
</div>
