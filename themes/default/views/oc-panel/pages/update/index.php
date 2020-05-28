<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Updates') ?>
        </h2>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1" title="<?=__('New')?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('Check for updates') ?>
            </a>
        </span>
    </div>
</div>

<? if ($latest_version != core::VERSION): ?>
    <div class="rounded-md bg-yellow-50 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm leading-5 font-medium text-yellow-800">
                    <?=__('A new version is available')?>
                </h3>
                <div class="mt-2 text-sm leading-5 text-yellow-700">
                    <p>
                        <?=__('You are not using latest version, please update.')?>
                    </p>
                </div>
                <div class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">
                        <a href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'confirm'))?>" class="px-2 py-1.5 rounded-md text-sm leading-5 font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:bg-yellow-100 transition ease-in-out duration-150">
                            <?=__('Update')?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif?>

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

<div class="bg-gray-50 sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?=__('Hash Key')?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?=__('Your Hash Key for this installation is')?>
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-gray-100 text-gray-800">
                    <?=core::config('auth.hash_key')?>
                </span>
            </p>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Version') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Name') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Changelog') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Release notes') ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?foreach ($versions as $version=>$values):?>
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">
                                        <?=$version?>
                                        <? if ($version == $latest_version): ?>
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-blue-100 text-blue-800">
                                                <?= __('Latest') ?>
                                            </span>
                                        <? endif ?>
                                        <? if ($version == core::VERSION): ?>
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium leading-5 bg-green-100 text-green-800">
                                                <?= __('Current') ?>
                                            </span>
                                        <? endif ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">
                                        <?=$values['codename']?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">
                                        <a target="_blank" href="<?=$values['changelog']?>"><?=__('Changelog')?></a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">
                                        <a target="_blank" href="<?=$values['blog']?>"><?=__('Release Notes')?></a>
                                    </div>
                                </td>
                            </tr>
                        <? endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
