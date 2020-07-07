<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Coupons') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Classifieds-coupon-system']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('New coupon') ?>
            </a>
        </span>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<?if (Core::count($elements) > 0):?>
    <div class="flex flex-col mt-8">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Name') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Discount') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Number Coupons') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Valid until') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Created') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <? foreach ($elements as $key => $coupon): ?>
                            <? $last_item = $key === count($elements) - 1 ?>
                            <?= View::factory('oc-panel/pages/coupon/_coupon', ['coupon' => $coupon, 'route' => $route, 'last_item' => $last_item]) ?>
                        <? endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?endif?>

<div class="text-center"><?=$pagination?></div>

<a class="mt-5 inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  py-1 px-2 text-sm leading-tight text-green-100 bg-green-500 hover:bg-green-400 pull-right " href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
    <i class="glyphicon glyphicon-download"></i>
    <?=__('Export all')?>
</a>

<?=FORM::open(Route::url('oc-panel',array('controller'=>'coupon','action'=>'import')), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?=__('Import coupons')?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?=__('Please use the correct CSV format')?> <a class="hover:underline" href="https://cdn.rawgit.com/yclas/yclas/master/install/samples/import/coupons.csv"><?=__('download example')?></a>.
                </p>
            </div>
            <div class="mt-5 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=Form::label('csv_file_categories', __('csv_file_categories'), ['class' => 'sr-only', 'for' => 'csv_file_categories'])?>
                    <div class="relative rounded-md shadow-sm">
                        <input type="file" name="csv_file_categories" id="csv_file_categories" />
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                </span>
            </div>
        </div>
    </div>
<?=Form::close()?>
