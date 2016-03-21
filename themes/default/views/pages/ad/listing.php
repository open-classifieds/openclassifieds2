<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <?if ($category!==NULL):?>
        <h1><?=$category->name?></h1>
    <?elseif ($location!==NULL):?>
        <h1><?=$location->name?></h1>
    <?else:?>
        <h1><?=__('Listings')?></h1>
    <?endif?>
</div>

<div class="well" id="recomentadion">
    <?if (Controller::$image!==NULL AND Theme::get('hide_description_icon')!=1):?>
        <img src="<?=Controller::$image?>" class="img-responsive" alt="<?=($category!==NULL) ? HTML::chars($category->name) : (($location!==NULL AND $category===NULL) ? HTML::chars($location->name) : NULL)?>">
    <?endif?>

    <p>
        <?if ($category!==NULL):?>
            <?=$category->description?> 
        <?elseif ($location!==NULL):?>
            <?=$location->description?>
        <?endif?>
    </p>
    
    <?if (Core::config('advertisement.only_admin_post')!=1):?>
        <i class="glyphicon glyphicon-pencil"></i> 
        <a title="<?=__('New Advertisement')?>" 
            href="<?=Route::url('post_new')?>?category=<?=($category!==NULL)?$category->seoname:''?>&location=<?=($location!==NULL)?$location->seoname:''?>">
            <?=__('Publish new advertisement')?>
        </a>
    <?endif?>
</div><!--end of recomentadion-->

