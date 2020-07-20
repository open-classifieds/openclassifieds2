<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Update')?> <?=Text::ucfirst(__($name))?>
        </h2>
    </div>
</div>

<div class="mt-2">
    <p class="text-sm">
        <?$controllers = Model_Access::list_controllers()?>
        <a class="font-medium text-blue-600 hover:text-blue-900" target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'index'))?>?email=<?=$form->object->email?>">
            <?=__('Orders')?>
        </a>
        <?if (array_key_exists('ad', $controllers)) :?>
            -
            <a class="font-medium text-blue-600 hover:text-blue-900" target="_blank" href="<?=Route::url('profile',array('seoname'=>$form->object->seoname))?>">
                <?=__('Ads')?>
            </a>
        <?endif?>
        <?if (core::config('advertisement.reviews')==1 OR core::config('product.reviews')==1):?>
            -
            <a class="font-medium text-blue-600 hover:text-blue-900" target="_blank" href="<?=Route::url('oc-panel',array('controller'=>'review','action'=>'index'))?>?email=<?=$form->object->email?>">
                <?=__('Reviews')?>
            </a>
        <?endif?>
    </p>
</div>

<div class="mt-8">
    <?=$form->render()?>
</div>

<?if (Auth::instance()->get_user()->is_admin()):?>
    <div class="mt-8">
        <?= FORM::open(Route::url('oc-panel',['controller'=>'user','action'=>'changepass','id'=>$form->object->id_user]), ['enctype' => 'multipart/form-data']) ?>
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                    <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
                        <div class="ml-4 mt-2">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                <?= __('Change password') ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6">
                    <?=Form::errors()?>

                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-3 sm:col-span-2">
                            <?=FORM::label('password1', __('New password'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'password1'])?>
                            <?=FORM::input('password1', '', ['class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5', 'id' => 'password1', 'required', 'type' => 'password'])?>
                        </div>
                        <div class="col-span-3 sm:col-span-2">
                            <?=FORM::label('password2', __('Repeat password'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'password2'])?>
                            <?=FORM::input('password2', '', ['class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5', 'id' => 'password2', 'required', 'type' => 'password'])?>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Type your password twice to change')?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <div class="flex justify-end">
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                        </span>
                    </div>
                </div>
            </div>
        <?= FORM::close() ?>
    </div>
<? endif ?>
