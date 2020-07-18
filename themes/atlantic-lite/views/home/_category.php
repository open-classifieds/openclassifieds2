<div class="card mb-3">
    <? $icon_src = new Model_Category($category['id_category']); $icon_src = $icon_src->get_icon(); if (($icon_src) !== FALSE):?>
        <a title="<?=HTML::chars((strip_tags($category['description']) !== '') ? strip_tags($category['description']) : $category['name'])?>"
            href="<?=Route::url('list', ['category' => $category['seoname'], 'location' => $user_location ? $user_location->seoname : NULL])?>">
            <img class="card-img-top" src="<?=Core::imagefly($icon_src, 300, 200)?>" alt="<?=HTML::chars($category['name'])?>">
        </a>
    <?endif?>
    <div class="card-header">
        <a href="<?= Route::url('list', ['category' => $category['seoname'], 'location' => $user_location ? $user_location->seoname : NULL]) ?>"
            class="text-dark"
            title="<?= HTML::chars((strip_tags($category['description']) !== '') ? strip_tags($category['description']) : $category['name']) ?>">
            <?= $category['name'] ?>
        </a>
    </div>
    <div class="list-group list-group-flush">
        <? $ci = 0; foreach ($categories as $subcategory) : ?>
            <? if ($subcategory['id_category_parent'] == $category['id_category'] and !in_array($subcategory['id_category'], $hidden_categories)) : ?>
                <? if ($ci < 2) : ?>
                    <?= View::factory('home/_subcategory', compact('subcategory', 'user_location')) ?>
                <? endif ?>
                <? $ci++; if ($ci == 2) : ?>
                    <a href="#" role="button"
                        class="show-all-categories list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                        data-cat-id="<?= $category['id_category'] ?>">
                        <?= _e('See all categories') ?> <span class="fas fa-angle-right pull-right"></span>
                    </a>
                <? endif ?>
            <? endif ?>
        <? endforeach ?>
    </div>
</div>
