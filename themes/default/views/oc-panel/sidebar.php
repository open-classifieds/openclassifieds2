<?php defined('SYSPATH') or die('No direct script access.');?>

<aside class="sidebar" role="navigation">
    <div class="sidebar-nav">
        <ul class="flex flex-wrap list-none p-0 pl-0 mb-0" id="side-menu">
            <li class="sidebar-search hidden-xs">
                <div class="relative flex items-stretch w-full custom-search-form">
                    <input type="text" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" data-keybinding='["command+shift+s", "ctrl+shift+s"]' placeholder="<?=__('Search...')?>">
                    <span class="input-group-btn">
                        <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal " type="button" role="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <div class="search-list" style="display: none;">
                    <ul class="list-unstyled">
                        <?if($user->is_admin() OR $user->is_moderator()):?>
                            <li>
                                <a class="ajax-load" data-keybinding='g h' href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><?=__('Home')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" data-keybinding='g s' href="<?=Route::url('oc-panel',array('controller'=>'stats'))?>"><?=__('Stats')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update'))?>"><?=__('Updates')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" data-keybinding='g a' href="<?=Route::url('oc-panel',array('controller'=>'ad'))?>"><?=__('Advertisements')?></a>
                            </li>
                            <?if( in_array(core::config('general.moderation'), Model_Ad::$moderation_status)  ):  // payment with moderation?>
                                <li>
                                    <a class="ajax-load" data-keybinding='g m' href="<?=Route::url('oc-panel',array('controller'=>'ad', 'action'=>'moderate'))?>"><?=__('Moderation')?></a>
                                </li>
                            <?endif?>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'category'))?>"><?=__('Categories')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'location'))?>"><?=__('Locations')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'fields'))?>"><?=__('Custom Fields')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" data-keybinding='g o' href="<?=Route::url('oc-panel',array('controller'=>'order'))?>"><?=__('Orders')?></a>
                            </li>
                            <?if (core::config('general.subscriptions')==1):?>
                                <li>
                                    <a class="ajax-load" data-keybinding='g p' href="<?=Route::url('oc-panel',array('controller'=>'plan'))?>"><?=__('Plans')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" data-keybinding='g s' href="<?=Route::url('oc-panel',array('controller'=>'subscription'))?>"><?=__('Subscriptions')?></a>
                                </li>
                            <?endif?>
                            <li>
                                <a class="ajax-load" data-keybinding='g c o' href="<?=Route::url('oc-panel',array('controller'=>'coupon'))?>"><?=__('Coupons')?></a>
                            </li>
                            <?if (core::config('advertisement.reviews')==1):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'review'))?>"><?=__('Reviews')?></a>
                                </li>
                            <?endif?>
                            <?if (core::config('general.blog')==1):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'blog'))?>"><?=__('Blog')?></a>
                                </li>
                            <?endif?>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'content', 'action'=>'page'))?>"><?=__('Pages')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'content', 'action'=>'email'))?>"><?=__('Email')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'newsletter'))?>"><?=__('Newsletters')?></a>
                            </li>
                            <?if (core::config('general.faq')==1):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'content', 'action'=>'help'))?>"><?=__('FAQ')?></a>
                                </li>
                            <?endif?>
                            <?if(core::config('general.forums')==1):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'forum'))?>"><?=__('Forums')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'topic'))?>"><?=__('Topics')?></a>
                                </li>
                            <?endif?>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'cmsimages'))?>"><?=__('Media')?></a>
                            </li>
                            <li <?=(Request::current()->controller()=='map')?'class="active"':''?> >
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'map','action'=>'index'))?>" title="<?=__('Interactive Map')?>">
                                    <span class="side-name-link"><?=__('Interactive Map')?></span>
                                </a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Themes')?></a>
                            </li>
                            <?if (Theme::has_options()):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme', 'action'=>'options'))?>"><?=__('Themes Options')?></a>
                                </li>
                            <?endif?>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'widget'))?>"><?=__('Widgets')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'menu'))?>"><?=__('Menu')?></a>
                            </li>
                            <li>
                                <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme', 'action'=>'css'))?>"><?=__('Custom CSS')?></a>
                            </li>
                            <?if ($user->has_access_to_any('settings,config')):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))?>"><?=__('Settings')?> - <?=__('General')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))?>"><?=__('Settings')?> - <?=__('Advertisement')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'email'))?>"><?=__('Settings')?> - <?=__('Email')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))?>"><?=__('Settings')?> - <?=__('Payment')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'plugins'))?>"><?=__('Plugins')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'translations'))?>"><?=__('Translations')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'image'))?>"><?=__('Media settings')?></a>
                                </li>
                                <?if (core::config('general.social_auth')):?>
                                     <li>
                                        <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'social'))?>"><?=__('Social Auth')?></a>
                                    </li>
                                <?endif?>
                            <?endif?>
                            <?if ($user->has_access_to_any('user,role,access')):?>
                                <li>
                                    <a class="ajax-load" data-keybinding='g u' href="<?=Route::url('oc-panel',array('controller'=>'user'))?>"><?=__('Users')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'role'))?>"><?=__('Roles')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'userfields'))?>"><?=__('User custom Fields')?></a>
                                </li>
                                <?if(core::config('general.black_list')):?>
                                    <li>
                                        <a class="ajax-load" data-keybinding='g b l' href="<?=Route::url('oc-panel',array('controller'=>'pool'))?>"><?=__('User black list')?></a>
                                    </li>
                                <?endif?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'subscriber'))?>"><?=__('Subscribers')?></a>
                                </li>
                            <?endif?>
                            <?if ($user->has_access_to_any('tools')):?>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools', 'action'=>'optimize'))?>"><?=__('Tools')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'crontab'))?>"><?=__('Crontab')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'import'))?>"><?=__('Import Ads')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'importUsers'))?>"><?=__('Import Users')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'optimize'))?>"><?=__('Optimize')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'sitemap'))?>"><?=__('Sitemap')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'migration'))?>"><?=__('Migration')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>"><?=__('Cache')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" data-keybinding='g c d' href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=1"><?=__('Cache')?> - <?=__('Delete all')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=2"><?=__('Cache')?> - <?=__('Delete expired')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'logs'))?>"><?=__('Logs')?></a>
                                </li>
                                <li>
                                    <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'phpinfo'))?>"><?=__('PHP Info')?></a>
                                </li>
                            <?endif?>
                        <?endif?>
                    </ul>
                </div>
            </li>
            <? if($user->is_admin() OR $user->is_moderator()):?>
                <li>
                    <a href="#"><i class="linecon li_display"></i> <span class="hidden-xs"><?=__('Panel')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level">
                        <li>
                            <a href="<?=Route::url('oc-panel',array('controller'=>'home'))?>"><?=__('Home')?></a>
                        </li>
                        <?=Theme::admin_link(__('Stats'),'stats','index','oc-panel','')?>
                        <?=Theme::admin_link(__('Updates'), 'update','index','oc-panel')?>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="linecon li_tag"></i> <span class="hidden-xs"><?=__('Classifieds')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level">
                        <?=Theme::admin_link(__('Advertisements'),'ad','','oc-panel')?>
                        <?if( in_array(core::config('general.moderation'), Model_Ad::$moderation_status)  ):  // payment with moderation?>
                            <?=Theme::admin_link(__('Moderation'),'ad','moderate','oc-panel')?>
                        <?endif?>
                        <?=Theme::admin_link(__('Categories'),'category','index','oc-panel')?>
                        <?=Theme::admin_link(__('Locations'),'location','index','oc-panel')?>
                        <?=Theme::admin_link(__('Custom Fields'), 'fields','index','oc-panel')?>
                        <?=Theme::admin_link(__('Orders'), 'order','index','oc-panel')?>
                        <?if (core::config('general.subscriptions')==1):?>
                            <?=Theme::admin_link(__('Plans'), 'plan','index','oc-panel')?>
                            <?=Theme::admin_link(__('Subscriptions'), 'subscription','index','oc-panel')?>
                        <?endif?>
                        <?=Theme::admin_link(__('Coupons'), 'coupon','index','oc-panel')?>
                        <?if (core::config('advertisement.reviews')==1):?>
                            <?=Theme::admin_link(__('Reviews'), 'review','index','oc-panel')?>
                        <?endif?>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="linecon li_note"></i> <span class="hidden-xs"><?=__('Content')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level nav-mobile-moved">
                        <?if (core::config('general.blog')==1):?>
                            <?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel')?>
                        <?endif?>
                        <?=Theme::admin_link(__('Pages'), 'content','page','oc-panel')?>
                        <?=Theme::admin_link(__('Email'), 'content','email','oc-panel')?>
                        <?=Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel')?>
                        <?if (core::config('general.faq')==1):?>
                            <?=Theme::admin_link(__('FAQ'), 'content','help','oc-panel')?>
                        <?endif?>
                        <?if(core::config('general.forums')==1):?>
                            <?=Theme::admin_link(__('Forums'),'forum','index','oc-panel')?>
                            <?=Theme::admin_link(__('Topics'), 'topic','index','oc-panel')?>
                        <?endif?>
                        <?=Theme::admin_link(__('Media'), 'cmsimages','index','oc-panel')?>
                        <li <?=(Request::current()->controller()=='map')?'class="active"':''?> >
                            <a href="<?=Route::url('oc-panel',array('controller'=>'map','action'=>'index'))?>" title="<?=__('Interactive Map')?>">
                                <span class="side-name-link"><?=__('Interactive Map')?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="linecon li_photo"></i> <span class="hidden-xs"><?=__('Appearance')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level nav-mobile-moved">
                        <?=Theme::admin_link(__('Themes'), 'theme','index','oc-panel')?>
                        <?if (Theme::has_options()):?>
                            <?=Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel')?>
                        <?endif?>
                        <?=Theme::admin_link(__('Widgets'), 'widget','index','oc-panel')?>
                        <?=Theme::admin_link(__('Menu'), 'menu','index','oc-panel')?>
                        <?=Theme::admin_link(__('Custom CSS'), 'theme','css','oc-panel')?>
                    </ul>
                </li>
                <?if ($user->has_access_to_any('settings,config')):?>
                <li>
                    <a href="#"><i class="linecon li_params"></i> <span class="hidden-xs"><?=__('Settings')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level nav-mobile-moved">
                        <?=Theme::admin_link(__('General'), 'settings','general','oc-panel')?>
                        <?=Theme::admin_link(__('Advertisement'), 'settings','form','oc-panel')?>
                        <?=Theme::admin_link(__('Email settings'), 'settings','email','oc-panel')?>
                        <?=Theme::admin_link(__('Payment'), 'settings','payment','oc-panel')?>
                        <?=Theme::admin_link(__('Plugins'), 'settings','plugins','oc-panel')?>
                        <?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel')?>
                        <?=Theme::admin_link(__('Media settings'), 'settings','image','oc-panel')?>
                        <?if (core::config('general.social_auth')):?>
                            <?=Theme::admin_link(__('Social Auth'), 'social','index','oc-panel')?>
                        <?endif?>
                    </ul>
                </li>
                <?endif?>
                <?if ($user->has_access_to_any('user,role,access')):?>
                <li>
                    <a href="#"><i class="linecon li_user"></i> <span class="hidden-xs"><?=__('Users')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level nav-mobile-moved">
                        <?=Theme::admin_link(__('Users'),'user','index','oc-panel')?>
                        <?=Theme::admin_link(__('Roles'),'role','index','oc-panel')?>
                        <?=Theme::admin_link(__('User custom Fields'), 'userfields','index','oc-panel')?>
                        <?if(core::config('general.black_list')):?>
                            <?=Theme::admin_link(__('User black list'),'pool','index','oc-panel')?>
                        <?endif?>
                        <?=Theme::admin_link(__('Subscribers'),'subscriber','index','oc-panel')?>
                    </ul>
                </li>
                <?endif?>
                <?if ($user->has_access_to_any('tools')):?>
                <li>
                    <a href="#"><i class="linecon li_lab"></i> <span class="hidden-xs"><?=__('Extra')?></span><span class="fa arrow"></span></a>
                    <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level nav-mobile-moved">
                        <?=Theme::admin_link(__('Tools'), 'tools','optimize','oc-panel')?>
                        <?=Theme::admin_link(__('Crontab'), 'crontab','index','oc-panel')?>
                        <?=Theme::admin_link(__('Import Ads'), 'import','index','oc-panel')?>
                        <?=Theme::admin_link(__('Import Users'), 'importUsers','index','oc-panel')?>
                        <li>
                            <a href="https://docs.yclas.com/" target="_blank"><?=__('I need help')?></a>
                        </li>
                    </ul>
                </li>
                <?endif?>
            <?endif?>
            <? if($user->is_translator()):?>
                <a href="#"><i class="linecon li_note"></i> <span class="hidden-xs"><?=__('Content')?></span><span class="fa arrow"></span></a>
                <ul class="flex flex-wrap list-none p-0 pl-0 mb-0 nav-second-level nav-mobile-moved">
                    <?if (core::config('general.blog')==1):?>
                        <?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel')?>
                    <?endif?>
                    <?=Theme::admin_link(__('Pages'), 'content','page','oc-panel')?>
                    <?=Theme::admin_link(__('Email'), 'content','email','oc-panel')?>
                    <?if (core::config('general.faq')==1):?>
                        <?=Theme::admin_link(__('FAQ'), 'content','help','oc-panel')?>
                    <?endif?>
                    <?=Theme::admin_link(__('Media'), 'cmsimages','index','oc-panel')?>
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
