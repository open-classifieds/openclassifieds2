<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= $email->title ?>
        </h2>
    </div>
</div>

<div class="mt-8">
    <?= FORM::open(Route::url('oc-panel', ['controller' => 'emails', 'action' => 'update', 'id' => $email->id_content]), ['enctype' => 'multipart/form-data']) ?>
        <?= View::factory('oc-panel/pages/emails/_form', ['email' => $email, 'locale' => $locale, 'locales' => $locales]) ?>
    <?= FORM::close() ?>
</div>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Delete this :object', [':object' => __('email')]) ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?= __('You\'re about to delete ":object". This is permanent!', [':object' => $email->title]) ?>
            </p>
        </div>
        <div class="mt-5">
            <a href="<?=Route::url('oc-panel', ['controller' => 'emails', 'action'=>'delete', 'id' => $email->id_content])?>" role="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                <?= __('Delete :object', [':object' => __('email')]) ?>
            </a>
        </div>
    </div>
</div>
