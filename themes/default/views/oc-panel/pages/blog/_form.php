<?php defined('SYSPATH') or die('No direct script access.');?>

<?= FORM::hidden($form_fields['id_post']['field_name'], $form_fields['id_post']['value'])?>
<?= FORM::hidden($form_fields['id_user']['field_name'], $form_fields['id_user']['value'])?>
<?= FORM::hidden($form_fields['seotitle']['field_name'], $form_fields['seotitle']['value'])?>

<div class="shadow sm:rounded-md sm:overflow-hidden">
    <div class="px-4 py-5 bg-white sm:p-6">
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-3 sm:col-span-2">
                <?=FORM::label($form_fields['title']['field_id'], __('Title'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => $form_fields['title']['field_id']])?>
                <?=FORM::input($form_fields['title']['field_name'], $form_fields['title']['value'], ['placeholder' => __('Title'), 'class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5', 'id' => $form_fields['title']['field_id'], 'required'])?>
            </div>
            <div class="col-span-3">
                <?=FORM::label($form_fields['description']['field_id'], __('Description'), ['class'=>'block text-sm leading-5 font-medium text-gray-700', 'for'=>$form_fields['description']['field_id']])?>
                <div class="rounded-md shadow-sm">
                    <?= Form::textarea($form_fields['description']['field_name'], $form_fields['description']['value'], [
                        'class'=>'form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        'id' => $form_fields['description']['field_id'],
                        'x-data' => '',
                        'x-init' => '$($refs.textarea).summernote(summernoteSettings())',
                        'x-ref' => 'textarea',
                        'placeholder'=>__('Description')
                    ]) ?>
                </div>
            </div>
            <div class="col-span-3">
                <div class="flex items-start">
                    <div class="absolute flex items-center h-5">
                        <?=FORM::checkbox($form_fields['status']['field_name'], 1, (bool) $form_fields['status']['value'], ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label($form_fields['status']['field_id'], __('Status'), ['class'=>'font-medium text-gray-700', 'for'=>$form_fields['status']['field_id']])?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <div class="flex justify-end">
            <span class="inline-flex rounded-md shadow-sm">
                <a href="<?= Route::url('oc-panel', ['controller' => 'blog']) ?>" role="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                    <?= __('Cancel') ?>
                </a>
            </span>
            <span class="ml-3 inline-flex rounded-md shadow-sm">
                <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
            </span>
        </div>
    </div>
</div>
