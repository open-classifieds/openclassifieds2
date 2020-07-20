<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <?if ($category!==NULL):?>
                <h1 class="h2"><?=$category->translate_name()?></h1>
            <?elseif ($location!==NULL):?>
                <h1 class="h2"><?=$location->translate_name()?></h1>
            <?else:?>
                <h1 class="h2"><?=_e('Listings')?></h1>
            <?endif?>

            <?if (Controller::$image!==NULL AND Theme::get('hide_description_icon')!=1):?>
                <img src="<?=Controller::$image?>" class="img-responsive" alt="<?=($category!==NULL) ? HTML::chars($category->translate_name()) : (($location!==NULL AND $category===NULL) ? HTML::chars($location->translate_name()) : NULL)?>">
            <?endif?>

            <?if ($category!==NULL):?>
                <p><?= $category->translate_description() ?></p>
            <?elseif ($location!==NULL):?>
                <p><?= $location->translate_description() ?></p>
            <?endif?>

            <? if (Core::config('advertisement.only_admin_post') != 1
                AND (core::config('advertisement.parent_category') == 1
                    OR (core::config('advertisement.parent_category') != 1
                        AND $category !== NULL
                        AND ! $category->is_parent()))):?>
                <a class="btn btn-primary" title="<?=__('New Advertisement')?>"
                    href="<?=Route::url('post_new')?>?category=<?=($category!==NULL)?$category->seoname:''?>&location=<?=($location!==NULL)?$location->seoname:''?>">
                    <i class="fas fa-pencil-alt"></i>
                    <?=_e('Publish new advertisement')?>
                </a>
            <?endif?>
        </div>

        <?= View::factory('ads/_carousel', ['slider_ads' => $featured != NULL ? $featured : $ads]) ?>

        <? if(core::count($ads)): ?>
            <?= View::factory('ads/_toolbar') ?>

            <? foreach($ads as $ad): ?>
                <?= View::factory('ads/_ad', compact('ad', 'user')) ?>
            <? endforeach ?>

            <?= $pagination ?>

        <? elseif (core::count($ads) == 0) : ?>
            <?if(core::config('general.auto_locate') AND core::request('userpos') == 1):?>
                <div class="btn-toolbar justify-content-between mb-3" role="toolbar">
                    <div class="btn-group" role="group">
                        <button
                            class="btn btn-sm btn-default <?=core::request('userpos') == 1 ? 'active' : NULL?>"
                            id="myLocationBtn"
                            type="button"
                            data-toggle="modal"
                            data-target="#myLocation"
                            data-href="?<?=http_build_query(['userpos' => 1] + Request::current()->query())?>">
                            <i class="fas fa-map-marker-alt"></i> <?=sprintf(__('%s from you'), i18n::format_measurement(Core::config('advertisement.auto_locate_distance', 1)))?>
                        </button>
                    </div>
                </div>
            <?endif?>

            <div class="jumbotron text-center">
                <p class="lead"><?=_e('We do not have any advertisements in this category')?></p>
            </div>
        <?endif?>

        <?if(core::config('general.auto_locate')):?>
            <?=View::factory('ads/_my-location-modal')?>

            <?if (core::config('advertisement.map')==1):?>
                <?=View::factory('ads/_map-modal', compact('ads'))?>
            <?endif?>
        <?endif?>

    </div>
    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
