<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Update')?> <?=$latest_version?>
        </h2>
    </div>
</div>

<div class="bg-gray-50 sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?=__('Your installation version is')?>
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-blue-100 text-blue-800">
                <?=core::VERSION?>
            </span>
        </h3>
    </div>
</div>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?if ($can_update == FALSE):?>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Not possible to auto update') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('You have an old version and automatic update is not possible. Please read the release notes and the manual update instructions.') ?>
                </p>
            </div>
            <div class="mt-5">
                <a target="_blank" href="<?=$version['blog']?>" role="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    <?=__('Release Notes')?> <?=$latest_version?>
                </a>
            </div>
        <?else:?>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Read carefully') ?>!
            </h3>
            <div class="mt-2 p-4 max-w-xl text-sm leading-5 rounded-md bg-yellow-50 text-yellow-700 leading-normal">
                <ul class="ml-4 list-disc">
                    <li><?=__('Backup all your files and database')?>. <a target="_blank" href="https://docs.yclas.com/backup-classifieds-site/"><?=__('Read more')?></a></li>
                    <li><?=__('This process can take few minutes DO NOT interrupt it')?></li>
                    <li><?=__('If you have doubts check the release notes for this version')?>. <a target="_blank" href="<?=$version['changelog']?>"><?=__('Release Notes')?> <?=$latest_version?></a></li>
                    <li><?=__('You are responsible for any damages or down time at your site')?></li>
                </ul>
            </div>
            <div class="mt-5">
                <a href="<?= Route::url('oc-panel', ['controller'=>'update','action'=>'latest']) ?>" onclick="return confirm('<?=__('This process can take few minutes DO NOT interrupt it')?>');" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    <?=__('Proceed with Update')?>
                </a>
            </div>
        <?endif?>
    </div>
</div>
