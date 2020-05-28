<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="py-4">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="py-8 bg-white">
                <div class="max-w-xl mx-auto px-4 sm:px-6 lg:max-w-screen-xl lg:px-8">
                    <div class="grid grid-cols-1 lg:grid lg:grid-cols-3 lg:gap-4">
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'general'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">General</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'advertisement'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Advertisements</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'payment'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Payments</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'media'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Media settings</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel/settings',array('controller'=>'email'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Email</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'translations'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Translations</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'category'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Categories</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'location'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Locations</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'fields'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Custom fields</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'userfields'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">User custom fields</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'role'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">User roles</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',['controller'=>'update'])?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Updates</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'coupon'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Coupons</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'emails'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Transactional emails</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'newsletter'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Newsletters</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'cmsimages'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Media</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'map'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Interactive Map</h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis.
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'social'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600">Social Auth</h5>
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
