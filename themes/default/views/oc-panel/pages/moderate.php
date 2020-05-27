<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<h1 class="page-header page-title">
    <?=__('Moderation')?>
</h1>

<hr>

<?$current_url = Model_Ad::STATUS_NOPUBLISHED?>

<div class="panel panel-default">
    <div>
        <form method="GET" enctype="multipart/form-data">
            <table class="w-full max-w-full mb-4 bg-transparent table-striped">
                <thead>
                    <tr>
                        <th class="sorting_disabled">
                            <div class="checkbox check-success">
                                <input type="checkbox" id="select-all">
                                <label for="select-all"></label>
                            </div>
                        </th>
                        <th class="sorting_disabled"><?=__('Activate')?></th>
                        <th class="sorting_disabled"><?=__('Name')?></th>
                        <th class="sorting_disabled"><?=__('Category')?></th>
                        <th class="sorting_disabled"><?=__('Location')?></th>
                        <?if(core::config('advertisement.count_visits')==1):?>
                            <th class="sorting_disabled"><?=__('Hits')?></th>
                        <?endif?>
                        <th class="sorting_disabled"><?=__('Status')?></th>
                        <th class="sorting_disabled"><?=__('Date')?></th>
                        <!-- in case there are no ads we dont show buttons -->
                        <?if(isset($ads)):?>
                            <th class="sorting_disabled nowrap">
                                <div class="relative">
                                    <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default  inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1" type="button" data-toggle="relative">
                                        <i class="fa fa-cog"></i>
                                    </button>
                                    <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded dropdown-menu-right">
                                        <li>
                                            <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  block w-full font-normal blue bg-transparent activate"
                                                data-toggle="confirmation"
                                                formaction="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>"
                                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                data-btnCancelLabel="<?=__('No way!')?>"
                                                title="<?=__('Activate?')?>">
                                                <i class="fa fa-check"></i> <?=__('Activate')?></span>
                                            </button>
                                        </li>
                                        <?if(Core::get('status') != Model_Ad::STATUS_SPAM):?>
                                            <li>
                                                <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  block w-full font-normal blue bg-transparent spam"
                                                    data-toggle="confirmation"
                                                    formaction="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>"
                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                    data-btnCancelLabel="<?=__('No way!')?>"
                                                    title="<?=__('Spam?')?>">
                                                    <i class="fa fa-fw fa-fire"></i> <?=__('Spam')?></span>
                                                </button>
                                            </li>
                                        <?endif?>
                                        <li>
                                            <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  block w-full font-normal blue bg-transparent delete"
                                                data-toggle="confirmation"
                                                formaction="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
                                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                data-btnCancelLabel="<?=__('No way!')?>"
                                                title="<?=__('Delete?')?>" data-text="<?=__('Are you sure you want to delete?')?>">
                                                <i class="fa fa-fw fa-times"></i> <?=__('Delete')?></span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </th>
                        <?endif?>
                    </tr>
                </thead>
                <?if(isset($ads)):?>
                    <tbody>
                        <?foreach($ads as $ad):?>
                            <tr id="tr<?=$ad->id_ad?>">
                                <td>
                                    <div class="checkbox check-success">
                                        <input name="id_ads[]" value="<?=$ad->id_ad?>" type="checkbox" id="ad_<?=$ad->id_ad?>">
                                        <label for="ad_<?=$ad->id_ad?>"></label>
                                    </div>
                                </td>
                                <td>
                                    <a
                                        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>"
                                        class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-green-100 bg-green-500 hover:bg-green-400 index-moderation"
                                        title="<?=__('Activate?')?>"
                                        data-id="tr<?=$ad->id_ad?>"
                                        data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                        data-btnCancelLabel="<?=__('No way!')?>">
                                        <i class="glyphicon glyphicon-ok-sign"></i> <?=$ad->id_ad?>
                                    </a>
                                </td>

                                <td><a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><?= wordwrap($ad->title, 45, "<br />\n"); ?></a>
                                </td>

                                <td><?= wordwrap($ad->category->name, 15, "<br />\n"); ?>

                                <td>
                                    <?if($ad->location->loaded()):?>
                                        <?=wordwrap($ad->location->name, 15, "<br />\n");?>
                                    <?else:?>
                                        n/a
                                    <?endif?>
                                </td>
                                <?if(core::config('advertisement.count_visits')==1):?>
                                <td><?=$ad->count_ad_hit();?></td>
                                <?endif?>

                                <td>
                                <?if($ad->status == Model_Ad::STATUS_NOPUBLISHED):?>
                                    <?=__('Not published')?>
                                <? elseif($ad->status == Model_Ad::STATUS_PUBLISHED):?>
                                    <?=__('Published')?>
                                <? elseif($ad->status == Model_Ad::STATUS_SPAM):?>
                                    <?=__('Spam')?>
                                <? elseif($ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
                                    <?=__('Unavailable')?>
                                <? elseif($ad->status == Model_Ad::STATUS_SOLD):?>
                                    <?=__('Sold')?>
                                <?endif?>

                                <?if( ($order = $ad->get_order())!==FALSE ):?>
                                    <a class="label <?=($order->status==Model_Order::STATUS_PAID)?'label-success':'label-warning'?> "
                                        href="<?=Route::url('oc-panel', array('controller'=> 'order','action'=>'index'))?>?email=<?=$order->user->email?>">
                                    <?if ($order->status==Model_Order::STATUS_CREATED):?>
                                        <?=__('Not paid')?>
                                    <?elseif ($order->status==Model_Order::STATUS_PAID):?>
                                        <?=__('Paid')?>
                                    <?endif?>
                                        <?=i18n::format_currency($order->amount,$order->currency)?>
                                    </a>
                                <?endif?>
                                </td>

                                <td><?= Date::format($ad->created, core::config('general.date_format'))?></td>
                                <td class="nowrap">
                                    <div class="relative inline-flex align-middle">
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"
                                            href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"
                                            rel="tooltip" title="<?=__('Edit')?>">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <div class="relative inline-flex align-middle">
                                            <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default  inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1" type="button" data-toggle="relative">
                                                <i class="fa fa-cog"></i>
                                            </button>
                                            <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded dropdown-menu-right">
                                                <li>
                                                    <a
                                                        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad, 'current_url'=>$current_url))?>"
                                                        class="index-moderation"
                                                        title="<?=__('Spam')?>"
                                                        data-id="tr<?=$ad->id_ad?>"
                                                        data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                        data-btnCancelLabel="<?=__('No way!')?>">
                                                        <i class="fa fa-fw fa-fire"></i> <?=__('Spam')?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a
                                                        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad, 'current_url'=>$current_url))?>"
                                                        class="index-moderation"
                                                        title="<?=__('Delete')?>"
                                                        data-id="tr<?=$ad->id_ad?>"
                                                        data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                        data-btnCancelLabel="<?=__('No way!')?>">
                                                        <i class="fa fa-fw fa-trash"></i> <?=__('Delete')?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?endforeach?>
                    </tbody>
                <?endif?>
            </table>
        <?if(isset($pagination)):?>
            <div class="text-center">
                <?=$pagination?>
            </div>
        <?endif?>
    </div>
</div>
