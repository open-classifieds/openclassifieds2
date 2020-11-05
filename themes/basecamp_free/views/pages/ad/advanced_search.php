<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pad_10tb">
<div class="container">
	<div class="row">
	<?if (Request::current()->query()):?>
		<?if (core::count($ads)>0):?>
		<div class="<?=(Theme::get('sidebar_position')!='none')?'col-xs-9':'col-xs-12'?> <?=(Theme::get('sidebar_position')=='left')?'pull-right':'pull-left'?>">
			<div class="page-header">
				<h3>
					<?if (core::get('title')) :?>
						<?=($total_ads == 1) ? sprintf(__('%d advertisement for %s'), $total_ads, core::get('title')) : sprintf(__('%d advertisements for %s'), $total_ads, core::get('title'))?>
					<?else:?>
						<?=_e('Search results')?>
					<?endif?>
				</h3>
			</div>

			<!-- Dropdown Edit Search -->
			<div class="drop-edit-search">
			<a class="collapse_searchform_expand add-transition" role="button" data-toggle="collapse" href="#collapsAdvSearchForm" aria-expanded="false" aria-controls="collapsAdvSearchForm">
				Edit Search <i class="fa fa-caret-square-o-down"></i>
			</a>

			<div class="collapse" id="collapsAdvSearchForm">
			<div class="modify_search clearfix">
			<?= FORM::open(Route::url('search'), array('class'=>'form-inline', 'method'=>'GET', 'action'=>''))?>
			<?=Form::errors()?>
			<fieldset>
				<div class="form-group col-xs-12 col-sm-4 ">
					<?= FORM::label('advertisement', _e('Advertisement Title'), array('class'=>'', 'for'=>'advertisement'))?>
						<div class="control mr-30">
							<?if(Core::config('general.algolia_search') == 1):?>
	                            <?=View::factory('pages/algolia/autocomplete_ad')?>
	                        <?else:?>
	                            <input type="text" id="title" name="title" class="form-control" value="<?=HTML::chars(core::get('title'))?>" placeholder="<?=__('Title')?>">
	                        <?endif?>
						</div>
				</div>
				<div class="form-group col-xs-12 col-sm-4 ">
					<?= FORM::label('category', _e('Category'), array('class'=>'', 'for'=>'category' ))?>
						<div class="control mr-30">
							<select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="category<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="category" class="form-control" data-placeholder="<?=__('Category')?>">
							<?if ( ! core::config('general.search_multi_catloc')) :?>
								<option value=""><?=__('Category')?></option>
							<?endif?>
							<?function lili($item, $key,$cats){?>
								<?if (core::config('general.search_multi_catloc')):?>
									<option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(is_array(core::request('category')) AND in_array($cats[$key]['seoname'], core::request('category')))?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
								<?else:?>
									<option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
								<?endif?>
								<?if (core::count($item)>0):?>
								<optgroup label="<?=$cats[$key]['translate_name']?>">
									<? if (is_array($item)) array_walk($item, 'lili', $cats);?>
									</optgroup>
								<?endif?>
							<?}array_walk($order_categories, 'lili',$categories);?>
							</select>
						</div>
				</div>
				<?if(core::config('advertisement.location') != FALSE AND core::count($locations) > 1 AND core::count($locations) < 100 ):?>
					<div class="form-group col-xs-12 col-sm-4">
						<?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
						<div class="control mr-30">
							<select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location" class="form-control" data-placeholder="<?=__('Location')?>">
							<?if ( ! core::config('general.search_multi_catloc')) :?>
								<option value=""><?=__('Location')?></option>
							<?endif?>
							<?function lolo($item, $key,$locs){?>
								<?if (core::config('general.search_multi_catloc')):?>
									<option value="<?=$locs[$key]['seoname']?>" <?=(is_array(core::request('location')) AND in_array($locs[$key]['seoname'], core::request('location')))?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
								<?else:?>
									<option value="<?=$locs[$key]['seoname']?>" <?=(core::request('location') == $locs[$key]['seoname'])?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
								<?endif?>
								<?if (core::count($item)>0):?>
								<optgroup label="<?=$locs[$key]['translate_name']?>">
									<? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
									</optgroup>
								<?endif?>
							<?}array_walk($order_locations, 'lolo',$locations);?>
							</select>
						</div>
					</div>
                <?elseif(core::config('advertisement.location') != FALSE AND core::count($locations) > 1):?>
                    <div class="form-group col-xs-12 col-sm-4">
						<?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
						<div class="control mr-30">
                            <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location-search" class="form-control ajax-location-search" data-placeholder="<?=__('Location')?>" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                <? if ( ! core::config('general.search_multi_catloc')) : ?>
                                    <option value=""><?=__('Location')?></option>
                                <? endif ?>
                                <? if (is_array($location_filter)) : ?>
                                    <? foreach ($location_filter as $location) : ?>
                                        <option value="<?= $location->seoname ?>" selected><?= $location->name ?></option>
                                    <? endforeach ?>
                                <? elseif ($location_filter) : ?>
                                    <option value="<?= $location_filter->seoname ?>" selected><?= $location_filter->name ?></option>
                                <? endif ?>
                            </select>
						</div>
					</div>
				<?endif?>

				<?if(core::config('advertisement.price')):?>
					<div class="form-group col-xs-6 col-sm-4">
						<label class="" for="price-min"><?=_e('Price from')?> </label>
							<div class="control mr-30">
								<input type="text" id="price-min" name="price-min" class="form-control" value="<?=HTML::chars(core::get('price-min'))?>" placeholder="<?=__('Price from')?>">
							</div>
					</div>
					<div class="form-group col-xs-6 col-sm-4">
						<label class="" for="price-max"><?=_e('Price to')?></label>
							<div class="control mr-30">
								<input type="text" id="price-max" name="price-max" class="form-control" value="<?=HTML::chars(core::get('price-max'))?>" placeholder="<?=__('to')?>">
							</div>
					</div>
				<?endif?>

				<div class="form-group col-xs-12 col-sm-4 text-center">
					<label>&nbsp;</label>
					<div>
						<?= FORM::button('submit', _e('Search'), array('type'=>'submit', 'class'=>'btn btn-base-dark', 'action'=>Route::url('search')))?>
					</div>
				</div>
			</fieldset>
			<?= FORM::close()?>
			</div>
			</div>
			</div>
		</div>

		<?=View::factory('pages/ad/listing',array('pagination'=>$pagination,'ads'=>$ads,'category'=>NULL, 'location'=>NULL, 'user'=>$user, 'featured'=>NULL))?>

		<?else:?>

		<div class="col-md-9 col-sm-12 col-xs-12">
			<div class="page-header">
				<h3><?=_e('Search results')?></h3>
			</div>

			<a class="collapse_searchform_expand add-transition" role="button" data-toggle="collapse" href="#collapsAdvSearchForm" aria-expanded="false" aria-controls="collapsAdvSearchForm">
			<span class="glyphicon glyphicon-edit"></span> Edit Search
			</a>

			<div class="collapse" id="collapsAdvSearchForm">
				<div class="modify_search clearfix">
				<?= FORM::open(Route::url('search'), array('class'=>'form-inline', 'method'=>'GET', 'action'=>''))?>
				<?=Form::errors()?>
				<fieldset>
					<div class="form-group col-xs-12 col-sm-4 ">
						<?= FORM::label('advertisement', _e('Advertisement Title'), array('class'=>'', 'for'=>'advertisement'))?>
						<div class="control mr-30">
							<?if(Core::config('general.algolia_search') == 1):?>
	                            <?=View::factory('pages/algolia/autocomplete_ad')?>
	                        <?else:?>
	                            <input type="text" id="title" name="title" class="form-control" value="<?=HTML::chars(core::get('title'))?>" placeholder="<?=__('Title')?>">
	                        <?endif?>
						</div>
					</div>
					<div class="form-group col-xs-12 col-sm-4 ">
						<?= FORM::label('category', _e('Category'), array('class'=>'', 'for'=>'category' ))?>
						<div class="control mr-30">
							<select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="category<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="category" class="form-control" data-placeholder="<?=__('Category')?>">
							<?if ( ! core::config('general.search_multi_catloc')) :?>
								<option value=""><?=__('Category')?></option>
							<?endif?>
							<?function lili($item, $key,$cats){?>
								<?if (core::config('general.search_multi_catloc')):?>
									<option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(is_array(core::request('category')) AND in_array($cats[$key]['seoname'], core::request('category')))?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
								<?else:?>
									<option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
								<?endif?>
								<?if (core::count($item)>0):?>
								<optgroup label="<?=$cats[$key]['translate_name']?>">
									<? if (is_array($item)) array_walk($item, 'lili', $cats);?>
									</optgroup>
								<?endif?>
							<?}array_walk($order_categories, 'lili',$categories);?>
							</select>
						</div>
					</div>
					<?if(core::config('advertisement.location') != FALSE AND core::count($locations) > 1 AND core::count($locations) < 100 ):?>
						<div class="form-group col-xs-12 col-sm-4">
							<?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
							<div class="control mr-30">
								<select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location" class="form-control" data-placeholder="<?=__('Location')?>">
								<?if ( ! core::config('general.search_multi_catloc')) :?>
									<option value=""><?=__('Location')?></option>
								<?endif?>
								<?function lolo($item, $key,$locs){?>
									<?if (core::config('general.search_multi_catloc')):?>
										<option value="<?=$locs[$key]['seoname']?>" <?=(is_array(core::request('location')) AND in_array($locs[$key]['seoname'], core::request('location')))?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
									<?else:?>
										<option value="<?=$locs[$key]['seoname']?>" <?=(core::request('location') == $locs[$key]['seoname'])?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
									<?endif?>
									<?if (core::count($item)>0):?>
									<optgroup label="<?=$locs[$key]['translate_name']?>">
										<? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
										</optgroup>
									<?endif?>
								<?}array_walk($order_locations, 'lolo',$locations);?>
								</select>
							</div>
						</div>
                    <?elseif(core::config('advertisement.location') != FALSE AND core::count($locations) > 1):?>
                        <div class="form-group col-xs-12 col-sm-4">
							<?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
							<div class="control mr-30">
                                <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location-search" class="form-control ajax-location-search" data-placeholder="<?=__('Location')?>" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                    <? if ( ! core::config('general.search_multi_catloc')) : ?>
                                        <option value=""><?=__('Location')?></option>
                                    <? endif ?>
                                    <? if (is_array($location_filter)) : ?>
                                        <? foreach ($location_filter as $location) : ?>
                                            <option value="<?= $location->seoname ?>" selected><?= $location->name ?></option>
                                        <? endforeach ?>
                                    <? elseif ($location_filter) : ?>
                                        <option value="<?= $location_filter->seoname ?>" selected><?= $location_filter->name ?></option>
                                    <? endif ?>
                                </select>
							</div>
						</div>
					<?endif?>

					<?if(core::config('advertisement.price')):?>
						<div class="form-group col-xs-6 col-sm-4">
							<label class="" for="price-min"><?=_e('Price from')?> </label>
								<div class="control mr-30">
									<input type="text" id="price-min" name="price-min" class="form-control" value="<?=HTML::chars(core::get('price-min'))?>" placeholder="<?=__('Price from')?>">
								</div>
						</div>

						<div class="form-group col-xs-6 col-sm-4">
							<label class="" for="price-max"><?=_e('Price to')?></label>
								<div class="control mr-30">
									<input type="text" id="price-max" name="price-max" class="form-control" value="<?=HTML::chars(core::get('price-max'))?>" placeholder="<?=__('to')?>">
								</div>
						</div>
					<?endif?>

					<div class="form-group col-xs-12 col-sm-4 text-center">
						<label>&nbsp;</label>
						<div>
							<?= FORM::button('submit', _e('Search'), array('type'=>'submit', 'class'=>'btn btn-base-dark', 'action'=>Route::url('search')))?>
						</div>
					</div>
				</fieldset>
				<?= FORM::close()?>
				</div>
			</div>

			<div class="no_results text-center">
				<span class="nr_badge"><i class="glyphicon glyphicon-info-sign glyphicon"></i></span>
				<p class="nr_info"><?=_e('Your search did not match any advertisement.')?></p>
			</div>
		</div>

		<?if(Theme::get('sidebar_position')!='none'):?>
            <?=(Theme::get('sidebar_position')=='left')?View::fragment('sidebar_front','sidebar'):''?>
            <?=(Theme::get('sidebar_position')=='right')?View::fragment('sidebar_front','sidebar'):''?>
        <?endif?>

		<?endif?>

	<?else:?>
		<div class="col-md-9 col-sm-12 col-xs-12">
			<div class="page-header">
				<h3><?=_e('Search')?></h3>
			</div>

			<div id="adv_search_form">
			<div class="clearfix">
			<?= FORM::open(Route::url('search'), array('class'=>'form-inline', 'method'=>'GET', 'action'=>''))?>
			<?=Form::errors()?>
				<fieldset>
					<div class="form-group col-xs-12 ">
						<?= FORM::label('advertisement', _e('Advertisement Title'), array('class'=>'', 'for'=>'advertisement'))?>
						<div class="control mr-30">
							<?if(Core::config('general.algolia_search') == 1):?>
								<?=View::factory('pages/algolia/autocomplete_ad')?>
							<?else:?>
								<input type="text" id="title" name="title" class="form-control" value="<?=HTML::chars(core::get('title'))?>" placeholder="<?=__('Title')?>">
							<?endif?>
						</div>
					</div>
					<div class="form-group col-xs-12 ">
						<?= FORM::label('category', _e('Category'), array('class'=>'', 'for'=>'category' ))?>
						<div class="control mr-30">
							<select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="category<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="category" class="form-control" data-placeholder="<?=__('Category')?>">
							<?if ( ! core::config('general.search_multi_catloc')) :?>
								<option value=""><?=__('Category')?></option>
							<?endif?>
							<?function lili($item, $key,$cats){?>
								<?if (core::config('general.search_multi_catloc')):?>
									<option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(is_array(core::request('category')) AND in_array($cats[$key]['seoname'], core::request('category')))?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
								<?else:?>
									<option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
								<?endif?>
								<?if (core::count($item)>0):?>
								<optgroup label="<?=$cats[$key]['translate_name']?>">
									<? if (is_array($item)) array_walk($item, 'lili', $cats);?>
								</optgroup>
								<?endif?>
							<?}array_walk($order_categories, 'lili',$categories);?>
							</select>
						</div>
					</div>
					<?if(core::config('advertisement.location') != FALSE AND core::count($locations) > 1 AND core::count($locations) < 100 ):?>
						<div class="form-group col-xs-12">
							<?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
							<div class="control mr-30">
								<select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location" class="form-control" data-placeholder="<?=__('Location')?>">
								<?if ( ! core::config('general.search_multi_catloc')) :?>
									<option value=""><?=__('Location')?></option>
								<?endif?>
								<?function lolo($item, $key,$locs){?>
									<?if (core::config('general.search_multi_catloc')):?>
										<option value="<?=$locs[$key]['seoname']?>" <?=(is_array(core::request('location')) AND in_array($locs[$key]['seoname'], core::request('location')))?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
									<?else:?>
										<option value="<?=$locs[$key]['seoname']?>" <?=(core::request('location') == $locs[$key]['seoname'])?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
									<?endif?>
									<?if (core::count($item)>0):?>
									<optgroup label="<?=$locs[$key]['translate_name']?>">
										<? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
										</optgroup>
									<?endif?>
								<?}array_walk($order_locations, 'lolo',$locations);?>
								</select>
							</div>
						</div>
                    <?elseif(core::config('advertisement.location') != FALSE AND core::count($locations) > 1):?>
                        <div class="form-group col-xs-12">
							<?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
							<div class="control mr-30">
                                <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location-search" class="form-control ajax-location-search" data-placeholder="<?=__('Location')?>" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                    <? if ( ! core::config('general.search_multi_catloc')) : ?>
                                        <option value=""><?=__('Location')?></option>
                                    <? endif ?>
                                    <? if (is_array($location_filter)) : ?>
                                        <? foreach ($location_filter as $location) : ?>
                                            <option value="<?= $location->seoname ?>" selected><?= $location->name ?></option>
                                        <? endforeach ?>
                                    <? elseif ($location_filter) : ?>
                                        <option value="<?= $location_filter->seoname ?>" selected><?= $location_filter->name ?></option>
                                    <? endif ?>
                                </select>
							</div>
						</div>
					<?endif?>

					<?if(core::config('advertisement.price')):?>
						<div class="form-group col-xs-6 col-sm-4">
							<label class="" for="price-min"><?=_e('Price from')?> </label>
							<div class="control mr-30">
								<input type="text" id="price-min" name="price-min" class="form-control" value="<?=HTML::chars(core::get('price-min'))?>" placeholder="<?=__('Price from')?>">
							</div>
						</div>
						<div class="form-group col-xs-6 col-sm-4">
							<label class="" for="price-max"><?=_e('Price to')?></label>
							<div class="control mr-30">
								<input type="text" id="price-max" name="price-max" class="form-control" value="<?=HTML::chars(core::get('price-max'))?>" placeholder="<?=__('to')?>">
							</div>
						</div>
					<?endif?>
					<div class="form-group col-xs-12 col-sm-4 text-center">
						<label>&nbsp;</label>
						<div>
							<?= FORM::button('submit', _e('Search'), array('type'=>'submit', 'class'=>'btn btn-base-dark', 'action'=>Route::url('search')))?>
						</div>
					</div>
				</fieldset>
			<?= FORM::close()?>
			</div>
			</div>
		</div>

		<?=View::fragment('sidebar_front','sidebar')?>

	<?endif?>

	</div>
</div>
