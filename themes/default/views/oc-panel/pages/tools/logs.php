<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="lg:flex lg:items-center lg:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('System Logs')?>
        </h2>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open('')?>
            <div>
                <div>
                    <div>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            <?=__('Reading log file')?> <span class="bg-red-50 text-red-600"><?= $file ?></span>
                        </p>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <?= FORM::label('site_name', __('Date'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('date', $date, [
                                    'data-date-format' => 'yyyy-mm-dd',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    'id' => 'date',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::textarea('log', $log, array(
                                    'rows' => 20,
                                    'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?= Form::close() ?>
    </div>
</div>
