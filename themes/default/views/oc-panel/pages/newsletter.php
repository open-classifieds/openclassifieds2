<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Send newsletter') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Content-send-a-newsletter']) ?>
        </div>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<div class="mt-8">
    <?= FORM::open(Route::url('oc-panel', ['controller' => 'newsletter'])) ?>
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-3 gap-6">
                    <?=FORM::label('to', __('To'), ['class'=>'block text-sm leading-5 font-medium text-gray-700'])?>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('send_all', 'on', FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('send_all', __('All active users.'), [
                                    'class'=>'font-medium text-gray-700',
                                ])?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-gray-100 text-gray-800">
                                    <?= $count_all_users ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('send_featured', 'on', FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('send_featured', __('Users with featured ads.'), [
                                    'class'=>'font-medium text-gray-700',
                                ])?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-gray-100 text-gray-800">
                                    <?= $count_featured ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('send_featured_expired', 'on', FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('send_featured_expired', __('Users with featured ads expired.'), [
                                    'class'=>'font-medium text-gray-700',
                                ])?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-gray-100 text-gray-800">
                                    <?= $count_featured_expired ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('send_unpub', 'on', FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('send_unpub', __('Users without published ads.'), [
                                    'class'=>'font-medium text-gray-700',
                                ])?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-gray-100 text-gray-800">
                                    <?= $count_unpub ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('send_logged', 'on', FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('send_logged', __('Users not logged last 3 months'), [
                                    'class'=>'font-medium text-gray-700',
                                ])?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-gray-100 text-gray-800">
                                    <?= $count_logged ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('send_spam', 'on', FALSE, [
                                    'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'
                                ])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('send_spam', __('Users marked a spam'), [
                                    'class'=>'font-medium text-gray-700',
                                ])?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-gray-100 text-gray-800">
                                    <?= $count_spam ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?=FORM::label('from', __('From'), ['class' => 'block text-sm font-medium leading-5 text-gray-700'])?>
                        <?=FORM::input('from', Auth::instance()->get_user()->name, [
                            'class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            'required',
                        ])?>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?=FORM::label('from_email', __('From Email'), ['class' => 'block text-sm font-medium leading-5 text-gray-700'])?>
                        <?=FORM::input('from_email', Auth::instance()->get_user()->email, [
                            'class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            'type' => 'email',
                            'required',
                        ])?>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?=FORM::label('subject', __('Subject'), ['class' => 'block text-sm font-medium leading-5 text-gray-700'])?>
                        <?=FORM::input('subject', '', [
                            'class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            'required',
                        ])?>
                    </div>
                    <div class="col-span-3">
                        <?=FORM::label('description', __('Message'), ['class'=>'block text-sm leading-5 font-medium text-gray-700'])?>
                        <div class="rounded-md shadow-sm">
                            <?=FORM::textarea('description', NULL, [
                                'x-data' => '',
                                'x-init' => '$($refs.textarea).summernote(summernoteSettings())',
                                'x-ref' => 'textarea',
                                'class'=>'form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'rows' => 15,
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <div class="flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a href="<?= Route::url('oc-panel', ['controller' => 'settings']) ?>" role="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            <?= __('Cancel') ?>
                        </a>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Send'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                </div>
            </div>
        </div>
    <?= FORM::close() ?>
</div>
