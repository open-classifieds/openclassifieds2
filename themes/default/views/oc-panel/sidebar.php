<?php defined('SYSPATH') or die('No direct script access.');?>

<aside class="col-md-1 col-sm-1 col-xs-1 respon-left-panel">
    <div class="sidebar-nav">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-sm-1 col-xs-1 respon-left-panel">
                    <div class="panel-group <?=(core::cookie('sidebar_state') == 'collapsed')? 'mini-col':NULL?>" id="accordion">
                    <? if($user->id_role==Model_Role::ROLE_ADMIN OR $user->id_role==Model_Role::ROLE_MODERATOR):?>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-th"></span>
                                        <span class="title-txt"><?=__('Classifieds')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Advertisements'),'ad','index','oc-panel','glyphicon glyphicon-align-right')?></td></tr>
                                        <? if( in_array(core::config('general.moderation'), Model_Ad::$moderation_status)  ):  // payment with moderation?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Moderation'),'ad','moderate','oc-panel','glyphicon glyphicon-ban-circle')?></td></tr>   
                                        <? endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Categories'),'category','index','oc-panel','glyphicon  glyphicon-tags')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Locations'),'location','index','oc-panel','glyphicon  glyphicon-map-marker')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Orders'), 'order','index','oc-panel','glyphicon  glyphicon-shopping-cart')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Coupons'), 'coupon','index','oc-panel','glyphicon glyphicon-tag')?></td></tr>
                                        <?if (core::config('advertisement.reviews')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Reviews'), 'review','index','oc-panel','glyphicon glyphicon-star-empty')?></td></tr>
                                        <?endif?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="title-txt"><?=__('Content')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <?if (core::config('general.blog')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel','glyphicon glyphicon-pencil')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Page'), 'content','page','oc-panel','glyphicon glyphicon-file')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'content','email','oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <?if (core::config('general.faq')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('FAQ'), 'content','help','oc-panel',' glyphicon glyphicon-question-sign')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel','glyphicon glyphicon-globe')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <?if(core::config('general.forums')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Forums'),'forum','index','oc-panel','glyphicon glyphicon-tags')?></td></tr>
                                            <tr><td class="br"><?=Theme::admin_link(__('Topics'), 'topic','index','oc-panel','glyphicon glyphicon-pencil')?></td></tr>
                                        <?endif?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-picture"></span>
                                        <span class="title-txt"><?=__('Appearance')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Themes'), 'theme','index','oc-panel','glyphicon glyphicon-picture')?></td></tr>
                                        <?if (Theme::has_options()):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel','glyphicon  glyphicon-wrench')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Custom CSS'), 'theme','css','oc-panel','glyphicon glyphicon-sound-stereo')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Widgets'), 'widget','index','oc-panel','glyphicon glyphicon-move')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Menu'), 'menu','index','oc-panel','glyphicon glyphicon-list')?></td></tr>
                                        <tr>
                                            <td class="br">
                                                <li <?=(Request::current()->controller()=='map')?'class="active"':''?> >
                                                    <a href="<?=Route::url('oc-panel',array('controller'=>'map','action'=>'index'))?>" title="<?=__('Interactive Map')?>">
                                                        <i class="glyphicon glyphicon-map-marker"></i>
                                                        <span class="side-name-link"><?=__('Interactive Map')?></span>
                                                    </a>
                                                </li>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?if ($user->has_access_to_any('settings,config')):?>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseSettings">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-wrench"></span>
                                        <span class="title-txt"><?=__('Settings')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseSettings" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('General'), 'settings','general','oc-panel','glyphicon  glyphicon-dashboard')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Payment'), 'settings','payment','oc-panel','glyphicon  glyphicon-credit-card')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'settings','email','oc-panel','glyphicon  glyphicon-envelope')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Images'), 'settings','image','oc-panel','glyphicon  glyphicon-picture')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Advertisement'), 'settings','form','oc-panel','glyphicon  glyphicon-edit')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Custom Fields'), 'fields','index','oc-panel','glyphicon  glyphicon-plus-sign')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Social Auth'), 'social','index','oc-panel','glyphicon glyphicon-thumbs-up')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <?if ($user->has_access_to_any('user,role,access')):?>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseUser">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-user"></span>
                                        <span class="title-txt"><?=__('Users')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseUser" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table no-hide">
                                        <tr><td class="br"><?=Theme::admin_link(__('Users'),'user','index','oc-panel','glyphicon  glyphicon-user')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Roles'),'role','index','oc-panel','glyphicon  glyphicon-retweet')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Custom Fields'), 'userfields','index','oc-panel','glyphicon  glyphicon-plus-sign')?></td></tr>
                                        <?if(core::config('general.black_list')):?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Black list'),'pool','index','oc-panel','glyphicon  glyphicon-fire')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Edit profile'), 'profile','edit','oc-panel','glyphicon glyphicon-edit', NULL, FALSE)?></td></tr>
                                        <tr><td class="br">
                                            <li>
                                                <a href="<?=Route::url('profile',array('seoname'=>$user->seoname))?>">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                    <span class="side-name-link"><?=__('Public profile')?></span>
                                                </a>
                                            </li>
                                        </td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <?if ($user->has_access_to_any('tools')):?>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTools">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-filter"></span>
                                        <span class="title-txt"><?=__('Tools')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTools" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Import tool'), 'import','index','oc-panel','glyphicon  glyphicon-import')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Updates'), 'update','index','oc-panel','glyphicon  glyphicon-refresh')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Crontab'), 'crontab','index','oc-panel','glyphicon  glyphicon-calendar')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Sitemap'), 'tools','sitemap','oc-panel','glyphicon  glyphicon-list-alt')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Migration'), 'tools','migration','oc-panel','glyphicon  glyphicon-forward')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Export'), 'tools','export','oc-panel','glyphicon  glyphicon-export',NULL,FALSE)?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Optimize'), 'tools','optimize','oc-panel','glyphicon  glyphicon-compressed')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Cache'), 'tools','cache','oc-panel','glyphicon  glyphicon-cog')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Logs'), 'tools','logs','oc-panel','glyphicon  glyphicon-list-alt')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('PHP Info'), 'tools','phpinfo','oc-panel','glyphicon  glyphicon-info-sign')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                    <?endif?>
                    <? if($user->id_role==Model_Role::ROLE_TRANSLATOR):?>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="title-txt"><?=__('Content')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <?if (core::config('general.blog')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel','glyphicon glyphicon-pencil')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Page'), 'content','page','oc-panel','glyphicon glyphicon-file')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'content','email','oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <?if (core::config('general.faq')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('FAQ'), 'content','help','oc-panel',' glyphicon glyphicon-question-sign')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel','glyphicon glyphicon-globe')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?endif?>
                        <? if($user->has_access_to_any('profile')):?>
                        <div class="panel panel-sidebar">
                            <div class="panel-heading" id="menu-profile-options">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                        <i class="fa fa-angle-double-down i-right"></i>
                                        <span class="glyphicon glyphicon-align-justify"></span>
                                        <span class="title-txt"><?=__('Profile Options')?></span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse <?=(Auth::instance()->get_user()->id_role == Model_Role::ROLE_USER ? "in" : NULL)?>">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Edit profile'), 'profile','edit','oc-panel','glyphicon glyphicon-edit', NULL, FALSE)?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('My Advertisements'), 'myads','index','oc-panel','glyphicon glyphicon-bullhorn', NULL, FALSE)?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('My Payments'), 'profile','orders','oc-panel','glyphicon glyphicon-shopping-cart', NULL, FALSE)?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('My Favorites'), 'profile','favorites','oc-panel','glyphicon glyphicon-heart', NULL, FALSE)?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Stats'),'myads','stats','oc-panel','glyphicon glyphicon-align-left', NULL, FALSE)?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Subscriptions'),'profile','subscriptions','oc-panel','glyphicon glyphicon-envelope', NULL, FALSE)?></td></tr>
                                        <tr><td class="br">
                                            <li>
                                                <a href="<?=Route::url('profile',array('seoname'=>$user->seoname))?>">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                    <span class="side-name-link"><?=__('Public profile')?></span>
                                                </a>
                                            </li>
                                        </td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <div class="panel panel-sidebar collapse-menu">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a  class=" btn-colapse-sidebar">
                                        <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                        <span class="title-txt"><?=__('Collapse menu')?></span>
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
