<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Update coupon') ?>
        </h2>
    </div>
</div>


<div class="mt-8" x-data="{ fixed: <?= $coupon->discount_amount != 0 ? 'true' : 'false' ?> }">
    <?= FORM::open(Route::url('oc-panel', array('controller'=> 'coupon', 'action'=>'update','id'=>$coupon->id_coupon))) ?>
        <input type="hidden" name="id_coupon" value="">

        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-3 sm:col-span-2">
                        <fieldset>
                            <legend class="text-base leading-6 font-medium text-gray-900"><?= __('Type') ?></legend>
                            <div class="mt-4">
                                <div class="flex items-center">
                                    <?= Form::radio('type', true, false, [
                                        'class' => 'form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out',
                                        'x-model' => 'fixed'
                                    ]) ?>
                                    <label class="ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700">
                                            <?= __('Fixed discount') ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center">
                                    <?= Form::radio('type', false, false, [
                                        'class' => 'form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out',
                                        'x-model' => 'fixed'
                                    ]) ?>
                                    <label class="ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700">
                                            <?= __('Percentage discount') ?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?=FORM::label('name', __('Coupon name'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'name'])?>
                        <?=FORM::input('name', $coupon->name, ['class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5'])?>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?= FORM::label('id_product', __('Product'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?
                                $product_values = ['' => __('Any')];
                                foreach ($products as $key => $value) {
                                    $product_values[$key] = $value;
                                }
                            ?>
                            <?= FORM::select('id_product', $product_values, $coupon->id_product, array(
                                'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ))?>
                        </div>
                    </div>
                    <div class="col-span-3 sm:col-span-2" x-show="fixed">
                        <?=FORM::label('discount_amount', __('Discount amount'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'discount_amount'])?>
                        <?=FORM::input('discount_amount', $coupon->discount_amount, ['class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5'])?>
                    </div>
                    <div class="col-span-3 sm:col-span-2" x-show="!fixed">
                        <?=FORM::label('discount_percentage', __('Discount percentage'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'discount_percentage'])?>
                        <?=FORM::input('discount_percentage', $coupon->discount_percentage, ['class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5'])?>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?=FORM::label('valid_date', __('Valid date'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'valid_date'])?>
                        <?=FORM::input('valid_date', $coupon->valid_date, [
                            'class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            'placeholder' => 'yyyy-mm-dd',
                            'data-toggle' => 'datepicker',
                            'data-date' => '',
                            'data-date-format' => 'yyyy-mm-dd',
                        ])?>
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <?=FORM::label('number_coupons', __('Number of coupons'), ['class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'number_coupons'])?>
                        <?=FORM::input('number_coupons', $coupon->number_coupons, ['class' => 'mt-1 form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5'])?>
                    </div>
                    <div class="col-span-3">
                        <div class="flex items-start">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('status', 'on', (bool) $coupon->status, ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('status', __('Status'), ['class'=>'font-medium text-gray-700', 'for'=>'status'])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <div class="flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a href="<?= Route::url('oc-panel', ['controller' => 'coupon']) ?>" role="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            <?= __('Cancel') ?>
                        </a>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                </div>
            </div>
        </div>
    <?= FORM::close() ?>
</div>
