<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= $field_data['label'] ?>
        </h2>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8" x-data="{ type: '<?= $field_data['type'] ?>' }">
    <div class="px-4 py-5 sm:p-6">
        <?=Form::open(Route::url('oc-panel',array('controller'=>'userfields','action'=>'update','id'=>$name)))?>
            <?=Form::errors()?>

            <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <?= FORM::label('name', __('Name'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <?= FORM::input('name', $name, [
                            'required' => TRUE,
                            'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        ]) ?>
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <?= FORM::label('type', __('Type'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <?= FORM::input('type', $field_data['type'], [
                            'x-model' => 'type',
                            'disabled' => TRUE,
                            'required' => TRUE,
                            'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        ]) ?>
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <?= FORM::label('label', __('Label'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <?= FORM::input('label', $field_data['label'], [
                            'required' => TRUE,
                            'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        ]) ?>
                    </div>
                </div>
                <div class="sm:col-span-4">
                    <?= FORM::label('tooltip', __('Tooltip'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <?= FORM::input('tooltip', $field_data['tooltip'], [
                            'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        ]) ?>
                    </div>
                </div>
                <div class="sm:col-span-4" x-show="type == 'select' || type == 'radio' || type == 'file' || type == 'file_dropbox' || type == 'file_gpicker' || type == 'checkbox_group'">
                    <?= FORM::label('Values', __('Values'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <?= FORM::input('values', is_array($field_data['values'])? implode(",", $field_data['values']): $field_data['values'], [
                            'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        ]) ?>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        <?=__('Comma separated for select.')?>
                    </p>
                </div>
                <div class="sm:col-span-6">
                    <div class="absolute flex items-center h-5">
                        <?=FORM::checkbox('required', 'on', (bool) $field_data['required'], ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label('required', __('Required'), ['class'=>'font-medium text-gray-700'])?>
                        <p class="text-gray-500"><?= __('Required field to register.') ?></p>
                    </div>
                </div>
                <div class="sm:col-span-6">
                    <div class="absolute flex items-center h-5">
                        <?=FORM::checkbox('searchable', 'on', (bool) $field_data['searchable'], ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label('searchable', __('Searchable'), ['class'=>'font-medium text-gray-700'])?>
                        <p class="text-gray-500"><?= __('Search in ads will include this field as well.') ?></p>
                    </div>
                </div>
                <div class="sm:col-span-6">
                    <div class="absolute flex items-center h-5">
                        <?=FORM::checkbox('admin_privilege', 'on', (bool) $field_data['admin_privilege'], ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label('admin_privilege', __('Admin privileged'), ['class'=>'font-medium text-gray-700'])?>
                        <p class="text-gray-500"><?= __('Can be seen and edited only by admin.') ?></p>
                    </div>
                </div>
                <div class="sm:col-span-6">
                    <div class="absolute flex items-center h-5">
                        <?=FORM::checkbox('show_profile', 'on', (bool) $field_data['show_profile'], ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label('show_profile', __('Show profile'), ['class'=>'font-medium text-gray-700'])?>
                        <p class="text-gray-500"><?= __('Can be seen in the user profile.') ?></p>
                    </div>
                </div>
                <div class="sm:col-span-6">
                    <div class="absolute flex items-center h-5">
                        <?=FORM::checkbox('show_register', 'on', (bool) $field_data['show_register'], ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label('show_register', __('Show register'), ['class'=>'font-medium text-gray-700'])?>
                        <p class="text-gray-500"><?= __('Appears when user registers.') ?></p>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a href="<?= Route::url('oc-panel', ['controller' => 'userfields']) ?>" role="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            <?= __('Cancel') ?>
                        </a>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                </div>
            </div>
        <?= Form::close() ?>
    </div>
</div>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Delete this :object', [':object' => __('field')]) ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?= __('You\'re about to delete ":object". This is permanent!', [':object' => $name]) ?>
            </p>
        </div>
        <div class="mt-5">
            <a href="<?=Route::url('oc-panel', array('controller'=> 'userfields', 'action'=>'delete','id'=>$name))?>" role="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                <?= __('Delete :object', [':object' => __('field')]) ?>
            </a>
        </div>
    </div>
</div>
