<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="card card-categories mb-3">
    <? if ($widget->widget_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?=$widget->widget_title ?></span>
        </div>
    <? endif ?>

    <div class="list-group list-group-flush">
        <? foreach( $widget->posts as $post) : ?>
            <a href="<?= Route::url('blog', ['seotitle' => $post->seotitle]) ?>" title="<?=HTML::chars($post->title)?>"
                class="list-group-item list-group-item-action d-flex justify-content-between"
                title="<?= HTML::chars($post->title) ?>">
                <div>
                    <?= $post->title ?>
                </div>
                <div>
                    <i class="fas fa-angle-right"></i>
                </div>
            </a>
        <? endforeach ?>
    </div>
</div>
