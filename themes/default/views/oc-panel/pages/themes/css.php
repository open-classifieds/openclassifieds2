<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Custom CSS') ?>
        </h2>
    </div>
</div>

<form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post">
    <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <? if (Theme::get_custom_css()): ?>
                <div>
                    <p class="mt-1 text-sm leading-5 text-gray-500">
                        <?=__('Current CSS file')?>  <?=HTML::anchor(Theme::get_custom_css())?>
                    </p>
                </div>
            <? endif ?>
            <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="about" class="block text-sm font-medium leading-5 text-gray-700">
                        CSS
                    </label>
                    <div class="mt-1 rounded-md shadow-sm">
                        <textarea id="css" name="css" rows="30" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"><?= $css_content ?></textarea>
                    </div>
                </div>
                <div class="sm:col-span-3">
                    <div class="absolute flex items-center h-5">
                        <?=Form::hidden('css_active', 0)?>
                        <?=Form::checkbox('css_active', 1, (bool) Core::post('css_active', Core::config('payment.css_active')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                    </div>
                    <div class="pl-7 text-sm leading-5">
                        <?=FORM::label('css_active', __('Active'), ['class'=>'font-medium text-gray-700'])?>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <span class="inline-flex rounded-md shadow-sm">
                    <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                </span>
            </div>
        </div>
    </div>
</form>
