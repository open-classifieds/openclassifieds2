<?php defined('SYSPATH') or die('No direct script access.');?>
	<div class="well">
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Edit Advertisement')?></h1>
		</div>
		
		<!-- PAYPAL buttons to featured and to top -->
		<?if((core::config('payment.pay_to_go_on_top') > 0  
				&& core::config('payment.to_top') != FALSE )
				OR (core::config('payment.pay_to_go_on_feature') > 0 
				&& core::config('payment.to_featured') != FALSE)):?>
			<div id="advise" class="well advise clearfix">
				<?if(core::config('payment.pay_to_go_on_top') > 0 && core::config('payment.to_top') != FALSE):?>
					<p class="text-info"><?=__('Your Advertisement can go on top again! For only ').core::config('payment.pay_to_go_on_top').' '.core::config('payment.paypal_currency');?></p>
					<a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>"><?=__('Go Top!')?></a>
				<?endif?>
				<?if(core::config('payment.pay_to_go_on_feature') > 0 && core::config('payment.to_featured') != FALSE):?>
					<p class="text-info"><?=__('Your Advertisement can go to featured! For only ').core::config('payment.pay_to_go_on_feature').' '.core::config('payment.paypal_currency');?></p>
					<a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>"><?=__('Go Featured!')?></a>
				<?endif?>
			</div>
		<?endif?>
		<!-- end paypal button -->
		
		<?= FORM::open(Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad)), array('class'=>'form-horizontal edit_ad_form', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<div class="control">
					<?if(Auth::instance()->get_user()->id_role == 10):?>
					<? $owner = new Model_User($ad->id_user)?>
					<table class="table table-bordered ">
						<tr>
							<th><?=__('Id_User')?></th>
							<th><?=__('Profile')?></th>
							<th><?=__('Name')?></th>
							<th><?=__('Email')?></th>
						</tr>
						<tbody>
							<tr>
								<td><p><?= $ad->id_user?></p></td>
								<td>	
									<a href="<?=Route::url('profile', array('seoname'=>$owner->seoname))?>" alt=""><?= $owner->seoname?></a>
								</td>
								<td><p><?= $owner->name?></p></td>
								<td>	
									<a href="<?=Route::url('contact')?>"><?= $owner->email?></a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?endif?>
				<div class="form-group">
					<div class="col-sm-4">
						<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
						<?= FORM::input('title', $ad->title, array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
					</div>
				</div>

				<!-- drop down selector  CATEGORIES-->
                <div class="form-group">
                    <div class="col-sm-4"> 
                    <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' ))?>
                    <div class="accordion">
                    <?function lili3($item, $key, $cats){?>
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <?if (count($item)>0):?>
                                    <label class="radio">
                                    	<a class="btn btn-primary btn-xs" data-toggle="collapse" type="button"  
                                       	 	data-target="#acc_<?=$cats['categories'][$key]['seoname']?>">                    
                                        	<i class=" glyphicon   glyphicon-plus"></i> <?=$cats['categories'][$key]['name']?>
                                    	</a>
									<?if(core::config('advertisement.parent_category')):?>
                                    <input <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'checked':''?> type="radio" id="radio_<?=$cats['categories'][$key]['seoname']?>" name="category" value="<?=$cats['categories'][$key]['id']?>" required > 
                                    <?endif?>
                                     <?if ($cats['categories'][$key]['price']>0):?>
                                        <span class="label label-success">
                                        <?=i18n::money_format( $cats['categories'][$key]['price'])?>
                                        </span>
                                    <?endif?>
                                    </label>
                                <?else:?>
                                    <label class="radio">
                                    <input <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'checked':''?> type="radio" id="radio_<?=$cats['categories'][$key]['seoname']?>" name="category" value="<?=$cats['categories'][$key]['id']?>" required >
                                   		<a class="btn btn-xs btn-primary" data-toggle="collapse" type="button"  
                                       	 	data-target="#acc_<?=$cats['categories'][$key]['seoname']?>">                    
                                        	<?=$cats['categories'][$key]['name']?>
                                    	</a>
                                     <?if ($cats['categories'][$key]['price']>0):?>
                                        <span class="label label-success">
                                        <?=i18n::money_format( $cats['categories'][$key]['price'])?>
                                        </span>
                                    <?endif?>
                                    </label>
                                <?endif?>
                            </div>
                            <?if (count($item)>0):?>
                                <div id="acc_<?=$cats['categories'][$key]['seoname']?>" 
                                    class="accordion-body collapse <?=($cats['categories'][$key]['seoname']==$cats['cat_selected'])?'in':''?>">
                                    <div class="accordion-inner">
                                        <? if (is_array($item)) array_walk($item, 'lili3', $cats);?>
                                    </div>
                                </div>
                            <?endif?>
                        </div>
                    <?}array_walk($order_categories, 'lili3', array('categories'=>$categories, 'cat_selected'=>$ad->category->seoname) );?>
                    </div>
                    </div>
                </div>
                <!-- /categories -->
                <!-- LOCATIONS -->
				<?if(core::config('advertisement.location') !== FALSE):?>
				<?if(count($locations) > 1):?>
                    <div class="form-group">
                        <div class="col-sm-4">  
                        <?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location' ))?>        
                            <select name="location" id="location" class="form-control" >
                            <option></option>
                            <?function lolo($item, $key,$locs){?>
                            <option value="<?=$key?>" class="<?=($key==$locs['loc_selected'])?'result-selected':''?>" <?=($key==$locs['loc_selected'])?'selected':''?>><?=$locs['locations'][$key]['name']?></option>
                                <?if (count($item)>0):?>
                                <optgroup label="<?=$locs['locations'][$key]['name']?>" >    
                                    <? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
                                    </optgroup>
                                <?endif?>
                            <?}array_walk($order_locations, 'lolo',array('locations'=>$locations, 'loc_selected'=>$ad->id_location));?>
                            </select>
                        </div>
                    </div>
				<?endif?>
				<?endif?>
				<!-- /locations -->
				<div class="form-group">
					<div class="col-sm-9">
						<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
						<?= FORM::textarea('description', $ad->description, array('class'=>'col-md-9', 'name'=>'description', 'id'=>'description', 'rows'=>8, 'required'))?>
					</div>
				</div>
				<?if(core::config('advertisement.phone') != FALSE):?>
				<div class="form-group">
					<div class="col-sm-4">
						<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
						<?= FORM::input('phone', $ad->phone, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<?endif?>
				<?if(core::config('advertisement.address') != FALSE):?>
				<div class="form-group">
					<div class="col-sm-4">
						<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
						<?= FORM::input('address', $ad->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<?endif?>
				<?if(core::config('advertisement.website') != FALSE):?>
				<div class="form-group">
					<div class="col-sm-4">
						<?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
						<?= FORM::input('website', $ad->website, array('class'=>'form-control', 'id'=>'website', 'placeholder'=>__('Website')))?>
					</div>
				</div>
				<?endif?>
				<?if(core::config('advertisement.price') != FALSE):?>
				<div class="form-group">
					<div class="col-sm-4">
						<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
						<div class="input-prepend">
							<?= FORM::input('price', $ad->price, array('placeholder'=>i18n::money_format(1),'class'=>'form-control', 'id' => 'price'))?>
						</div>
					</div>
				</div>
				<?endif?>
				<!-- Fields coming from custom fields feature -->
				<?if (Theme::get('premium')==1):?>
					<?if(isset($fields)):?>
					<?if (is_array($fields)):?>
						<?foreach($fields as $name=>$field):?>
						<div class="form-group">
						<?$cf_name = 'cf_'.$name?>
						<?if($field['type'] == 'select' OR $field['type'] == 'radio') {
							$select = array(''=>'');
							foreach ($field['values'] as $select_name) {
								$select[$select_name] = $select_name;
							}
						}
						else $select = $field['values']?>
	    					<?=Form::form_tag('cf_'.$name, array(    
	                            'display'   => $field['type'],
	                            'label'     => $field['label'],
	                            'default'	=> $ad->$cf_name,
	                            'options'	=> $select,
	                            'required'	=> $field['required']))?> 
	                    </div>     
						<?endforeach?>
					<?endif?>
					<?endif?>
				<?endif?>
				<!-- /endcustom fields -->
				<div class="row">
					<div class="col-md-12">
						<?$images = $ad->get_images()?>
						<?if($images):?>
							<?php foreach ($images as $path => $value):?>
							<?if(isset($value['thumb'])): // only formated images (not originals)?>
							<?$esc_html_img_name = HTML::chars(str_replace('.jpg', '', substr(strrchr($value['thumb'], '/'), 1 )));?>
							<div class="col-md-4 edit-image">
								<a>
									<img src="<?=URL::base('http')?><?= $value['thumb']?>" class="img-rounded" alt="<?=$esc_html_img_name?>" />
								</a>
								<button class="btn btn-danger index-delete"
								   onclick="return confirm('<?=HTML::chars(__("Delete?"))?>');" 
								   type="submit" 
								   name="img_delete"
								   value="<?=$esc_html_img_name?>" 
								   href="<?=Route::url('default', array('controller'=>'ad', 
								   									   'action'=>'img_delete', 
								   									   'id'=>$ad->id_ad))?>" 
								   rel"tooltip" 
								   title="<?=HTML::chars(__("Delete image"))?>">
									<?=__("Delete")?>
								</button>
							</div>
							<?endif?>
							<?endforeach?>
						
						<?endif?>
					</div>	
				</div>
				<div class="form-group">
					<?if (core::config('advertisement.num_images') > count($images)):?> <!-- permition to add more images-->
						
						<div class="col-sm-4">
							<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images0'))?>
							<input class="form-control" type="file" name='image0' id='fileInput0' />
						</div>
					<?endif?>
				</div>
				
					<?= FORM::button('submit', 'update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))))?>
				
			</fieldset>
		<?= FORM::close()?>
	</div>
	<!--/well-->
		
