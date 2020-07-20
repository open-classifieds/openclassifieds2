<?php defined('SYSPATH') or die('No direct script access.');?>

<h1>
    <?= $user->name . ' ' . __("Reviews")?>
</h1>

<hr>

<div class="row">
    <div class="col-md-12">
        <p>
            <? for ($i=0; $i < round($user->rate,1); $i++) : ?>
                <span class="glyphicon glyphicon-star"></span>
            <? endfor ?>

            (<?= round($user->rate,1) ?>/<?= Model_Review::RATE_MAX ?>)<span class="separator"> | </span>

            <span class="glyphicon glyphicon-comment"></span> <?=core::count($reviews)?>

            <?=_e('reviews')?>
        </p>
    </div>
</div>

<? if ($reviews !== NULL) : ?>
    <? foreach($reviews as $review) : ?>
        <article class="row">
            <div class="col-12 col-sm-12 col-md-3">
                <a title="<?= HTML::chars($review->user->name) ?>" class="thumbnail">
                    <img src="<?= $review->user->get_profile_image() ?>" alt="<?= __('Profile image') ?>" height="140px">
                </a>
            </div>
            <div class="col-12 col-sm-12 col-md-9">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <ul class="list-inline">
                            <li>
                                <i class="glyphicon glyphicon-calendar"></i> <span><?= $review->created ?></span>
                            </li>
                            <li>
                                <i class="glyphicon glyphicon-time"></i> <span><?= Date::fuzzy_span(Date::mysql2unix($review->created)) ?></span>
                            </li>
                            <li>
                                <i class="glyphicon glyphicon-user"></i> <a href="<?= Route::url('profile', ['seoname' => $review->user->seoname]) ?>"><?= $review->user->name ?></a>
                            </li>
                        </ul>

                        <?if ($review->rate !== NULL):?>
                            <div class="rating">
                                <h1 class="rating-num">
                                    <?= round($review->rate,2) ?>.0
                                </h1>
                                <? for ($i=0; $i < round($review->rate,1); $i++) : ?>
                                    <span class="glyphicon glyphicon-star"></span>
                                <? endfor ?>
                            </div>
                        <?endif?>

                        <? if ($review->ad->status != Model_Ad::STATUS_PUBLISHED) : ?>
                            <h5 class="h4">
                                <strong>
                                    <?= Text::limit_chars(Text::removebbcode($review->ad->title),30, NULL,TRUE) ?>
                                </strong>
                            </h5>
                        <? else : ?>
                            <h5 class="h4">
                                <a title="<?= $review->ad->title ?>" href="<?= Route::url('ad', ['controller'=>'ad', 'category' => $review->ad->category->seoname, 'seotitle' => $review->ad->seotitle ])?>">
                                    <strong>
                                        <?= Text::limit_chars(Text::removebbcode($review->ad->title),30, NULL,TRUE) ?>
                                    </strong>
                                </a>
                            </h5>
                        <? endif ?>
                        <p class="text-description">
                            <?= Text::bb2html($review->description,TRUE) ?>
                        </p>
                    </div>
                    <span class="clearfix"></span>
                </div>
            </div>
        </article>
    <? endforeach ?>
    <?= $pagination ?>
<? endif ?>
