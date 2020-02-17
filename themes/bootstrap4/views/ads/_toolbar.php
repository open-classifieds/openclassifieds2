<div class="btn-toolbar justify-content-between mb-3" role="toolbar">
    <div class="btn-group" role="group">
        <? if(core::config('general.auto_locate')): ?>
            <button
                class="btn btn-sm btn-secondary <?=core::request('userpos') == 1 ? 'active' : NULL?>"
                id="myLocationBtn"
                type="button"
                data-toggle="modal"
                data-target="#myLocation"
                data-marker-title="<?=__('My Location')?>"
                data-marker-error="<?=__('Cannot determine address at this location.')?>"
                data-href="?<?=http_build_query(['userpos' => 1] + Request::current()->query())?>">
                <i class="fas fa-map-marker-alt"></i> <?=sprintf(__('%s from you'), i18n::format_measurement(Core::cookie('mydistance', Core::config('advertisement.auto_locate_distance', 2))))?>
            </button>
        <? endif ?>

        <?if (core::config('advertisement.map')==1):?>
            <a href="#"
                class="btn btn-secondary btn-sm"
                data-toggle="modal" data-target="#listingMap">
                <span class="glyphicon glyphicon-globe"></span> <?=_e('Map')?>
            </a>
        <?endif?>
    </div>

    <div class="btn-group" role="group">
        <div class="btn-group">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?=_e('Show').' '.HTML::chars(core::request('items_per_page')).' '._e('items per page')?>
            </button>
            <div class="dropdown-menu dropdown-menu-right" role="menu" id="show-list">
                <a class="dropdown-item" href="?<?=http_build_query(['items_per_page' => '5'] + Request::current()->query())?>">  5 <?=_e('per page')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['items_per_page' => '10'] + Request::current()->query())?>"> 10 <?=_e('per page')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['items_per_page' => '20'] + Request::current()->query())?>"> 20 <?=_e('per page')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['items_per_page' => '50'] + Request::current()->query())?>"> 50 <?=_e('per page')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['items_per_page' => '100'] + Request::current()->query())?>">100 <?=_e('per page')?></a>
            </div>
        </div>
        <div class="btn-group">
            <button type="button" id="sort" data-sort="<?=HTML::chars(core::request('sort',core::config('advertisement.sort_by')))?>" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="fas fa-sort"></span> <?=_e('Sort')?>
            </button>
            <div class="dropdown-menu dropdown-menu-right" role="menu" id="sort-list">
                <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'title-asc'] + Request::current()->query())?>"><?=_e('Name (A-Z)')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'title-desc'] + Request::current()->query())?>"><?=_e('Name (Z-A)')?></a>

                <? if(core::config('advertisement.price') != FALSE) : ?>
                    <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'price-asc'] + Request::current()->query())?>"><?=_e('Price (Low)')?></a>
                    <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'price-desc'] + Request::current()->query())?>"><?=_e('Price (High)')?></a>
                <? endif ?>

                <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'featured'] + Request::current()->query())?>"><?=_e('Featured')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'favorited'] + Request::current()->query())?>"><?=_e('Favorited')?></a>

                <? if(core::config('general.auto_locate')) : ?>
                    <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'distance'] + Request::current()->query())?>" id="sort-distance"><?=_e('Distance')?></a>
                <? endif ?>

                <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'published-desc'] + Request::current()->query())?>"><?=_e('Newest')?></a>
                <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'published-asc'] + Request::current()->query())?>"><?=_e('Oldest')?></a>
            </div>
        </div>
    </div>
</div>
