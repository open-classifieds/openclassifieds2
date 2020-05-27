<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="flex flex-wrap list-none p-0 pl-0 mb-0 navbar-top-links navbar-right">
    <li class="hidden-xs hidden-sm hidden-md">
        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'favorites'))?>">
            <i class="linecon li_heart"></i> 
        </a>
    </li>
    <li class="hidden-xs hidden-sm hidden-md">
    <?if (core::config('general.messaging') AND $messages = Model_Message::get_unread_threads(Auth::instance()->get_user())) :?>
            <?if (($messages_count = $messages->count_all()) > 0) :?>
                <a class=" inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1"
                    href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>"
                    data-toggle="dropdown"
                >
                    <i class="linecon li_mail"></i><?=$messages_count?>
                </a>
                <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded">
                    <li class="block py-2 px-6 mb-0 text-sm text-greay-600 whitespace-no-wrap"><?=sprintf(__('You have %s unread messages'), $messages_count)?></li>
                    <?foreach ($messages->find_all() as $message):?>
                        <li>
                            <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>($message->id_message_parent != NULL) ? $message->id_message_parent : $message->id_message))?>">
                                <small><strong><?=isset($message->ad->title) ? $message->ad->title : __('Direct Message')?></strong></small>
                                <br>
                                <small><em><?=$message->from->name?></em></small>
                            </a>
                        </li>
                    <?endforeach?>
                </ul>
            <?else:?>
                <a
                    href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>"
                    title="<?=__('You have no unread messages')?>"
                    data-toggle="popover"
                    data-placement="bottom"
                >
                    <i class="linecon li_mail"></i> <?=$messages_count?>
                </a>
            <?endif?>
    <?elseif ($ads = Auth::instance()->get_user()->contacts() AND core::count($ads) > 0) :?>
            <a class=" inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1" data-toggle="relative" href="#" id="contact-notification" data-url="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'notifications'))?>">
                <i class="linecon li_mail"></i> <?=core::count($ads)?>
            </a>
            <ul id="contact-notification-dd" class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded">
                <li class="block py-2 px-6 mb-0 text-sm text-greay-600 whitespace-no-wrap"><?=__('Please check your email')?></li>
                <li class="divider"></li>
                <li class="block py-2 px-6 mb-0 text-sm text-greay-600 whitespace-no-wrap"><?=__('You have been contacted for these ads')?></li>
                <?foreach ($ads as $ad ):?>
                    <li class="block py-2 px-6 mb-0 text-sm text-greay-600 whitespace-no-wrap"><strong><?=$ad["title"]?></strong></li>
                <?endforeach?>
            </ul>
    <?endif?>
    </li>
    <li class="relative">
        <a class=" inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1 profile-dropdown" data-toggle="relative" href="#">
            <span><?=Auth::instance()->get_user()->name?></span>
            <img src="<?=Auth::instance()->get_user()->get_profile_image()?>" alt="" height="32" width="32" class="img-circle profile-img">
        </a>
        <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded pull-right dropdown-profile">
            <li>
                <a href="<?=Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname))?>">
                    <i class="fa fa-fw fa-user"></i> <?=__('Public profile')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>">
                    <i class="fa fa-fw fa-edit"></i> <?=__('Edit profile')?>
                </a>
            </li>
            <li class="hidden-lg">
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'favorites'))?>">
                    <i class="fa fa-fw fa-heart"></i> <?=__('My Favorites')?>
                </a>
            </li>
            <?if (core::config('general.messaging') == TRUE):?>
            <li class="hidden-lg">
                <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>">
                    <i class="fa fa-fw fa-envelope-o"></i> <?=__('My Messages')?>
                </a>
            </li>
            <?endif?>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))?>">
                    <i class="fa fa-fw fa-list-alt"></i> <?=__('My Advertisements')?>
                </a>
            </li>  
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>">
                    <i class="fa fa-fw fa-shopping-cart"></i> <?=__('My Payments')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'subscriptions'))?>">
                    <i class="fa fa-fw fa-calendar-check-o"></i> <?=__('My Subscriptions')?>
                </a>
            </li>
            <li class="divider hidden-lg"></li>                                        
            <li class="hidden-lg">
                <a href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
                    <i class="fa fa-fw fa-sign-out"></i> <?=__('Logout')?>
                </a>
            </li>
        </ul>
    </li>
    </li>
    <li class="hidden-xs hidden-sm hidden-md">
        <a href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
            <i class="fa fa-fw fa-sign-out"></i> <?=__('Logout')?>
        </a>
    </li>
</ul>
