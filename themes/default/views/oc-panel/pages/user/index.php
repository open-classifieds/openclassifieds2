<?php defined('SYSPATH') or die('No direct script access.');?>

<?= View::factory('oc-panel/crud/indexajax', [
    'name' => $name,
    'controller' => $controller,
    'route' => $route,
    'extra_info_view' => $extra_info_view,
    'filters' => $filters,
    'fields' => $fields,
    'elements' => $elements,
]) ?>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('User custom fields') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('Manage your custom fields for your users.') ?>
                </p>
            </div>
            <div class="mt-3 text-sm leading-5">
                <a href="<?= Route::url('oc-panel', ['controller' => 'userfields']) ?>" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    <?= __('Go to user custom fields settings') ?> &rarr;
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('User roles') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('Manage different roles to your users, from translation and content creation to moderation and website administration.') ?>
                </p>
            </div>
            <div class="mt-3 text-sm leading-5">
                <a href="<?= Route::url('oc-panel', ['controller' => 'role']) ?>" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    <?= __('Go to user roles settings') ?> &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
