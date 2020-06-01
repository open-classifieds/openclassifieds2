<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="py-4">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="py-8 bg-white">
                <div class="max-w-xl mx-auto px-4 sm:px-6 lg:max-w-screen-xl lg:px-8">
                    <div class="grid grid-cols-1 lg:grid lg:grid-cols-3 lg:gap-4">
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'general'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('General') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'advertisement'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Advertisements') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'payment'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Payments') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'media'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Media settings') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'email'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Email') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'translations'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Translations') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'category'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Categories') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'location'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Locations') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'fields'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Custom fields') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'userfields'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('User custom fields') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'role'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('User roles') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'update'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Updates') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'coupon'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Coupons') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'emails'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Transactional emails') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'newsletter'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Newsletters') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'cmsimages'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Media') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'map'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Interactive Map') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'social'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">
                                <?= __('Social Auth') ?> <?= View::factory('oc-panel/components/pro-badge') ?>
                            </h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
