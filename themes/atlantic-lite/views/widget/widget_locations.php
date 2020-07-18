<div class="card card-locations mb-3">
    <? if ($widget->locations_title != '') : ?>
        <div class="card-header">
            <span class="h6"><?= $widget->locations_title ?></span>
        </div>
    <? endif ?>

    <? if ($widget->loc_breadcrumb !== NULL) : ?>
        <div class="card-body py-3">
            <h6 class="mb-0">
                <? if ($widget->loc_breadcrumb['id_parent'] != 0) : ?>
                    <a href="<?= Route::url('list', ['location' => $widget->loc_breadcrumb['parent_seoname'], 'category' => $widget->cat_seoname]) ?>"
                        title="<?= HTML::chars($widget->loc_breadcrumb['parent_name']) ?>">
                        <?= $widget->loc_breadcrumb['parent_name'] ?>
                    </a>
                    - <?= $widget->loc_breadcrumb['name'] ?>
                <? else : ?>
                    <a href="<?= Route::url('list', ['location' => $widget->loc_breadcrumb['parent_seoname'], 'category' => $widget->cat_seoname]) ?>"
                        title="<?= HTML::chars($widget->loc_breadcrumb['parent_name']) ?>">
                        <?= _e('Home') ?>
                    </a>
                    - <?= $widget->loc_breadcrumb['name'] ?>
                <? endif ?>
            </h6>
        </div>
    <? endif ?>
    <div class="list-group list-group-flush">
        <? foreach($widget->loc_items as $location): ?>
            <a href="<?= Route::url('list', ['location' => $location->seoname, 'category' => $widget->cat_seoname]) ?>"
                class="list-group-item list-group-item-action d-flex justify-content-between"
                title="<?= HTML::chars($location->name) ?>">
                <div>
                    <span><?= $location->translate_name() ?></span>
                </div>
                <div>
                    <i class="fas fa-angle-right"></i>
                </div>
            </a>
        <? endforeach ?>
    </div>
</div>
