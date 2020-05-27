<?php defined('SYSPATH') or die('No direct script access.');?>

<h1 class="page-header page-title"><?=__('Edit Menu')?> <?=$menu_data['title']?></h1>
<hr>

<div class="flex flex-wrap">
    <div class="md:w-2/3 pr-4 pl-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="relative inline-flex align-middle text-blue-100 bg-blue-500 hover:bg-blue-400 pull-right">
                    <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Menu type')?></button>
                    <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400  inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1" data-toggle="relative">
                        <span class="caret"></span>
                    </button>
                    <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded" id="menu_type">
                        <!-- dropdown menu links -->
                        <li><a class="custom"><?=__('Custom')?></a></li>
                        <li><a class="categories"><?=__('Categories')?></a></li>
                        <li><a class="default"><?=__('Default')?></a></li>
                    </ul>
                </div>
                
                <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'update','id'=>$name))?>">
                <!-- drop down selector -->
                <div class="mb-4" style="display:none;" id="categories-group">
                    <?= FORM::label('category', __('Category'), array('class'=>'control-label col-sm-1', 'for'=>'category' ))?> 
                    <div class="sm:w-1/3 pr-4 pl-4">
                    <div class="accordion">
                
                    <?function lili3($item, $key,$cats){?>
                        <div class="accordion-group">
                            <div class="accordion-heading"> 
                
                                <?if (core::count($item)>0):?>
                                    <div class="radio">
                                    <label>
                                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" data-name="radio_<?=$cats[$key]['name']?>" class="menu_category"  value="<?=$cats[$key]['id']?>"> 
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" data-toggle="collapse" type="button"  
                                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                            <i class=" glyphicon   glyphicon-plus"></i> <?=$cats[$key]['name']?>
                                        </a>
                                    </label>
                                    </div>
                                    
                                <?else:?>
                                    <div class="radio">
                                    <label>
                                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" data-name="radio_<?=$cats[$key]['name']?>" class="menu_category"  value="<?=$cats[$key]['id']?>"> 
                                    
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-xs text-blue-100 bg-blue-500 hover:bg-blue-400" data-toggle="collapse" type="button"  
                                            data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                            <?=$cats[$key]['name']?>
                                        </a>
                                    </label>
                                    </div>
                                <?endif?>
                            </div>
                
                            <?if (core::count($item)>0):?>
                                <div id="acc_<?=$cats[$key]['seoname']?>" 
                                    class="accordion-body collapse <?=($cats[$key]['seoname']==:get('category'))?'in':''?>">
                                    <div class="accordion-inner">
                                        <? if (is_array($item)) array_walk($item, 'lili3', $cats);?>
                                    </div>
                                </div>
                            <?endif?>
                
                        </div>
                    <?}array_walk($order_categories, 'lili3',$categories);?>
                
                    </div>
                    </div>
                </div>
                
                <div class="mb-4"  id="default-group" style="display:none;">
                    <?= FORM::label('default_links_label', __('Default links'), array('class'=>'control-label col-sm-1', 'for'=>'default_links' ))?>
                    <div class="sm:w-1/3 pr-4 pl-4"> 
                        <div class="accordion" >
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_home"  name="home" data-url="" data-icon="glyphicon-home glyphicon" value="home">    
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" type="button"  >                    
                                             <?=__('Home')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_listing" name="listing" data-url="all" data-icon="glyphicon glyphicon-list" value="listing">
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" type="button"  >                    
                                             <?=__('listing')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_search" name="search" data-url="search.html" data-icon="glyphicon glyphicon-search" value="search">
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" type="button"  >                    
                                             <?=__('Search')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_contact" name="contact" data-url="contact.html" data-icon="glyphicon glyphicon-envelope" value="contact">
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" type="button"  >                    
                                             <?=__('contact')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_rss" name="rss" data-url="rss.xml" data-icon="glyphicon glyphicon-signal" value="rss">
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" type="button"  >                    
                                             <?=__('rss')?>
                                        </a>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                    <input type="radio" class="default_links" id="radio_map" name="map" data-url="map.html" data-icon="glyphicon glyphicon-globe" value="map">
                                        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 btn-xs" type="button"  >                    
                                             <?=__('map')?>
                                        </a>
                                    </label>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="control-label sm:w-1/6 pr-4 pl-4"><?=__('Title')?></label>
                    <div class="sm:w-1/3 pr-4 pl-4">
                        <input class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" type="text" name="title" value="<?=$menu_data['title']?>" placeholder="<?=__('Title')?>" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="control-label sm:w-1/6 pr-4 pl-4"><?=__('Url')?></label>
                    <div class="sm:w-1/3 pr-4 pl-4">
                        <input class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" type="url" id="url" name="url" value="<?=$menu_data['url']?>" placeholder="http://somedomain.com" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <?= FORM::label('target', __('Target'), array('class'=>'control-label col-sm-1', 'for'=>'target' ))?>
                    <div class="sm:w-1/3 pr-4 pl-4">
                    <?= FORM::select('target', array('_self' => '_self', '_blank' => '_blank', '_parent' => '_parent', '_top' => '_top'), $menu_data['target'], array('class' => 'form-control', 'id' => 'target') )?>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="control-label sm:w-1/6 pr-4 pl-4"><a target="_blank" href="http://getbootstrap.com/components/#glyphicons"><?=__('Icon')?></a></label>
                    <div class="sm:w-1/3 pr-4 pl-4">
                        <input class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded icon-picker" type="text" name="icon" value="<?=$menu_data['icon']?>">
                    </div>
                </div>
                
                <div class="form-actions">
                <a href="<?=Route::url('oc-panel',array('controller'=>'menu'))?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default ajax-load" title="<?=__('Cancel')?>"><?=__('Cancel')?></a>
                <button type="submit" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Save')?></button>
                </div>
                          
                
                </form>
            </div>
        </div>
    </div>
</div>