<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-featured mb-3">
    <?if ($widget->featured_title!=''):?>
        <div class="card-header">
            <span class="h6"><?= $widget->featured_title ?></span>
        </div>
    <?endif?>

    <div class="card-body">
        <ul class="list-unstyled">
            <?  foreach($widget->ads as $ad): ?>
                <li class="media">
                    <? if($ad->get_first_image() !== NULL): ?>
                        <img
                            src="<?= Core::imagefly($ad->get_first_image('image'), 250, 250) ?>"
                            alt="<?= HTML::chars($ad->title) ?>"
                            class="img-fluid tw-w-16 mr-3"
                        >
                    <? else: ?>
                        <img
                            data-src="holder.js/250x250?<?= str_replace('+', ' ', http_build_query(array('text' => $ad->category->name, 'size' => 14, 'auto' => 'yes'))) ?>"
                            class="img-fluid tw-w-16 mr-3"
                        >
                    <? endif ?>

                    <div class="media-body">
                        <h5 class="mt-0 mb-1">
                            <a href="<?=Route::url('ad',array('seotitle'=>$ad->seotitle,'category'=>$ad->category->seoname))?>" title="<?=HTML::chars($ad->title)?>">
                                <? if($widget->placeholder != 'header'): ?>
                                    <?= Text::limit_chars(Text::removebbcode($ad->title), 30, NULL, TRUE) ?>
                                <? else: ?>
                                    <?= Text::limit_chars(Text::removebbcode($ad->title), 45, NULL, TRUE) ?>
                                <? endif ?>
                            </a>
                        </h5>

                        <? if($widget->placeholder != 'header'): ?>
                            <?= Text::limit_chars(Text::removebbcode($ad->description), 30, NULL, TRUE) ?>
                        <? else: ?>
                            <?= Text::limit_chars(Text::removebbcode($ad->description), 150, NULL, TRUE) ?>
                        <? endif ?>
                    </div>
                </li>
            <? endforeach ?>
        </ul>
    </div>
</div>
