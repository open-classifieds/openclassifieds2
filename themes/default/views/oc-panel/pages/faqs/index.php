<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Faqs') ?>
        </h2>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="shadow-sm rounded-md">
            <?= FORM::open(Route::url('oc-panel', ['controller'=>'faqs']), ['method' => 'GET', 'x-data' => ''])?>
                <?= FORM::select('locale', $locales, $locale, ['x-on:change' => '$el.submit()', 'class' => 'block form-select w-24 py-2 px-3 py-0 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5'])?>
            <?= FORM::close()?>
        </span>
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?= Route::url('oc-panel', ['controller'=>'faqs','action'=>'create']) ?>?locale=<?= $locale ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('New faq') ?>
            </a>
        </span>
    </div>
</div>

<?if (Core::count($faqs) > 0):?>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-8">
        <ul class='sortable' id="ol_1" data-id="1">
            <? foreach ($faqs as $key => $faq): ?>
                <? $last_item = $key === count($faqs) - 1 ?>
                <?= View::factory('oc-panel/pages/faqs/_faq', ['faq' => $faq, 'last_item' => $last_item]) ?>
            <? endforeach ?>
        </ul>
    </div>

    <div class="flex justify-end mt-4">
        <span id="ajax_result" data-url="<?=Route::url('oc-panel', ['controller'=>'faqs','action'=>'saveorder'])?>"></span>
    </div>
<?else:?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Create all new :object from original', [':object' => __('faqs')]) ?>
            </h3>
            <div class="mt-5">
                <a href="<?=Route::url('oc-panel', array('controller'=>'faqs','action'=>'duplicate'))?>?to_locale=<?=$locale?>" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <?= __('Create :object', [':object' => __('faqs')]) ?>
                </a>
            </div>
        </div>
    </div>
<?endif?>
