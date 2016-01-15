<?php defined('SYSPATH') or die('No direct script access.');?>

<aside class="sidebar" role="navigation">
    <div class="sidebar-nav">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                    <button class="btn" type="button" role="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
                </div>
            </li>
            <? if($user->id_role==Model_Role::ROLE_ADMIN OR $user->id_role==Model_Role::ROLE_MODERATOR):?>
                <li>
                    <a href="#"><i class="linecon li_display"></i> <span class="hidden-xs"><?=__('Panel')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><?=__('Home')?></a>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Stats'),'stats','index','oc-panel','')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Updates'), 'update','index','oc-panel')?>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="linecon li_tag"></i> <span class="hidden-xs"><?=__('Classifieds')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?=Theme::admin_link(__('Advertisements'),'ad','index','oc-panel')?>
                        </li>
                        <? if( in_array(core::config('general.moderation'), Model_Ad::$moderation_status)  ):  // payment with moderation?>
                        <li>
                            <?=Theme::admin_link(__('Moderation'),'ad','moderate','oc-panel')?>
                        </li>
                        <? endif?>
                        <li>
                            <?=Theme::admin_link(__('Categories'),'category','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Locations'),'location','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Orders'), 'order','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Coupons'), 'coupon','index','oc-panel')?>
                        </li>
                        <?if (core::config('advertisement.reviews')==1):?>
                        <li>
                            <?=Theme::admin_link(__('Reviews'), 'review','index','oc-panel')?>
                        </li>
                        <?endif?>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="linecon li_note"></i> <span class="hidden-xs"><?=__('Content')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?=Theme::admin_link(__('Pages'), 'content','page','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Email'), 'content','email','oc-panel')?>
                        </li>
                        <?if (core::config('general.faq')==1):?>
                        <li>
                            <?=Theme::admin_link(__('FAQ'), 'content','help','oc-panel')?>
                        </li>
                        <?endif?>
                        <li>
                            <?=Theme::admin_link(__('Media'), 'cmsimages','index','oc-panel')?>
                        </li>
                        <li <?=(Request::current()->controller()=='map')?'class="active"':''?> >
                            <a href="<?=Route::url('oc-panel',array('controller'=>'map','action'=>'index'))?>" title="<?=__('Interactive Map')?>">
                                <span class="side-name-link"><?=__('Interactive Map')?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="linecon li_photo"></i> <span class="hidden-xs"><?=__('Appearance')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?=Theme::admin_link(__('Themes'), 'theme','index','oc-panel')?>
                        </li>
                        <?if (Theme::has_options()):?>
                        <li>
                            <?=Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel')?>
                        </li>
                        <?endif?>
                        <li>
                            <?=Theme::admin_link(__('Widgets'), 'widget','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Menu'), 'menu','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Custom CSS'), 'theme','css','oc-panel')?>
                        </li>
                    </ul>
                </li>
                <?if ($user->has_access_to_any('settings,config')):?>
                <li>
                    <a href="#"><i class="linecon li_params"></i> <span class="hidden-xs"><?=__('Settings')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">           
                        <li>
                            <?=Theme::admin_link(__('General'), 'settings','general','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Advertisement'), 'settings','form','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Email settings'), 'settings','email','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Payment'), 'settings','payment','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Plugins'), 'settings','plugins','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Media settings'), 'settings','image','oc-panel')?>
                        </li>
                        <?if (core::config('general.social_auth')):?>
                        <li>
                            <?=Theme::admin_link(__('Social Auth'), 'social','index','oc-panel')?>
                        </li>
                        <?endif?>
                    </ul>
                </li>
                <?endif?>
                <?if ($user->has_access_to_any('user,role,access')):?>
                <li>
                    <a href="#"><i class="linecon li_user"></i> <span class="hidden-xs"><?=__('Users')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?=Theme::admin_link(__('Users'),'user','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Roles'),'role','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('User custom Fields'), 'userfields','index','oc-panel')?>
                        </li>
                        <?if(core::config('general.black_list')):?>
                        <li>
                            <?=Theme::admin_link(__('User black list'),'pool','index','oc-panel')?>
                        </li>
                        <?endif?>
                    </ul>
                </li>
                <?endif?>
                <?if ($user->has_access_to_any('tools')):?>
                <li>
                    <a href="#"><i class="linecon li_lab"></i> <span class="hidden-xs"><?=__('Extra')?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <?=Theme::admin_link(__('Tools'), 'tools','optimize','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Import'), 'import','index','oc-panel')?>
                        </li>
                        <li>
                            <?=Theme::admin_link(__('Export'), 'tools','export','oc-panel')?>
                        </li>
                        <li>
                            <a href="http://market.open-classifieds.com/oc-panel/support/index" target="_blank"><?=__('I need help')?></a>
                        </li>
                    </ul>
                </li>
                <?endif?>
            <?endif?>
            <? if($user->id_role==Model_Role::ROLE_TRANSLATOR):?>
                <a href="#"><i class="linecon li_note"></i> <span class="hidden-xs"><?=__('Content')?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <?=Theme::admin_link(__('Pages'), 'content','page','oc-panel')?>
                    </li>
                    <?if (core::config('general.blog')==1):?>
                    <li>
                        <?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel')?>
                    </li>
                    <?endif?>
                    <li>
                        <?=Theme::admin_link(__('Email'), 'content','email','oc-panel')?>
                    </li>
                    <?if (core::config('general.faq')==1):?>
                    <li>
                        <?=Theme::admin_link(__('FAQ'), 'content','help','oc-panel')?>
                    </li>
                    <?endif?>
                    <li>
                        <?=Theme::admin_link(__('Media'), 'cmsimages','index','oc-panel')?>
                    </li>
                    <li <?=(Request::current()->controller()=='map')?'class="active"':''?> >
                        <a href="<?=Route::url('oc-panel',array('controller'=>'map','action'=>'index'))?>" title="<?=__('Interactive Map')?>">
                            <span class="side-name-link"><?=__('Interactive Map')?></span>
                        </a>
                    </li>
                </ul>
            <?endif?>
            <li id="collapse-menu">
                <i class="fa fa-angle-double-left"></i><span><?=__('Collapse menu')?></span>
            </li>
        </ul>
    </div>
</aside>