<?if(count($ads)):?>
    <div class="btn-group pull-right">
        <?if(core::config('general.auto_locate')):?>
            <button
                class="btn btn-sm btn-default <?=core::request('userpos') == 1 ? 'active' : NULL?>"
                id="myLocationBtn"
                type="button"
                data-toggle="modal"
                data-target="#myLocation"
                data-marker-title="<?=__('My Location')?>"
                data-marker-error="<?=__('Cannot determine address at this location.')?>"
                data-href="?<?=http_build_query(['userpos' => 1] + Request::current()->query())?>">
                <i class="glyphicon glyphicon-map-marker"></i> <?=sprintf(__('%s from you'), i18n::format_measurement(Core::cookie('mydistance', Core::config('advertisement.auto_locate_distance', 2))))?>
            </button>
        <?endif?>
        <?if (core::config('advertisement.map')==1):?>
            <a href="#"
                class="btn btn-default btn-sm"
                data-toggle="modal" data-target="#listingMap">
                <span class="glyphicon glyphicon-globe"></span> <?=__('Map')?>
            </a>
        <?endif?>
        <div class="btn-group">
          	<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	            <?=__('Show').' '.core::request('items_per_page').' '.__('items per page')?> <span class="caret"></span>
          	</button>
          	<ul class="dropdown-menu dropdown-menu-right" role="menu" id="show-list">
	            <li><a href="?<?=http_build_query(['items_per_page' => '5'] + Request::current()->query())?>">  5 <?=__('per page')?></a></li>
	            <li><a href="?<?=http_build_query(['items_per_page' => '10'] + Request::current()->query())?>"> 10 <?=__('per page')?></a></li>
	            <li><a href="?<?=http_build_query(['items_per_page' => '20'] + Request::current()->query())?>"> 20 <?=__('per page')?></a></li>
	            <li><a href="?<?=http_build_query(['items_per_page' => '50'] + Request::current()->query())?>"> 50 <?=__('per page')?></a></li>
	            <li><a href="?<?=http_build_query(['items_per_page' => '100'] + Request::current()->query())?>">100 <?=__('per page')?></a></li>
          	</ul>
        </div>
        <button type="button" id="sort" data-sort="<?=core::request('sort',core::config('advertisement.sort_by'))?>" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-list-alt"></span> <?=__('Sort')?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" id="sort-list">
            <li><a href="?<?=http_build_query(['sort' => 'title-asc'] + Request::current()->query())?>"><?=__('Name (A-Z)')?></a></li>
            <li><a href="?<?=http_build_query(['sort' => 'title-desc'] + Request::current()->query())?>"><?=__('Name (Z-A)')?></a></li>
            <?if(core::config('advertisement.price')!=FALSE):?>
                <li><a href="?<?=http_build_query(['sort' => 'price-asc'] + Request::current()->query())?>"><?=__('Price (Low)')?></a></li>
                <li><a href="?<?=http_build_query(['sort' => 'price-desc'] + Request::current()->query())?>"><?=__('Price (High)')?></a></li>
            <?endif?>
            <li><a href="?<?=http_build_query(['sort' => 'featured'] + Request::current()->query())?>"><?=__('Featured')?></a></li>
            <li><a href="?<?=http_build_query(['sort' => 'favorited'] + Request::current()->query())?>"><?=__('Favorited')?></a></li>
            <?if(core::config('general.auto_locate')):?>
                <li><a href="?<?=http_build_query(['sort' => 'distance'] + Request::current()->query())?>" id="sort-distance"><?=__('Distance')?></a></li>
            <?endif?>
            <li><a href="?<?=http_build_query(['sort' => 'published-desc'] + Request::current()->query())?>"><?=__('Newest')?></a></li>
            <li><a href="?<?=http_build_query(['sort' => 'published-asc'] + Request::current()->query())?>"><?=__('Oldest')?></a></li>
        </ul>
    </div>
    <div class="clearfix"></div>
    
  <?foreach($ads as $ad ):?>
      <?if($ad->featured >= Date::unix2mysql(time())):?>
          <article class="list well clearfix featured ">
              <span class="label label-danger pull-right"><?=__('Featured')?></span>
      <?else:?>
          <article class="list well clearfix">
      <?endif?>
          <div class="pull-right favorite" id="fav-<?=$ad->id_ad?>">
              <?if (Auth::instance()->logged_in()):?>
                  <?$fav = Model_Favorite::is_favorite($user,$ad);?>
                  <a data-id="fav-<?=$ad->id_ad?>" class="add-favorite <?=($fav)?'remove-favorite':''?>" title="<?=__('Add to Favorites')?>" href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'favorites','id'=>$ad->id_ad))?>">
                      <i class="glyphicon glyphicon-heart<?=($fav)?'':'-empty'?>"></i>
                  </a>
              <?else:?>
                  <a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                      <i class="glyphicon glyphicon-heart-empty"></i>
                  </a>
              <?endif?>
          </div>
          
          <?if($ad->id_location != 1):?>
              <a href="<?=Route::url('list',array('location'=>$ad->location->seoname))?>" title="<?=HTML::chars($ad->location->name)?>">
                  <span class="label label-default"><?=$ad->location->name?></span>
              </a>
          <?endif?>
          
          <h2>
              <a title="<?=HTML::chars($ad->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                  <?=$ad->title?>
              </a>
          </h2>
          
          <div class="picture">
              <a class="pull-left" title="<?=HTML::chars($ad->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                  <figure>
                      <?if($ad->get_first_image() !== NULL):?>
                          <img src="<?=Core::imagefly($ad->get_first_image(),150,150)?>" alt="<?=HTML::chars($ad->title)?>" />
                      <?elseif(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                          <img src="<?=Core::imagefly($icon_src,150,150)?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                      <?elseif(( $icon_src = $ad->location->get_icon() )!==FALSE ):?>
                          <img src="<?=Core::imagefly($icon_src,150,150)?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                      <?else:?>
                          <img data-src="holder.js/150x150?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->name, 'size' => 14, 'auto' => 'yes')))?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>"> 
                      <?endif?>
                  </figure>
              </a>
          </div>
          
          <ul>
              <?if (core::request('sort') == 'distance' AND Model_User::get_userlatlng()) :?>
                  <li><b><?=__('Distance');?>:</b> <?=i18n::format_measurement($ad->distance)?></li>
              <?endif?>
              <?if ($ad->published!=0){?>
                  <li><b><?=__('Publish Date');?>:</b> <?=Date::format($ad->published, core::config('general.date_format'))?></li>
              <? }?>
              <?if ($ad->price!=0){?>
                  <li class="price"><?=__('Price');?>: <b><?=i18n::money_format( $ad->price)?></b></li>
              <?}?>  
              <?if ($ad->price==0 AND core::config('advertisement.free')==1){?>
                  <li class="price"><?=__('Price');?>: <b><?=__('Free');?></b></li>
              <? }?>  
          </ul>
       
          <?if(core::config('advertisement.description')!=FALSE):?>
            <p><?=Text::limit_chars(Text::removebbcode($ad->description), 255, NULL, TRUE);?></p>
          <?endif?>
          
          <a title="<?=HTML::chars($ad->seotitle);?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><i class="glyphicon glyphicon-share"></i><?=__('Read more')?></a>
          <?if ($user !== NULL AND ($user->id_role == Model_Role::ROLE_ADMIN OR $user->id_role == Model_Role::ROLE_MODERATOR )):?>
              <br />
              <div class="toolbar btn btn-primary btn-xs"><i class="glyphicon glyphicon-cog"></i>
                  <div id="user-toolbar-options<?=$ad->id_ad?>" class="hide user-toolbar-options">
                      <a class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><i class="glyphicon glyphicon-edit"></i> <?=__("Edit");?></a> |
                      <a class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>" 
                          onclick="return confirm('<?=__('Deactivate?')?>');"><i class="glyphicon glyphicon-off"></i><?=__("Deactivate");?>
                      </a> |
                      <a class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>" 
                          onclick="return confirm('<?=__('Spam?')?>');"><i class="glyphicon glyphicon-fire"></i><?=__("Spam");?>
                      </a> |
                      <a class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>" 
                          onclick="return confirm('<?=__('Delete?')?>');"><i class="glyphicon glyphicon-remove"></i><?=__("Delete");?>
                      </a>

                  </div>
              </div>
          <?elseif($user !== NULL && $user->id_user == $ad->id_user):?>

          <br/>
          <div class="toolbar btn btn-primary btn-xs"><i class="glyphicon glyphicon-cog"></i>
              <div id="user-toolbar-options<?=$ad->id_ad?>" class="hide user-toolbar-options">
                  <a class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><i class="glyphicon glyphicon-edit"></i><?=__("Edit");?></a> |
                  <a class="btn btn-primary btn-xs" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'deactivate','id'=>$ad->id_ad))?>" 
                      onclick="return confirm('<?=__('Deactivate?')?>');"><i class="glyphicon glyphicon-off"></i><?=__("Deactivate");?>
                  </a>
              </div>
          </div>
          <?endif?>
      </article>
  
  <?endforeach?>

  <?=$pagination?>
 <?elseif (count($ads) == 0):?>
    <?if(core::config('general.auto_locate') AND core::request('userpos') == 1):?>
        <div class="btn-group pull-right">
            <button
                class="btn btn-sm btn-default <?=core::request('userpos') == 1 ? 'active' : NULL?>"
                id="myLocationBtn"
                type="button"
                data-toggle="modal"
                data-target="#myLocation"
                data-href="?<?=http_build_query(['userpos' => 1] + Request::current()->query())?>">
                <i class="glyphicon glyphicon-map-marker"></i> <?=sprintf(__('%s from you'), i18n::format_measurement(Core::config('advertisement.auto_locate_distance', 1)))?>
            </button>
        </div>
        <div class="clearfix"></div>
    <?endif?>
 <!-- Case when we dont have ads for specific category / location -->
  <div class="page-header">
      <h3><?=__('We do not have any advertisements in this category')?></h3>
  </div>
