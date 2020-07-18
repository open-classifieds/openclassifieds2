<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <?if ($ad->status != Model_Ad::STATUS_PUBLISHED AND $permission === FALSE AND ($ad->id_user != $user)):?>
            <div class="jumbotron text-center">
                <p class="lead"><?=_e('This advertisement doesn´t exist, or is not yet published!')?></p>
            </div>
        <?else:?>
            <? if((Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == 10) OR
                (Auth::instance()->logged_in() AND $ad->user->id_user == Auth::instance()->get_user()->id_user)):?>
                <? if((core::config('payment.pay_to_go_on_top') > 0
                            AND core::config('payment.to_top') != FALSE )
                            OR (core::config('payment.pay_to_go_on_feature') > 0
                            AND core::config('payment.to_featured') != FALSE)): ?>
                    <?= View::factory('ads/_promotion', compact('ad')) ?>
                <? endif ?>
            <?endif?>

            <?= Form::errors() ?>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <div class="mb-2">
                        <?if (core::config('advertisement.location') AND $ad->id_location != 1 AND $ad->location->loaded()):?>
                            <strong class="text-success d-inline-block">
                                <?=$ad->location->translate_name()?>
                            </strong>
                        <?endif?>
                    </div>

                    <div class="mb-2">
                        <?= View::factory('ads/_favorite-button', compact('ad')) ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <h1 class="h2 mb-0">
                        <?= $ad->title ?>
                    </h1>

                    <?if (Core::config('advertisement.reviews')==1):?>
                        <div class="mb-1 text-info">
                            <a class="badge badge-success" href="<?= Route::url('ad-review', ['seotitle' => $ad->seotitle]) ?>" >
                                <?if ($ad->rate !== NULL):?>
                                    <?for ($i=0; $i < round($ad->rate,1); $i++):?>
                                        <i class="fa fa-star"></i>
                                    <?endfor?>
                                <?else:?>
                                    <?=_e('Leave a review')?>
                                <?endif?>
                            </a>
                        </div>
                    <?endif?>

                    <?if ($ad->price > 0) : ?>
                        <div class="mb-1 text-info">
                            <h2 class="h4 mb-0">
                                <span class="price-curry"><?= i18n::money_format($ad->price, $ad->currency()) ?></span>
                            </h2>
                        </div>
                    <?elseif ($ad->price==0 AND core::config('advertisement.free')==1):?>
                        <div class="mb-1 text-info">
                            <h2 class="h4 mb-0">
                                <?= _e('Free') ?>
                            </h2>
                        </div>
                    <?endif?>
                </div>

                <div class="mb-1 text-muted">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <?= Date::format($ad->published, core::config('general.date_format'))?>
                        </li>

                        <li class="list-inline-item">
                            <a href="<?=Route::url('profile',  ['seoname'=>$ad->user->seoname])?>">
                                <?=_e('by')?> <?= $ad->user->name ?>
                            </a>
                        </li>

                        <?if(core::config('advertisement.count_visits')==1):?>
                            <li class="list-inline-item">
                                <?=$hits?> <?=_e('hits')?>
                            </li>
                        <?endif?>
                    </ul>
                </div>
            </div>

            <? if($images = $ad->get_images()): ?>
                <div id="gallery" class="mb-3">
                    <div class="row">
                        <?foreach ($images as $path => $value):?>
                            <?= View::factory('ads/_image', compact('path', 'value', 'ad')) ?>
                        <?endforeach?>
                    </div>
                </div>
            <? endif ?>

            <?= View::factory('ads/_custom-fields', compact('cf_list')) ?>

            <div class="mb-3">
                <? if(core::config('advertisement.description') != FALSE): ?>
                    <p><?= Text::bb2html($ad->description, TRUE) ?></p>
                <? endif ?>

                <?if (Valid::url($ad->website)):?>
                    <hr>

                    <p>
                        <a href="<?=$ad->website?>" rel="nofollow" target="_blank"><?=$ad->website?></a>
                    </p>
                <?endif?>

                <?if (core::config('advertisement.address')):?>
                    <hr>

                    <p><?=$ad->address?></p>
                <?endif?>
            </div>

            <?= View::factory('ads/_btn-group', compact('ad')) ?>

            <? if(core::config('advertisement.sharing') == 1) : ?>
                <hr>
                <?= View::factory('share') ?>
            <? endif ?>

            <? if($ad->qr()) : ?>
                <hr>
                <?= $ad->qr() ?>
            <? endif ?>

            <?= $ad->map() ?>

            <? if($ad->comments()) : ?>
                <hr>
                <?= $ad->comments() ?>
            <? endif ?>

            <? if(core::config('advertisement.report') == 1): ?>
                <hr>
                <?= $ad->flagad() ?>
            <?endif?>

            <?= $ad->structured_data() ?>

            <?= View::factory('ads/_blueimp-gallery') ?>

            <?= $ad->related() ?>
        <?endif?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
