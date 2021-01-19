<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Add money to:')?> <?= $form->object->name ?> (<?= $form->object->email ?>)
        </h2>
    </div>
</div>

<?if (Auth::instance()->get_user()->is_admin()):?>
    <div class="mt-8">
        <?= FORM::open(Route::url('oc-panel',['controller'=>'user','action'=>'add_money','id' => $form->object->id_user]), ['enctype' => 'multipart/form-data']) ?>
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <?=Form::errors()?>

                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-3 sm:col-span-2">
                            <?= FORM::label('amount', __('Amount'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'amount'])?>
                            <?= Form::input('amount', 0, [
                                'required',
                                'type' => 'number',
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <div class="flex justify-end">
                        <span class="ml-3 inline-flex rounded-md shadow-sm">
                            <?=FORM::button('submit', __('Add'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                        </span>
                    </div>
                </div>
            </div>
        <?= FORM::close() ?>
    </div>
<? endif ?>
