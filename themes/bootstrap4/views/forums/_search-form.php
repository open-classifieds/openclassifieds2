<?php defined('SYSPATH') or die('No direct script access.');?>
<form action="<?=Route::URL('forum-home')?>" method="get" class="mt-4">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="task-table-filter" data-action="filter" data-filters="#task-table" placeholder="<?=__('Search')?>" type="search" value="<?=HTML::chars(core::get('search'))?>" name="search">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit"><?=_e('Search')?></button>
        </div>
    </div>
</form>
