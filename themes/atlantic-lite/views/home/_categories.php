<h2 class="h4 mb-3">
    <?=_e("Categories")?>
    <?if ($user_location) :?>
        <small><?=$user_location->translate_name()?></small>
    <?endif?>
</h2>

<div class="card-columns">
    <? foreach ($categories as $category) : ?>
        <? if ($category['id_category_parent'] == 1 and $category['id_category'] != 1 and !in_array($category['id_category'], $hidden_categories)) : ?>
            <?= View::factory('home/_category', compact('categories', 'category', 'user_location', 'hidden_categories')) ?>
        <? endif ?>
    <? endforeach ?>
</div>

<div id="modal-home-categories" class="modal fade" tabindex="-1" data-apiurl="<?= Route::url('api', ['version' => 'v1', 'format' => 'json', 'controller' => 'categories']) ?>">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
</div>
