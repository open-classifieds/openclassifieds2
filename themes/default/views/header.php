<?php defined('SYSPATH') or die('No direct script access.');?>
<header class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu-panel">
                <span class="sr-only"><?=__('Toggle navigation')?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=Route::url('default')?>">
                <h1><?=core::config('general.site_name')?></h1>
            </a>
        </div>
        <?
            $cats = Model_Category::get_category_count();
            $loc_seoname = NULL;
            
            if (Model_Location::current()->loaded())
                $loc_seoname = Model_Location::current()->seoname;
        ?>
        <div class="collapse navbar-collapse" id="mobile-menu-panel">
            <ul class="nav navbar-nav">
                <?if (class_exists('Menu') AND count( $menus = Menu::get() )>0 ):?>
                    <?foreach ($menus as $menu => $data):?>
                        <li class="<?=(Request::current()->uri()==$data['url'])?'active':''?>" >
                        <a href="<?=$data['url']?>" target="<?=$data['target']?>">
                            <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i> <?endif?>
                            <?=$data['title']?></a> 
                        </li>
                    <?endforeach?>
                <?else:?>
                    <?=Theme::nav_link(__('Listing'),'ad', 'glyphicon glyphicon-list' ,'listing', 'list')?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=__('Categories')?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?foreach($cats as $c ):?>
                                <?if($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" title="<?=HTML::chars($c['seoname'])?>" href="<?=Route::url('list', array('category'=>$c['seoname'],'location'=>$loc_seoname))?>">
                                            <?=$c['name']?>
                                        </a>
                                        <?if($c['id_category_parent'] == 1):?>
                                            <?$i = 0; foreach($cats as $chi):?>
                                                <?if($chi['id_category_parent'] == $c['id_category']):?>
                                                    <?$i++; if($i == 1):?>
                                                        <ul class="dropdown-menu">
                                                    <?endif?>
                                                    <li>
                                                        <a title="<?=HTML::chars($chi['name'])?>" href="<?=Route::url('list', array('category'=>$chi['seoname'],'location'=>$loc_seoname))?>">
                                                            <?if (Theme::get('category_badge') != 1) : ?>
                                                                <span class="pull-right badge badge-success"><?=number_format($chi['count'])?></span>
                                                            <?endif?>
                                                            <span class="<?=Theme::get('category_badge') != 1 ? 'badged-name' : NULL?>"><?=$chi['name']?></span>
                                                        </a>
                                                    </li>
                                                <?endif?>
                                            <?endforeach?>
                                            <?if($i > 0):?>
                                                </ul>
                                            <?endif?>
                                        <?endif?>
                                    </li>
                                <?endif?>
                            <?endforeach?>
                        </ul>
                    </li>
                    <?if (core::config('general.blog')==1):?>
                        <?=Theme::nav_link(__('Blog'),'blog','','index','blog')?>
                    <?endif?>
                    <?if (core::config('general.faq')==1):?>
                        <?=Theme::nav_link(__('FAQ'),'faq','','index','faq')?>
                    <?endif?>
                    <?if (core::config('general.forums')==1):?>
                        <?=Theme::nav_link('','forum','glyphicon glyphicon-tag','index','forum-home')?>
                    <?endif?>
                    <?=Theme::nav_link('','ad', 'glyphicon glyphicon-search ', 'advanced_search', 'search')?>
                    <?if (core::config('advertisement.map')==1):?>
                        <?=Theme::nav_link('','map', 'glyphicon glyphicon-globe ', 'index', 'map')?>
                    <?endif?>
                    <?=Theme::nav_link('','contact', 'glyphicon glyphicon-envelope ', 'index', 'contact')?>
                <?endif?>
            </ul>
            <div class="pull-right navbar-btn">
                <?=View::factory('widget_login')?>
                <?if (Core::config('advertisement.only_admin_post')!=1):?>
                    <a class="btn btn-danger" href="<?=Route::url('post_new')?>">
                        <i class="glyphicon glyphicon-pencil glyphicon"></i>
                        <?=__('Publish new ')?>
                    </a>                
                <?endif?>
            </div>
        </div><!--/.nav-collapse -->
    </div>
</header>
<?if (!Auth::instance()->logged_in()):?>
    <div id="login-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" >&times;</a>
                    <h3><?=__('Login')?></h3>
                </div>
                <div class="modal-body">
                    <?=View::factory('pages/auth/login-form')?>
                </div>
            </div>
        </div>
    </div>
    <div id="forgot-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" >&times;</a>
                    <h3><?=__('Forgot password')?></h3>
                </div>
                <div class="modal-body">
                    <?=View::factory('pages/auth/forgot-form')?>
                </div>
            </div>
        </div>
    </div>
     <div id="register-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <a class="close" data-dismiss="modal" >&times;</a>
                  <h3><?=__('Register')?></h3>
                </div>
                <div class="modal-body">
                    <?=View::factory('pages/auth/register-form')?>
                </div>
            </div>
        </div>
    </div>
<?endif?>