<?endif?>

<?if(core::config('general.auto_locate')):?>
    <div class="modal fade" id="myLocation" tabindex="-1" role="dialog" aria-labelledby="myLocationLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-distance btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="label-icon"><?=i18n::format_measurement(Core::cookie('mydistance', Core::config('advertisement.auto_locate_distance', 2)))?></span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="#" data-value="2"><?=i18n::format_measurement(2)?></a>
                                </li>
                                <li>
                                    <a href="#" data-value="5"><?=i18n::format_measurement(5)?></a>
                                </li>
                                <li>
                                    <a href="#" data-value="10"><?=i18n::format_measurement(10)?></a>
                                </li>
                                <li>
                                    <a href="#" data-value="20"><?=i18n::format_measurement(20)?></a>
                                </li>
                                <li>
                                    <a href="#" data-value="50"><?=i18n::format_measurement(50)?></a>
                                </li>
                                <li>
                                    <a href="#" data-value="250"><?=i18n::format_measurement(250)?></a>
                                </li>
                                <li>
                                    <a href="#" data-value="500"><?=i18n::format_measurement(500)?></a>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="distance" id="myDistance" value="<?=Core::cookie('mydistance', Core::config('advertisement.auto_locate_distance', 2))?>" disabled>
                        <input type="hidden" name="latitude" id="myLatitude" value="" disabled>
                        <input type="hidden" name="longitude" id="myLongitude" value="" disabled>
                        <?=FORM::input('myAddress', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'myAddress', 'placeholder'=>__('Where do you want to search?')))?>
                        <span class="input-group-btn">
                            <button id="setMyLocation" class="btn btn-default" type="button"><?=__('Ok')?></button>
                        </span>
                    </div>
                    <br>
                    <div id="mapCanvas"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Close')?></button>
                    <?if (core::request('userpos') == 1) :?>
                        <a class="btn btn-danger" href="?<?=http_build_query(['userpos' => NULL] + Request::current()->query())?>"><?=__('Remove')?></a>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
    <?if (core::config('advertisement.map')==1):?>
        <?=View::factory('pages/ad/listing_map', compact('ads'))?>
    <?endif?>
<?endif?>
