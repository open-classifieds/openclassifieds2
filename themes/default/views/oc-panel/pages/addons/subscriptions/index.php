<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Subscriptions') ?>
        </h2>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<?= Form::open(Route::url('oc-panel/addons', ['controller' => 'subscriptions'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base leading-5 font-medium text-gray-900">
                <?= __('Enable Subscriptions') ?>
            </h3>
            <div class="mt-2 sm:flex sm:items-start sm:justify-between">
                <div class="max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?= __('With Subscriptions/Memberships you can charge daily, weekly, monthly or yearly subscription to your users to be able to post to your website.') ?>
                    </p>
                </div>
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <?=FORM::checkbox('is_active', 1, (bool) Core::post('is_active', $is_active), ['class' => 'form-checkbox h-6 w-6 text-blue-600 bg-gray-100 transition duration-150 ease-in-out'])?>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('subscriptions_expire', 1, (bool) Core::post('subscriptions_expire', Core::config('general.subscriptions_expire')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('subscriptions_expire', __('Subscription Expire'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500"><?=__("Once set to TRUE, if user subscription expires, the user and the ads get disabled, until renewal.")?></p>
                        </div>
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
<?= Form::close() ?>

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
    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
            <div class="ml-4 mt-2">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?= __('Plans') ?>
                </h3>
            </div>
            <div class="ml-4 mt-2 flex-shrink-0">
                <span class="inline-flex rounded-md shadow-sm">
                    <a href="<?= Route::url('oc-panel', ['controller' => 'plan', 'action' => 'create']) ?>" role="button" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700">
                        <?= __('New plan') ?>
                    </a>
                </span>
            </div>
        </div>
    </div>
    <div class="flex flex-col mt-8">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
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

                <?if ($controller->allowed_crud_action('export')):?>
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 flex justify-end">
                        <a class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-500 focus:outline-none focus:shadow-outline-green focus:border-green-700 active:bg-green-700 transition duration-150 ease-in-out" href="<?=Route::url($route, ['controller'=> Request::current()->controller(), 'action'=>'export']) ?>" title="<?=__('Export')?>">
                            <?=__('Export all')?>
                        </a>
                    </div>
                <?endif?>
            </div>
        </div>
    </div>
</div>
