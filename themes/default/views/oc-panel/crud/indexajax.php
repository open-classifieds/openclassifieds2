<?php defined('SYSPATH') or die('No direct script access.');?>

<style>
    .dropdown-menu {
        position: absolute;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        margin: 1px 0 0 2px;
        list-style: none;
        font-size: 14px;
        text-align: left;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: 4px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        background-clip: padding-box;
    }
</style>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= Text::ucfirst(__($name)) ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <p class="mt-2 items-center text-sm leading-5 text-gray-500">
                <?if($name == 'user'):?>
                    <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Users-manage-users']) ?>
                <?elseif($name == 'role'):?>
                    <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Users-how-do-roles-work']) ?>
                <?elseif($name == 'order'):?>
                    <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Orders']) ?>
                <?elseif($name == 'crontab'):?>
                    <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Extras-how-to-set-crons']) ?>
                <?elseif($name == 'plan'):?>
                    <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Plugins-membership-plans-to-post?id=create-plans']) ?>
                <?elseif($name == 'topic'):?>
                    <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Plugins-forum-section']) ?>
                <?endif?>
            </p>
        </div>
    </div>

    <?if ($controller->allowed_crud_action('create')):?>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="ml-3 shadow-sm rounded-md">
                <a href="<?= Route::url($route, ['controller' => Request::current()->controller(), 'action' => 'create']) ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    <?=__('New')?>
                </a>
            </span>
        </div>
    <?endif?>
</div>

<?if($extra_info_view):?>
    <p><?=$extra_info_view?></p>
<?endif?>

<div id="filter_buttons" data-url="<?=Route::url($route, ['controller'=> Request::current()->controller(), 'action'=>'ajax']).'?'.str_replace('rel=ajax','',$_SERVER['QUERY_STRING']) ?>">
    <?if (core::count($filters)>0):?>
        <form class="flex items-center mt-8 bg-white rounded-md shadow-sm" id="form-ajax-load" method="get" action="<?=Route::url($route, ['controller'=> Request::current()->controller(), 'action'=>'index']) ?>">
            <?foreach($filters as $field_name=>$values):?>
                <?if (is_array($values)):?>
                    <select name="filter__<?=$field_name?>" id="filter__<?=$field_name?>" class="-ml-px form-select relative block w-full rounded-none bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        <option value=""><?= $field_name ?> = <?=__('All')?></option>
                        <?foreach ($values as $key=>$value):?>
                            <option value="<?=$key?>" <?=(core::request('filter__'.$field_name)==$key AND core::request('filter__'.$field_name)!==NULL)?'SELECTED':''?> >
                                <?=$field_name?> = <?=$value?>
                            </option>
                        <?endforeach?>
                    </select>
                <?elseif($values=='DATE'):?>
                    <input type="text" class="datepicker_boot form-input relative block w-full rounded-none -ml-px bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" id="filter__from__<?=$field_name?>" name="filter__from__<?=$field_name?>" placeholder="<?=__('From')?> <?=$field_name?>" value="<?=core::request('filter__from__'.$field_name)?>" data-date="<?=core::request('filter__from__'.$field_name)?>" data-date-format="yyyy-mm-dd">
                    <input type="text" class="datepicker_boot form-input relative block w-full rounded-none -ml-px bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" id="filter__to__<?=$field_name?>" name="filter__to__<?=$field_name?>" placeholder="<?=__('To')?> <?=$field_name?>" value="<?=core::request('filter__to__'.$field_name)?>" data-date="<?=core::request('filter__to__'.$field_name)?>" data-date-format="yyyy-mm-dd">
                <?elseif($values=='RANGE'):?>
                    <input type="text" class="form-input relative block w-full rounded-none -ml-px bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" id="filter__from__<?=$field_name?>" name="filter__from__<?=$field_name?>" placeholder="<?=__('From')?> <?=$field_name?>" value="<?=core::request('filter__from__'.$field_name)?>" >
                    <input type="text" class="form-input relative block w-full rounded-none -ml-px bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" id="filter__to__<?=$field_name?>" name="filter__to__<?=$field_name?>" placeholder="<?=__('To')?> <?=$field_name?>" value="<?=core::request('filter__to__'.$field_name)?>" >
                <?elseif($values=='INPUT'):?>
                    <input type="text" class="form-input relative block w-full rounded-none -ml-px bg-transparent focus:z-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" id="filter__<?=$field_name?>" name="filter__<?=$field_name?>" placeholder="<?=(isset($captions[$field_name])?$captions[$field_name]['model'].' '.$captions[$field_name]['caption']:$field_name)?>" value="<?=core::request('filter__'.$field_name)?>" >
                <?endif?>
            <?endforeach?>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-none rounded-r-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out"><?=__('Filter')?></button>
        </form>
    <?endif?>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full overflow-hidden">
                <table id="grid-data-api" class="min-w-full">
                    <thead>
                        <tr>
                            <?foreach($fields as $field):?>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider" data-column-id="<?= $field ?>" <?= $elements->primary_key() == $field ? 'data-identifier="true"' : ''?> >
                                    <?if(isset($captions[$field]) AND !Exec::is_callable($captions[$field])):?>
                                        <?=Text::ucfirst($captions[$field]['model'].' '.$captions[$field]['caption'])?>
                                    <?else:?>
                                        <?=Text::ucfirst($field)?>
                                    <?endif?>
                                </th>
                            <?endforeach?>

                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50" data-column-id="commands" data-formatter="commands" data-sortable="false"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?if ($controller->allowed_crud_action('export')):?>
        <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
            <a class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:shadow-outline-green focus:border-green-700 active:bg-green-700 transition duration-150 ease-in-out" href="<?=Route::url($route, ['controller'=> Request::current()->controller(), 'action'=>'export']) ?>" title="<?=__('Export')?>">
                <?=__('Export all')?>
            </a>
        </div>
    <?endif?>
</div>
