<a title="<?= HTML::chars($subcategory['name']) ?>"
    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
    href="<?= Route::url('list', ['category' => $subcategory['seoname'], 'location' => $user_location ? $user_location->seoname : NULL]) ?>">
    <?= $subcategory['name'] ?>

    <? if (Theme::get('category_badge') != 1) : ?>
        <span class="badge badge-primary badge-pill"><?= number_format($subcategory['count']) ?></span>
    <? endif ?>
</a>
