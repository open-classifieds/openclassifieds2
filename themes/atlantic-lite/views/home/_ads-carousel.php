<div class="mb-5">
    <h2 class="h4 mb-3">
        <? if (core::config('advertisement.ads_in_home') == 0): ?>
            <?= _e('Latest Ads') ?>
        <? elseif (core::config('advertisement.ads_in_home') == 1 OR core::config('advertisement.ads_in_home') == 4): ?>
            <?= _e('Featured Ads') ?>
        <? elseif (core::config('advertisement.ads_in_home') == 2): ?>
            <?= _e('Popular Ads last month') ?>
        <? endif ?>

        <? if ($user_location) : ?>
            <small><?=$user_location->name?></small>
        <? endif ?>
    </h2>

    <div id="home-ads-carousel" class="carousel slide" data-ride="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="card-group">
                    <? $i = 0; foreach ($ads as $ad) : ?>
                        <?= View::factory('home/_ad', compact('ad')) ?>
                        <? $i++; if ($i % 4 == 0) : ?>
                            </div></div><div class="carousel-item"><div class="card-group">
                        <? endif ?>
                    <? endforeach ?>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#home-ads-carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only"><?= _('Previous') ?></span>
        </a>
        <a class="carousel-control-next" href="#home-ads-carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only"><?= _('Next') ?></span>
        </a>
    </div>
</div>
