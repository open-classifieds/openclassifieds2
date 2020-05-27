<?php defined('SYSPATH') or die('No direct script access.');?>

<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= $forum->name ?>
        </h2>
    </div>
</div>

<div class="mt-8">
    <?= FORM::open(Route::url('oc-panel', ['controller' => 'forum', 'action' => 'update', 'id' => $forum->id_forum])) ?>
        <?= View::factory('oc-panel/pages/forum/_form', ['forum' => $forum, 'parent_forums' => $parent_forums]) ?>
    <?= FORM::close() ?>
</div>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Delete this :object', [':object' => __('forum')]) ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?= __('You\'re about to delete ":object". This is permanent!', [':object' => $forum->name]) ?>
            </p>
        </div>
        <div class="mt-5">
            <a href="<?=Route::url('oc-panel', ['controller' => 'forum', 'action'=>'delete', 'id' => $forum->id_forum])?>" role="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                <?= __('Delete :object', [':object' => __('forum')]) ?>
            </a>
        </div>
    </div>
</div>
