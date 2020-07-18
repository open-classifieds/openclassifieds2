<?if (Auth::instance()->logged_in()):?>
    <? $fav = Model_Favorite::is_favorite(Auth::instance()->get_user(), $ad) ?>

    <div x-data="{ favorited: <?= $fav ? 'true' : 'false' ?> }">

        <a class="btn btn-sm" @click.prevent="
            fetch('<?=Route::url('oc-panel', ['controller'=>'profile', 'action'=>'favorites','id' => $ad->id_ad])?>')
                .then(response => response.text())
                .then(html => { favorited = ! favorited; })
        " :class="{ 'btn-danger': favorited, 'btn-outline-secondary': !favorited }" title="<?=__('Add to Favorites')?>" href="<?=Route::url('oc-panel', ['controller'=>'profile', 'action'=>'favorites','id' => $ad->id_ad])?>">
            <i class="fa-star" :class="{ 'fas': favorited, 'far': !favorited }"></i>
        </a>
    </div>
<?else:?>
    <div>
        <a data-toggle="modal" data-dismiss="modal" class="btn btn-sm btn-outline-secondary" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
            <i class="far fa-star"></i>
        </a>
    </div>
<?endif?>
