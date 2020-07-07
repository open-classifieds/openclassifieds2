<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Themes') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Themes-how-to-change-a-theme']) ?>
        </div>
    </div>
</div>

<div class="mt-8">
    <div>
        <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
        <?= __('Current theme') ?></h3>
    </div>
    <div class="mt-2 grid gap-6 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">
        <div class="flex flex-col">
            <div class="flex-shrink-0 rounded-lg shadow-lg overflow-hidden">
                <img class="h-48 w-full object-cover" src="<?= Theme::get_theme_screenshot(Theme::$theme) ?>" alt="" />
            </div>
            <div class="flex-1 py-6 flex flex-col justify-between">
                <div class="flex-1">
                    <h4 class="text-xl leading-7 font-semibold text-gray-900">
                        <?=$selected['Name']?>
                    </h4>
                    <?if (Theme::has_options()):?>
                        <p class="mt-2 text-sm leading-5 font-medium text-blue-600">
                            <span class="inline-flex rounded-md shadow-sm">
                                <a href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                    <?= __('Options') ?>
                                </a>
                            </span>
                        </p>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
</div>

<? if (core::count($themes)>1):?>
    <div class="mt-8">
        <div>
            <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
                <?= __('Available themes') ?>
            </h3>
        </div>
        <div class="mt-2 grid gap-6 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">
            <? foreach ($themes as $theme=>$info):?>
                <?if(Theme::$theme!==$theme):?>
                    <div class="flex flex-col">
                        <div class="flex-shrink-0 rounded-lg shadow-lg overflow-hidden">
                            <img class="h-48 w-full object-cover" src="<?= Theme::get_theme_screenshot($theme) ?>" alt="" />
                        </div>
                        <div class="flex-1 py-6 flex flex-col justify-between">
                            <div class="flex-1 flex items-center justify-between">
                                <h4 class="text-xl leading-7 font-semibold text-gray-900">
                                    <?=$info['Name']?>
                                </h4>
                                <p class="text-sm leading-5 font-medium text-blue-600">
                                    <span class="inline-flex rounded-md shadow-sm">
                                        <a href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                            <?= __('Activate') ?>
                                        </a>
                                    </span>
                                    <?if (Core::config('appearance.allow_query_theme')=='1'):?>
                                        <span class="inline-flex rounded-md shadow-sm ml-2">
                                            <a target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                                <?=__('Preview')?>
                                            </a>
                                        </span>
                                    <?endif?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?endif?>
            <?endforeach?>
        </div>
    </div>
<? endif ?>

<? if (core::count($templates)>0):?>
    <div class="mt-8">
        <div>
            <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
                <?=__('Pro Themes')?>
            </h3>
        </div>
        <div class="mt-2 grid gap-6 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">
            <? foreach ($templates as $item):?>
                <div class="flex flex-col">
                    <div class="flex-shrink-0 rounded-lg shadow-lg overflow-hidden">
                        <?if (empty($item['screenshot'])===FALSE):?>
                            <img class="h-48 w-full object-cover" src="<?=$item['screenshot']?>">
                        <?else:?>
                            <img class="h-48 w-full object-cover" src="//www.placehold.it/300x200&text=<?=$item['titlename']?>">
                        <?endif?>
                    </div>
                    <div class="flex-1 py-6 flex flex-col justify-between">
                        <div class="flex-1 flex items-center justify-between">
                            <h4 class="text-xl leading-7 font-semibold text-gray-900">
                                <?=$item['name']?>
                            </h4>
                            <?if (empty($item['demo_url'])===FALSE):?>
                                <p class="text-sm leading-5 font-medium text-blue-600">
                                    <span class="inline-flex rounded-md shadow-sm">
                                        <a target="_blank" href="<?=$item['demo_url']?>" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                            <?= __('Preview') ?>
                                        </a>
                                    </span>
                                </p>
                            <?endif?>
                        </div>
                    </div>
                </div>
            <?endforeach?>
        </div>
    </div>
<? endif ?>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Custom themes') ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                Want to make your classified ads site unique to look more professional for your customers? You can have a theme designed specially for you!
            </p>
        </div>
        <div class="mt-3 text-sm leading-5">
            <a href="https://yclas.com/contact.html" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                Get a quote! &rarr;
            </a>
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <?=Form::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme')), array('enctype'=>'multipart/form-data'))?>
        <div class="bg-white shadow sm:rounded-lg mt-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?=__('Install theme from Zip file')?>
                </h3>
                <div class="mt-5 sm:flex sm:items-center">
                    <div class="max-w-xs w-full">
                        <?=Form::label('theme_file', __('theme_file'), ['class' => 'sr-only', 'for' => 'theme_file'])?>
                        <div class="relative rounded-md shadow-sm">
                            <input type="file" name="theme_file" id="theme_file" />
                        </div>
                    </div>
                    <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                        <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                    </span>
                </div>
            </div>
        </div>
    <?=Form::close()?>

    <?=Form::open(Route::url('oc-panel',array('controller'=>'home','action'=>'download')))?>
        <div class="bg-white shadow sm:rounded-lg mt-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?=__('Install theme from license')?>
                </h3>
                <div class="mt-5 sm:flex sm:items-center">
                    <div class="max-w-xs w-full">
                        <?=Form::label('license', __('License'), ['class' => 'sr-only', 'for' => 'license'])?>
                        <div class="relative rounded-md shadow-sm">
                            <?=Form::input('license', Core::request('license'), ['placeholder' => __('License'), 'class' => 'form-input block w-full sm:text-sm sm:leading-5', 'required'])?>
                        </div>
                    </div>
                    <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                        <?=Form::button('submit', __('Download'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                    </span>
                </div>
            </div>
        </div>
    <?=Form::close()?>
</div>
