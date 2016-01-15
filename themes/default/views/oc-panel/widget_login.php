<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="nav navbar-top-links navbar-right">
    <li class="hidden-xs hidden-sm hidden-md">
        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'favorites'))?>">
            <i class="linecon li_heart"></i><?=__('Favorites')?>
        </a>
    </li>
    <li class="hidden-xs hidden-sm hidden-md">
    <?if (core::config('general.messaging') AND $messages = Model_Message::get_unread_threads(Auth::instance()->get_user())) :?>
            <?if ($messages_count = $messages->count_all() > 0) :?>
                <a class="dropdown-toggle"
                    href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>"
                    data-toggle="dropdown"
                    data-target="#"
                >
                    <i class="linecon li_mail"></i><?=$messages_count?>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown-header"><?=sprintf(__('You have %s unread messages'), $messages_count)?></li>
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
                    data-target="#"
                    data-placement="bottom"
                >
                    <i class="linecon li_mail"></i><?=__('My Messages')?> <?=$messages_count?>
                </a>
            <?endif?>
    <?elseif ($ads = Auth::instance()->get_user()->contacts() AND count($ads) > 0) :?>
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="contact-notification" data-url="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'notifications'))?>">
                <i class="linecon li_mail"></i><?=count($ads)?>
            </a>
            <ul id="contact-notification-dd" class="dropdown-menu">
                <li class="dropdown-header"><?=__('Please check your email')?></li>
                <li class="divider"></li>
                <li class="dropdown-header"><?=__('You have been contacted for these ads')?></li>
                <?foreach ($ads as $ad ):?>
                    <li class="dropdown-header"><strong><?=$ad["title"]?></strong></li>
                <?endforeach?>
            </ul>
    <?endif?>
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle profile-dropdown bigger" data-toggle="dropdown" href="#">
            <img src="/themes/default/img/no-profile-image.jpg" alt="" height="42" width="42" class="profile-img"><span class="hidden-xs"><?=Auth::instance()->get_user()->name?></span><i class="fa fa-angle-down"></i>
            <!-- Loading a vector svg instead of an image could maybe be better -->
        </a>
        <ul class="dropdown-menu pull-right">
            <li>
                <a href="<?=Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname))?>">
                    <i class="fa fa-user"></i><?=__('Public profile')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>">
                    <i class="fa fa-edit"></i><?=__('Edit profile')?>
                </a>
            </li>
            <li class="hidden-lg">
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'favorites'))?>">
                    <i class="fa fa-heart"></i><?=__('My Favorites')?>
                </a>
            </li>
            <?if (core::config('general.messaging') == TRUE):?>
            <li class="hidden-lg">
                <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>">
                    <i class="fa fa-envelope-o"></i><?=__('My Messages')?>
                </a>
            </li>
            <?endif?>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))?>">
                    <i class="fa fa-list-alt"></i><?=__('My Advertisements')?>
                </a>
            </li>  
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>">
                    <i class="fa fa-shopping-cart"></i><?=__('My Payments')?>
                </a>
            </li>
            <li>
                <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'subscriptions'))?>">
                    <i class="fa fa-calendar-check-o"></i><?=__('My Subscriptions')?>
                </a>
            </li>
            <li class="divider hidden-lg"></li>                                        
            <li class="hidden-lg">
                <a href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
                    <i class="fa fa-sign-out"></i><?=__('Logout')?>
                </a>
            </li>
        </ul>
    </li>
    </li>
    <li class="hidden-xs hidden-sm hidden-md">
        <a href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>" class="bigger">
            <i class="fa fa-sign-out"></i><?=__('Logout')?>
        </a>
    </li>
</ul>
