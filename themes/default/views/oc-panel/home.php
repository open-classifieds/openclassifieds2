<?php defined('SYSPATH') or die('No direct script access.');?>

<?=View::factory('pwa/_alert')?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Dashboard') ?>
        </h2>
    </div>
</div>

<? if (Auth::instance()->get_user()->is_admin() AND Core::config('license.number') == NULL) : ?>
    <form action="<?= Route::url('oc-panel',array('controller'=>'home','action'=> 'dowload'))?>" method="post">
        <div class="bg-white shadow sm:rounded-lg mt-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Lite version
                </h3>
                <div class="mt-5">
                    <div class="rounded-md bg-gray-50 px-6 py-5 sm:flex sm:items-start sm:justify-between">
                        <div>
                            <div class="text-sm leading-5 font-medium text-gray-900">
                                <?=__('You are using our Lite version')?>
                            </div>
                            <div class="mt-1 text-sm leading-5 text-gray-600 sm:flex sm:items-center">
                                <?=__('To enable all the features and to get support upgrade to Pro')?>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-6 sm:flex-shrink-0">
                            <span class="inline-flex rounded-md shadow-sm">
                                <a href="https://yclas.com/self-hosted.html" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                    <?= __('Buy Pro') ?>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <label for="license" class="block text-sm font-medium leading-5 text-gray-700">
                        <?= __('Enter your license to activate Pro') ?>
                    </label>
                </div>
                <div class="mt-1 sm:flex sm:items-center">
                    <div class="max-w-xs w-full">
                        <div class="relative rounded-md shadow-sm">
                            <input name="license" id="license" class="form-input block w-full sm:text-sm sm:leading-5" placeholder="xxx-xxxx" required />
                        </div>
                    </div>
                    <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5">
                            <?= __('Check') ?>
                        </button>
                    </span>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    <?=sprintf(__('License will be activated in %s domain. Once activated, your license cannot be changed to another domain.'), parse_url(URL::base(), PHP_URL_HOST))?>
                </p>
            </div>
        </div>
    </form>
<? endif ?>

<?if (core::cookie('intro_panel')!=1):?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?=__('Welcome')?> <?=Auth::instance()->get_user()->name?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?=__('Thanks for using Yclas.')?>
                    <?=__('Your installation version is')?>
                    <a class="underline text-blue-600" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1">
                        <?=core::VERSION?>
                    </a>
                </p>
            </div>
            <div class="mt-3 text-sm leading-5 flex inline-flex">
                <a href="<?=Route::url('oc-panel',array('controller'=>'pages','action'=>'index'))?>" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    <?=__('Create or edit content')?> &rarr;
                </a>
                <a href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>" class="ml-6 font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    <?= __('Change the theme options') ?> &rarr;
                </a>
                <a href="<?=Route::url('oc-panel/settings',array('controller'=>'general'))?>" class="ml-6 font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                    <?= __('Edit the settings of this website') ?> &rarr;
                </a>
            </div>
        </div>
    </div>
<?endif?>

<div class="mt-8">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
    <?= __('Today') ?>
    </h3>
    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                    <?=__('New visits')?>
                    </dt>
                    <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                    <?=$visits_today?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                    <?=__('New users')?>
                    </dt>
                    <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                    <?=$users_today?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                    <?=__('New advertisements')?>
                    </dt>
                    <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                    <?=$ads_today?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl>
                    <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                    <?=__('New orders')?>
                    </dt>
                    <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                    <?=$orders_today?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
