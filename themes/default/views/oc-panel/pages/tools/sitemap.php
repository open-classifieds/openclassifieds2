<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="lg:flex lg:items-center lg:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Sitemap') ?>
        </h2>
        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Extras-create-site-map']) ?>
        </div>
    </div>
</div>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Your sitemap XML to submit to engines') ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-900">
            <div class="relative rounded-md shadow-sm">
                <div class="block form-input w-full sm:text-sm sm:leading-5">
                    <? if (Core::is_cloud()) : ?>
                        <?=Route::url('sitemap')?>
                    <? else : ?>
                        <?=core::config('general.base_url')?><?=(file_exists(DOCROOT.'sitemap-index.xml'))? 'sitemap-index.xml':'sitemap.xml.gz'?>
                    <? endif ?>
                </p>
            </div>
        </div>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?=__('Last time generated')?>
                <? if (Core::is_cloud()) : ?>
                    <?=Date::unix2mysql((Core::config('sitemap.last_generated')!='')?Core::config('sitemap.last_generated'):time())?>
                <? else : ?>
                    <?=Date::unix2mysql(Sitemap::last_generated_time())?>
                <? endif ?>
            </p>
        </div>
        <div class="mt-5">
            <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>?force=1" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                <?=__('Generate')?>
            </a>
        </div>
    </div>
</div>
