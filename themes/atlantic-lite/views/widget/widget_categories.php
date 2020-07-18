<div class="card card-categories mb-3">
    <? if ($widget->categories_title != '') : ?>
        <div class="card-header">
            <span class="h6"><?= $widget->categories_title ?></span>
        </div>
    <? endif ?>

    <? if ($widget->cat_breadcrumb !== NULL) : ?>
        <div class="card-body py-3">
            <h6 class="mb-0">
                <? if ($widget->cat_breadcrumb['id_parent'] != 0) : ?>
                    <a href="<?= Route::url('list', ['category' => $widget->cat_breadcrumb['parent_seoname'], 'location' => $widget->loc_seoname]) ?>"
                        title="<?= HTML::chars($widget->cat_breadcrumb['parent_name']) ?>">
                        <?= $widget->cat_breadcrumb['parent_name'] ?>
                    </a>
                    - <?= $widget->cat_breadcrumb['name'] ?>
                <? else : ?>
                    <a href="<?= Route::url('list', ['category' => $widget->cat_breadcrumb['parent_seoname'], 'location' => $widget->loc_seoname]) ?>"
                        title="<?= HTML::chars($widget->cat_breadcrumb['parent_name']) ?>">
                        <?= _e('Home') ?>
                    </a>
                    - <?= $widget->cat_breadcrumb['name'] ?>
                <? endif ?>
            </h6>
        </div>
    <? endif ?>
    <div class="list-group list-group-flush">
        <? foreach ($widget->cat_items as $category) : ?>
            <a href="<?= Route::url('list', ['category' => $category->seoname, 'location' => $widget->loc_seoname]) ?>"
                class="list-group-item list-group-item-action d-flex justify-content-between"
                title="<?= HTML::chars($category->name) ?>">
                <div>
                    <span><?= $category->name ?></span>
                </div>
                <div>
                    <i class="fas fa-angle-right"></i>
                </div>
            </a>
        <? endforeach ?>
    </div>
</div>
