<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="mb-4">
    <h1 class="h2"><?=_e('My Favorites')?></h1>
    <?if (Auth::instance()->get_user()->is_admin()) :?>
        <p><a target='_blank' href='https://docs.yclas.com/add-chosen-ads-favourites/'><?=_e('Read more')?></a></p>
    <?endif?>
</div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?=_e('Advertisement') ?></th>
                    <th><?=_e('Favorited') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?foreach($favorites as $favorite):?>
                    <?= View::factory('oc-panel/profile/favorites/_ad', compact('favorite')) ?>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
