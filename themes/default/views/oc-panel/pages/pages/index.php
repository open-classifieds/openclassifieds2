<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Pages') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Content-Add-pages']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="shadow-sm rounded-md">
            <?= FORM::open(Route::url('oc-panel', ['controller'=>'pages']), ['method' => 'GET', 'x-data' => ''])?>
                <?= FORM::select('locale', $locales, $locale, ['x-on:change' => '$el.submit()', 'class' => 'block form-select w-24 py-2 px-3 py-0 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5'])?>
            <?= FORM::close()?>
        </span>
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?= Route::url('oc-panel', ['controller'=>'pages','action'=>'create']) ?>?locale=<?= $locale ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('New page') ?>
            </a>
        </span>
    </div>
</div>

<?if (Core::count($pages) > 0):?>
    <div class="flex flex-col mt-8">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Title') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Seo title') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Status') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <? foreach ($pages as $key => $page): ?>
                            <? $last_item = $key === count($pages) - 1 ?>
                            <?= View::factory('oc-panel/pages/pages/_page', ['page' => $page, 'last_item' => $last_item]) ?>
                        <? endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?else:?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Create all new :object from original', [':object' => __('pages')]) ?>
            </h3>
            <div class="mt-5">
                <a href="<?=Route::url('oc-panel', array('controller'=>'pages','action'=>'duplicate'))?>?to_locale=<?=$locale?>" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <?= __('Create :object', [':object' => __('pages')]) ?>
                </a>
            </div>
        </div>
    </div>
<?endif?>
