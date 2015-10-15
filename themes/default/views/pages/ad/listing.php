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
        <?if (core::config('advertisement.map')==1):?>
            <a href="<?=Route::url('map')?>?category=<?=Model_Category::current()->loaded()?Model_Category::current()->seoname:NULL?>&location=<?=Model_Location::current()->loaded()?Model_Location::current()->seoname:NULL?>" 
                class="btn btn-default btn-sm <?=(core::cookie('list/grid')==0)?'active':''?>">
                <span class="glyphicon glyphicon-globe"></span> <?=__('Map')?>
            </a>
        <?endif?>
        <button type="button" id="sort" data-sort="<?=core::request('sort')?>" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
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
                          <img src="<?=$ad->get_first_image()?>" alt="<?=HTML::chars($ad->title)?>" />
                      <?elseif(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                          <img src="<?=$icon_src?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                      <?elseif(( $icon_src = $ad->location->get_icon() )!==FALSE ):?>
                          <img src="<?=$icon_src?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                      <?else:?>
                          <img data-src="holder.js/<?=core::config('image.width_thumb')?>x<?=core::config('image.height_thumb')?>?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->name, 'size' => 14, 'auto' => 'yes')))?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>"> 
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
          </ul>
       
          <?if(core::config('advertisement.description')!=FALSE):?>
            <p><?=Text::limit_chars(Text::removebbcode($ad->description), 255, NULL, TRUE);?></p>
          <?endif?>
          
          <a title="<?=HTML::chars($ad->seotitle);?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><i class="glyphicon glyphicon-share"></i><?=__('Read more')?></a>
          <?if ($user !== NULL && $user->id_role == Model_Role::ROLE_ADMIN):?>
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
 <!-- Case when we dont have ads for specific category / location -->
  <div class="page-header">
      <h3><?=__('We do not have any advertisements in this category')?></h3>
  </div>
<?endif?>
