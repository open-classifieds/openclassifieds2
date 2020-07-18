<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <? if(core::config('advertisement.homepage_map') == 1): ?>
            <?= View::factory('home/_map') ?>
        <? endif ?>

        <? if(core::config('advertisement.ads_in_home') != 3): ?>
            <?= View::factory('home/_ads-carousel', compact('ads', 'user_location')) ?>
        <? endif ?>

        <?= View::factory('home/_categories', compact('categories', 'hidden_categories', 'user_location')) ?>

        <? if(core::config('advertisement.homepage_map') == 2): ?>
            <?= View::factory('home/_map') ?>
        <? endif ?>

        <? if(core::config('general.auto_locate') AND ! Cookie::get('user_location') AND Core::is_HTTPS()): ?>
            <?= View::factory('home/_auto-locate', compact('auto_located_locations')) ?>
        <? endif ?>
    </div>
    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
