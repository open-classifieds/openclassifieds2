<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Moderation')?>
        </h2>
    </div>
</div>

<?$current_url = Model_Ad::STATUS_NOPUBLISHED?>

<? if (Core::count($ads) > 0) : ?>
    <div
        x-data="{ action: '', selectAll: false }"
        @fireaction="
            action = $event.detail.action; $refs.form.setAttribute('action', action);
            $refs.form.submit()
        "
    >
        <form method="GET" enctype="multipart/form-data">
            <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
                <div class="flex flex-col">
                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div class="align-middle inline-block min-w-full overflow-hidden sm:rounded-lg border-b border-gray-200">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            <?= Form::checkbox('select_all', 1, 0, [
                                                'class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out',
                                                '@click' => 'selectAll = ! selectAll'
                                            ])?>
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            <?= __('Name') ?>
                                        </th>
                                        <?if(core::config('advertisement.count_visits')==1):?>
                                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                <?= __('Hits') ?>
                                            </th>
                                        <?endif?>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            <?= __('Status') ?>
                                        </th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            <?= __('Created') ?>
                                        </th>
                                        <?if(isset($ads)):?>
                                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-right">
                                                <div x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left ml-3">
                                                    <div>
                                                        <button @click.prevent="open = !open" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                        </button>
                                                    </div>
                                                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg z-10">
                                                        <div class="rounded-md bg-white shadow-xs">
                                                            <div class="py-1">
                                                                <button
                                                                    type="button"
                                                                    @click="$dispatch('fireaction', {action: '<?=Route::url('oc-panel', ['controller'=>'ad','action'=>'activate'])?>'})"
                                                                    class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                >
                                                                    <?=__('Activate')?>
                                                                </button>
                                                                <button
                                                                    type="button"
                                                                    @click="$dispatch('fireaction', {action: '<?=Route::url('oc-panel', ['controller'=>'ad','action'=>'spam'])?>'})"
                                                                    class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                >
                                                                    <?=__('Spam')?>
                                                                </button>
                                                            </div>
                                                            <div class="border-t border-gray-100"></div>
                                                            <div class="py-1">
                                                                <button
                                                                    formaction="<?=Route::url('oc-panel', ['controller'=>'ad','action'=>'delete'])?>"
                                                                    class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                >
                                                                    <?=__('Delete')?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        <?endif?>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <?$i = 0; foreach($ads as $ad):?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium text-gray-900">
                                                <?=Form::checkbox('id_ads[]', $ad->id_ad, 0, ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out', 'id' => 'select-all'])?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                <?= $ad->id_ad ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">
                                                    <a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?= wordwrap($ad->title, 15, "<br />\n"); ?></a>
                                                </div>
                                                <div class="text-sm leading-5 text-gray-500">
                                                    <?= wordwrap($ad->category->name, 15, "<br />\n"); ?>
                                                </div>
                                                <?if($ad->location->loaded()):?>
                                                    <div class="text-sm leading-5 text-gray-500">
                                                        <?= wordwrap($ad->location->name, 15, "<br />\n"); ?>
                                                    </div>
                                                <?endif?>
                                            </td>
                                            <?if(core::config('advertisement.count_visits')==1):?>
                                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                    <?=$ad->count_ad_hit() ?>
                                                </td>
                                            <?endif?>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                <?if($ad->status == Model_Ad::STATUS_NOPUBLISHED):?>
                                                    <?=__('Not published')?>
                                                <? elseif($ad->status == Model_Ad::STATUS_PUBLISHED):?>
                                                    <?=__('Published')?>
                                                <? elseif($ad->status == Model_Ad::STATUS_SPAM):?>
                                                    <?=__('Spam')?>
                                                <? elseif($ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
                                                    <?=__('Unavailable')?>
                                                <?endif?>

                                                <?if( ($order = $ad->get_order())!==FALSE ):?>
                                                    <a class="label <?=($order->status==Model_Order::STATUS_PAID)?'label-success':'label-warning'?> "
                                                        href="<?=Route::url('oc-panel', array('controller'=> 'order','action'=>'index'))?>?email=<?=$order->user->email?>">
                                                    <?if ($order->status==Model_Order::STATUS_CREATED):?>
                                                        <br> <?=__('Not paid')?>
                                                    <?elseif ($order->status==Model_Order::STATUS_PAID):?>
                                                        <br> <?=__('Paid')?>
                                                    <?endif?>
                                                        <?=i18n::format_currency($order->amount,$order->currency)?>
                                                    </a>
                                                <?endif?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                                <?=Date::format($ad->created, core::config('general.date_format'))?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5">
                                                <div class="flex justify-end items-center space-x-3">
                                                    <div class="font-medium">
                                                        <a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" class="text-green-600 hover:text-green-900 focus:outline-none focus:underline"><?= __('Activate') ?></a>
                                                    </div>
                                                    <div class="font-medium">
                                                        <a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>" class="text-blue-600 hover:text-blue-900 focus:outline-none focus:underline"><?= __('Edit') ?></a>
                                                    </div>
                                                    <div x-data="{ open: false }" @keydown.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                                                        <div>
                                                            <button @click.prevent="open = !open" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                            </svg>
                                                            </button>
                                                        </div>
                                                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg z-10">
                                                            <div class="rounded-md bg-white shadow-xs">
                                                                <div class="py-1">
                                                                    <?if($ad->status != Model_Ad::STATUS_SPAM):?>
                                                                        <a
                                                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                            href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>?current_url=<?=$current_url?>"
                                                                        >
                                                                            <?=__('Spam')?>
                                                                        </a>
                                                                    <?endif?>
                                                                </div>
                                                                <?if($current_url == Model_Ad::STATUS_PUBLISHED):?>
                                                                    <div class="border-t border-gray-100"></div>
                                                                    <?if(core::config('payment.to_featured') != FALSE):?>
                                                                        <?if($ad->featured==NULL OR Date::mysql2unix($ad->featured) < time()):?>
                                                                            <a
                                                                                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                                href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_featured','id'=>$ad->id_ad))?>"
                                                                            >
                                                                                <?=__('Featured')?>
                                                                            </a>
                                                                        <?elseif(Date::mysql2unix($ad->featured) > time()):?>
                                                                            <a
                                                                                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                                href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'unfeature','id'=>$ad->id_ad))?>"
                                                                            >
                                                                                <?=__('Remove Featured')?>
                                                                            </a>
                                                                        <?endif?>
                                                                    <?endif?>
                                                                    <?if(core::config('payment.pay_to_go_on_top') > 0 AND core::config('payment.to_top') != FALSE):?>
                                                                        <a
                                                                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                            href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_top','id'=>$ad->id_ad))?>"
                                                                        >
                                                                            <?=__('Go to top')?>
                                                                        </a>
                                                                    <?endif?>
                                                                    <a
                                                                        class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                        href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'stats','id'=>$ad->id_ad))?>"
                                                                    >
                                                                        <?=__('Stats')?>
                                                                    </a>
                                                                <?endif?>
                                                                <div class="border-t border-gray-100"></div>
                                                                <div class="py-1">
                                                                    <a
                                                                        class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                                        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>?current_url=<?=$current_url?>"
                                                                    >
                                                                        <?=__('Delete')?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <? endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<? endif ?>

<?if(isset($pagination)):?>
    <div class="text-center"><?=$pagination?></div>
<?endif?>
