<?php defined('SYSPATH') or die('No direct script access.');?>
	<div class="page-header">
		<h1><?=__('Publish new advertisement')?></h1>
	</div>
	<div class=" well">
		<?= FORM::open(Route::url('post_new',array('controller'=>'new','action'=>'index')), array('class'=>'form-horizontal post_new', 'enctype'=>'multipart/form-data'))?>
			<fieldset>

				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('title', __('Title'), array('class'=>'control-label', 'for'=>'title'))?>
						<?= FORM::input('title', Request::current()->post('title'), array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
					</div>
				</div>

               <!-- drop down selector -->
                <div class="form-group">
                    
                    <div class="col-md-4">
                    <?= FORM::label('category', __('Category'), array('class'=>'control-label', 'for'=>'category' ))?> 
                    <div class="accordion" >

                    <?function lili3($item, $key,$cats){?>
                        <div class="accordion-group">
                            <div class="accordion-heading"> 

                                <?if (count($item)>0):?>
                                    <label class="radio">
                                    	<a class="btn btn-primary btn-xs" data-toggle="collapse" type="button"  
                                       	 	data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                        	<i class=" glyphicon glyphicon-plus glyphicon"></i> <?=$cats[$key]['name']?>
                                    	</a>
                                    <?if(core::config('advertisement.parent_category')):?>
                                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="category" value="<?=$cats[$key]['id']?>" required > 
                                    <?endif?>
                                    <?if ($cats[$key]['price']>0):?>
                                        <span class="label label-success">
                                        <?=i18n::money_format( $cats[$key]['price'])?>
                                        </span>
                                    <?endif?>
                                    
                                    </label>
                                    
                                <?else:?>
                                    <label class="radio">
                                    <input <?=($cats[$key]['seoname']==Core::get('category'))?'checked':''?> type="radio" id="radio_<?=$cats[$key]['seoname']?>" name="category" value="<?=$cats[$key]['id']?>" required > 
                                    
                                   		<a class="btn btn-xs btn-primary" data-toggle="collapse" type="button"  
                                       	 	data-target="#acc_<?=$cats[$key]['seoname']?>">                    
                                        	<?=$cats[$key]['name']?>
                                    	</a>

                                     <?if ($cats[$key]['price']>0):?>
                                        <span class="label label-success pull-right">
                                        <?=i18n::money_format( $cats[$key]['price'])?>
                                        </span>
                                    <?endif?>
                                    </label>
                                <?endif?>
                            </div>

                            <?if (count($item)>0):?>
                                <div id="acc_<?=$cats[$key]['seoname']?>" 
                                    class="accordion-body collapse <?=($cats[$key]['seoname']==Core::get('category'))?'in':''?>">
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
				
				<?if(count($locations) > 1 AND $form_show['location'] != FALSE):?>
                    <div class="form-group">
                        
                        <div class="col-md-4">
                        	<?= FORM::label('location', __('Location'), array('class'=>'control-label', 'for'=>'location' ))?>
                            <select name="location" id="location" class="form-control" required>
                            <option></option>
                            <?function lolo($item, $key,$locs){?>
                            <option value="<?=$key?>"><?=$locs[$key]['name']?></option>
                                <?if (count($item)>0):?>
                                <optgroup label="<?=$locs[$key]['name']?>">    
                                    <? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
                                    </optgroup>
                                <?endif?>
                            <?}array_walk($order_locations, 'lolo',$locations);?>
                            </select>
                        </div>
                    </div>
				<?endif?>

				<div class="form-group">
					
					<div class="col-md-8">
						<?= FORM::label('description', __('Description'), array('class'=>'control-label', 'for'=>'description', 'spellcheck'=>TRUE))?>
						<?= FORM::textarea('description', Request::current()->post('description'), array('class'=>'form-control', 'name'=>'description', 'id'=>'description' ,  'rows'=>10, 'required'))?>
					</div>
				</div>
				<div class="form-group">
					<?for ($i=0; $i < core::config("advertisement.num_images") ; $i++):?> 
						
						<div class="col-md-4">
							<?= FORM::label('images', __('Images'), array('class'=>'control-label', 'for'=>'images'.$i))?>
							<input type="file" name="<?='image'.$i?>" id="<?='fileInput'.$i?>" />
						</div>
					<?endfor?>
				</div>
				<?if($form_show['phone'] != FALSE):?>
				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('phone', __('Phone'), array('class'=>'control-label', 'for'=>'phone'))?>
						<?= FORM::input('phone', Request::current()->post('phone'), array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone')))?>
					</div>
				</div>
				<?endif?>
				<?if($form_show['address'] != FALSE):?>
				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('address', __('Address'), array('class'=>'control-label', 'for'=>'address'))?>
						<?= FORM::input('address', Request::current()->post('address'), array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
					</div>
				</div>
				<?endif?>
				<?if($form_show['price'] != FALSE):?>
				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('price', __('Price'), array('class'=>'control-label', 'for'=>'price'))?>
						<div class="input-prepend">
						<?= FORM::input('price', Request::current()->post('price'), array('placeholder' => i18n::money_format(1), 'class' => 'form-control', 'id' => 'price', 'type'=>'text'))?>
						</div>
					</div>
				</div>
				<?endif?>
				<?if($form_show['website'] != FALSE):?>
				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('website', __('Website'), array('class'=>'control-label', 'for'=>'website'))?>
						<?= FORM::input('website', Request::current()->post('website'), array('placeholder' => __('Website'), 'class' => 'form-control', 'id' => 'website'))?>
					</div>
				</div>
				<?endif?>
				<?if (!Auth::instance()->get_user()):?>
				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
						<?= FORM::input('name', Request::current()->post('name'), array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
					</div>
				</div>
				<div class="form-group">
					
					<div class="col-md-4">
						<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
						<?= FORM::input('email', Request::current()->post('email'), array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
					</div>
				</div>
				<?endif?>

				<?if(core::config('advertisement.tos') != ''):?>
				<div class="form-group">
					<div class="col-md-4">
                        <label class="checkbox">
                          	<input type="checkbox" required name="tos" id="tos"/> 
							<a target="_blank" href="<?=Route::url('page', array('seotitle'=>core::config('advertisement.tos')))?>"> <?=__('Terms of service')?></a>
						</label>
					</div>
				</div>
				<?endif?>
				<?if ($form_show['captcha'] != FALSE):?>
				<div class="form-group">
					<div class="col-md-4">
						Captcha*:<br />
						<?= captcha::image_tag('newad');?><br />
						<?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
					</div>
				</div>
				<?endif?>
				<div class="form-actions">
					<?= FORM::button('submit', __('Publish new'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('post_new',array('controller'=>'new','action'=>'index'))))?>
					<p class="help-block"><?=__('User account will be created')?></p>
				</div>
			</fieldset>
		<?= FORM::close()?>

	</div>
	<!--/well-->
