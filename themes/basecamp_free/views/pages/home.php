<?php defined('SYSPATH') or die('No direct script access.');?>
<!-- INDEX HEAD -->
<div class="index-head">
	<div class="container">
		<h2><?=Theme::get('maintitle_banner')?></h2>
		<div class="index-head-btns">
		<?if (Core::config('advertisement.only_admin_post')!=1):?>
			<a class="btn btn-base-light btn-lg" href="<?=Route::url('post_new')?>">
				 <?=_e('Publish new ')?>
			</a>                
		<?endif?>
		<?if (!Auth::instance()->logged_in()):?>
		<a class="btn btn-base-light btn-lg" data-toggle="modal" tabindex="-1" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'register'))?>#register-modal">
			<?=_e('Register')?>
		</a>
		<?endif?>
		</div>
	</div>
</div>
<!-- // INDEX HEAD -->

<?if(core::config('advertisement.homepage_map') == 1):?>
	<?=View::factory('pages/map/home')?>
<?endif?>

<!-- MAIN CONTENT - ADS -->
<?if(core::config('advertisement.ads_in_home') != 3):?>
<div class="container">
	<div class="row">
		<div class="col-xs-12">			
			<?if(core::count($ads)):?>
			<h3 class="text-center">
				<?if(core::config('advertisement.ads_in_home') == 0):?>
					<?=_e('Latest Ads')?>
				<?elseif(core::config('advertisement.ads_in_home') == 1 OR core::config('advertisement.ads_in_home') == 4):?>
					<?=_e('Featured Ads')?>
				<?elseif(core::config('advertisement.ads_in_home') == 2):?>
					<?=_e('Popular Ads last month')?>
				<?endif?>
				<?if ($user_location) :?>
					<small><?=$user_location->name?></small>
				<?endif?>
			</h3>
			<div class="home_grid clearfix">
				<ul class="ad_squares">
				<?$i=0; foreach($ads as $ad):?>
					<li class="c_ad_block">
						<div class="ad_block_inner">
							<a href="<?=Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>" title="<?=$ad->title?>" class="min-h">
							<?if($ad->get_first_image()!== NULL):?>
								<img src="<?=Core::imagefly($ad->get_first_image('image'),300,300)?>" alt="<?=HTML::chars($ad->title)?>">
							<?else:?>
								<img data-src="holder.js/200x200?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->name, 'size' => 14, 'auto' => 'yes')))?>" alt="<?=HTML::chars($ad->title)?>"> 
							<?endif?>
							<?if ($ad->price>0):?>
								<span class="ad_price"> <?=i18n::money_format( $ad->price, $ad->currency())?></span>
							<?elseif (($ad->price==0 OR $ad->price == NULL) AND core::config('advertisement.free')==1):?>
								<span class="ad_price"><?=_e('Free');?></span>
							<?else:?>
								<span class="ad_price"><?=_e('Check Listing');?></span>
							<?endif?>
							</a>
						</div>
					</li>
				<?endforeach?>
				</ul>
			</div>
			<?endif?>
			
		</div>
	</div>
</div>
<?endif?>
<!-- // MAIN CONTENT - ADS -->
<!-- MAIN CONTENT - GET STARTED -->
<div class="post-ad-banner color-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-10">
				<div class="post-ad-banner-text">
					<?=Theme::get('maintitle_lowerbanner')?>
				</div>	
			</div>
			<div class="col-xs-2 text-right post-ad-banner-btn">
				<a class="btn btn-base-light btn-lg" href="<?=Route::url('post_new')?>">
					<?=_e('Publish new ')?>
				</a>  
			</div>
		</div>
	</div>
</div>
<!-- // MAIN CONTENT - GET STARTED -->
<br>
<br>
<!-- MAIN CONTENT - CATEGORIES -->
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="home_cats">
				<div class="clearfix">
					<div class="row">
					<?$i=0; foreach($categs as $c):?>
						<?if($c['id_category_parent'] == 1 AND $c['id_category'] != 1 AND ! in_array($c['id_category'], $hide_categories)):?>
							<div class="col-xs-4 col-sm-4 col-md-4">
								<div class="panel panel-home-categories">
									<div class="panel-heading">
										<a title="<?=HTML::chars((strip_tags($c['description'])!=='')?strip_tags($c['description']):$c['name'])?>" href="<?=Route::url('list', array('category'=>$c['seoname'], 'location'=>$user_location ? $user_location->seoname : NULL))?>"><?=mb_strtoupper($c['name']);?>
										<?if (Theme::get('category_badge')!=1) : ?>
											 (<?=number_format($c['count'])?>)</a>
										<?endif?>
									</div>
									<div class="panel-body">
										<ul class="list-group">
										<?$ci=0; foreach($categs as $chi):?>
											<?if($chi['id_category_parent'] == $c['id_category'] AND ! in_array($chi['id_category'], $hide_categories)):?>
												<?if ($ci < 3):?>
													<li class="list-group-item">
														<a title="<?=HTML::chars($chi['name'])?>" href="<?=Route::url('list', array('category'=>$chi['seoname'], 'location'=>$user_location ? $user_location->seoname : NULL))?>">
														<?if (Theme::get('category_badge')!=1) : ?>
															<span class="pull-right badge badge-success"><?=number_format($chi['count'])?></span>
														<?endif?><?=$chi['name'];?>
														</a>
													</li>
												<?endif?>
												<?$ci++; if($ci == 3):?>
													<li class="list-group-item">
														<a role="button" 
                                                            class="show-all-categories"
                                                            data-cat-id="<?=$c['id_category']?>">
                                                            <?=_e("See all categories")?> <span class="glyphicon glyphicon-chevron-right pull-right"></span>
                                                        </a>
													</li>
												<?endif?>
											<?endif?>
										<?endforeach?>
										</ul>
									</div>
								</div>
							</div>
							<? $i++; if ($i%3 == 0) echo '<div class="clear"></div>';?>
						<?endif?>
					<?endforeach?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?if(core::config('advertisement.homepage_map') == 2):?>
	<?=View::factory('pages/map/home')?>
<?endif?>

<div id="modalAllCategories" class="modal fade" tabindex="-1" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'categories'))?>">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- // MAIN CONTENT - CATEGORIES -->

<br>
<br>

<?if(core::config('general.auto_locate') AND ! Cookie::get('user_location')):?>
	<input type="hidden" name="auto_locate" value="<?=core::config('general.auto_locate')?>">
	<?if(core::count($auto_locats) > 0):?>
		<div class="modal fade" id="auto-locations" tabindex="-1" role="dialog" aria-labelledby="autoLocations" aria-hidden="true">
			<div class="modal-dialog	modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 id="autoLocations" class="modal-title text-center"><?=_e('Please choose your closest location')?></h4>
					</div>
					<div class="modal-body">
						<div class="list-group">
							<?foreach($auto_locats as $loc):?>
								<a href="<?=Route::url('default')?>" class="list-group-item" data-id="<?=$loc->id_location?>"><span class="pull-right"><span class="glyphicon glyphicon-chevron-right"></span></span> <?=$loc->name?> (<?=i18n::format_measurement($loc->distance)?>)</a>
							<?endforeach?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?endif?>
<?endif?>