<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pad_10tb">
	<div class="container single">
		<div class="row">
			<div class="col-xs-12">
				<?if ($ad->status != Model_Ad::STATUS_PUBLISHED && $permission === FALSE && ($ad->id_user != $user)):?>
					<div class="no_results text-center">
						<span class="nr_badge"><i class="glyphicon glyphicon-comment"></i></span>
						<p class="nr_info"><?= _e('This advertisement doesn´t exist, or is not yet published!')?></p>
					</div>
				<?else:?>

				<div class="page-header">
					<h3><?= $ad->title;?></h3>
				</div>

				<?=Form::errors()?>

				<!-- Boost Ad Options -->
				<?if ((Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == 10 ) OR
				(Auth::instance()->logged_in() AND $ad->user->id_user == Auth::instance()->get_user()->id_user)):?>
				<?if((core::config('payment.pay_to_go_on_top') > 0
				&& core::config('payment.to_top') != FALSE )
				OR (core::config('payment.pay_to_go_on_feature') > 0
				&& core::config('payment.to_featured') != FALSE)):?>
				<div class="alert alert-success text-center alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?if(core::config('payment.pay_to_go_on_top') > 0 AND core::config('payment.to_top') != FALSE AND $ad->status == Model_Ad::STATUS_PUBLISHED):?>
							<p class="pad_5tb"><?=_e('Your Advertisement can go on top again! For only ').i18n::format_currency(core::config('payment.pay_to_go_on_top'),core::config('payment.paypal_currency'));?></p>
							<a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>"><?=_e('Go Top!')?></a>
						<?endif?>
						<?if(core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql()):?>
							<p class="pad_5tb"><?=_e('Your Advertisement can go to featured! For only ').i18n::format_currency(Model_Order::get_featured_price(),core::config('payment.paypal_currency'));?></p>
							<a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>"><?=_e('Go Featured!')?></a>
						<?endif?>
				</div>
				<?endif?>
				<?endif?>
				<!-- // BOOST AD OPTIONS -->
				<div class="row">
					<div class="col-xs-7 main-ad-left">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#photos" aria-controls="photos" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-camera"></span> <?=_e('Photos')?></a></li>
							<?if (core::config('advertisement.map')==1 AND $ad->latitude AND $ad->longitude):?>
								<li role="presentation"><a href="#map" aria-controls="map" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-map-marker"></span> <?=_e('Map')?></a></li>
							<?endif?>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane clearfix fade in active" id="photos">
								<?$images = $ad->get_images()?>
								<?if($images):?>
									<div id="gallery" class="ad_images pad_10">
										<?$i=0; foreach ($images as $path => $value):?>
											<?$i++; if( isset($value['thumb']) AND isset($value['image']) ):?>
												<? if ($i==1) :?>
													<div class="mainThumb">
														<a href="<?=$value['image']?>" class="thumbnail gallery-item first" data-gallery>
															<?=HTML::picture($value['thumb'], ['w' => 200, 'h' => 200], ['992px' => ['w' => '200', 'h' => '200'], '320px' => ['w' => '200', 'h' => '200']], ['class' => 'img-rounded'], ['alt' => HTML::chars($ad->title)])?>
														</a>
													</div>
												<?else:?>
													<div class="ad_thumb_block">
														<a href="<?=$value['image']?>" class="thumbnail gallery-item" data-gallery>
															<?=HTML::picture($value['thumb'], ['w' => 200, 'h' => 200], ['992px' => ['w' => '200', 'h' => '200'], '320px' => ['w' => '200', 'h' => '200']], ['alt' => HTML::chars($ad->title)])?>
														</a>
													</div>
												<?endif?>
											<?endif?>
										<?endforeach?>
										<div class="clear"></div>
									</div>
								<?else:?>
									<div id="gallery" class="ad_images">
										<div class="#">
											<div class="thumbnail gallery-item">
											<img data-src="holder.js/200x200?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->translate_name(), 'size' => 14, 'auto' => 'yes')))?>" alt="<?=HTML::chars($ad->title)?>">
											</div>
										</div>
									</div>
								<?endif?>
							</div>
							<?if ($ad->map() !== FALSE):?>
								<div role="tabpanel" class="tab-pane clearfix fade" id="map">
									<div class="pad_10">
										<?=$ad->map()?>
									</div>
								</div>
							<?endif?>
						</div>
					</div>

					<div class="col-xs-5 main-ad-right">
						<div class="seller_box">
							<div class="pad_10">
								<?if ($ad->id_location != 1):?>
									<p class="seller_user"> <a class="" href="<?=Route::url('profile',  array('seoname'=>$ad->user->seoname))?>"><?=$ad->user->name?></a></p>
									<p class="seller_location"><?=$ad->location->translate_name()?></p>
								<?else:?>
									<p class="seller_user_ul"> <a class="" href="<?=Route::url('profile',  array('seoname'=>$ad->user->seoname))?>"><?=$ad->user->name?></a></p>
								<?endif?>

								<div class="ad_details">
									<?if (core::config('advertisement.address') AND $ad->address != NULL):?>
										<p><span class="glyphicon glyphicon-map-marker"></span> <?=$ad->address?></p>
									<?endif?>
									<?if (core::config('advertisement.phone')==1 AND strlen($ad->phone)>1):?>
										<p><span class="glyphicon glyphicon-phone-alt"></span> <a class="opt_btn" href="tel:<?=$ad->phone?>"><?=$ad->phone?></a></p>
									<?else:?>
										<p><span class="glyphicon glyphicon-phone-alt"></span> <a class="opt_btn" href="tel:#">N/A</a></p>
									<?endif?>
									<?if (Valid::url($ad->website)):?>
										<p><span class="glyphicon glyphicon-globe"></span> <a href="<?=$ad->website?>" rel="nofollow" target="_blank"><?=$ad->website?></a></p>
									<?endif?>
									<p class=""><span class="glyphicon glyphicon-calendar"></span> <?= Date::format($ad->published, core::config('general.date_format'))?></p>
									<?if(core::config('advertisement.count_visits')==1):?>
										<p class=""><span class="glyphicon glyphicon-eye-open"></span> <?=$hits?> <?=_e('Hits')?></p>
									<?endif?>
								</div>

								<div class="favorite" id="fav-<?=$ad->id_ad?>">
								<?if (Auth::instance()->logged_in()):?>
									<?$fav = Model_Favorite::is_favorite(Auth::instance()->get_user(),$ad);?>
									<a data-id="fav-<?=$ad->id_ad?>" class="add-favorite <?=($fav)?'remove-favorite':''?>" title="<?=__('Add to Favorites')?>" href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'favorites','id'=>$ad->id_ad))?>">
										<i class="glyphicon glyphicon-star<?=($fav)?'':'-empty'?>"></i>
									</a>
								<?else:?>
									<a data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
										<i class="glyphicon glyphicon-star-empty"></i>
									</a>
								<?endif?>
								</div>
							</div>

							<div class="seller_footer clearfix">
								<div class="seller_f_block">
                                    <?if((core::config('payment.paypal_seller')==1 OR Core::config('payment.stripe_connect')==1 OR Core::config('payment.escrow_pay')==TRUE) AND $ad->price != NULL AND $ad->price > 0):?>
										<?if(core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1)):?>
                    						<?if($ad->status != Model_Ad::STATUS_SOLD):?>
												<a class="sf_btn i_price" href="<?=Route::url('default', array('action'=>'buy','controller'=>'ad','id'=>$ad->id_ad))?>"><?=__('Buy Now')?> - <span class="price-curry"><?=i18n::money_format( $ad->price, $ad->currency())?></a></span>
						                    <?else:?>
						                        <a class="sf_btn i_price disabled">
						                            &nbsp;&nbsp;<?=_e('Sold')?>
						                        </a>
						                    <?endif?>
										<?else:?>
											<span class="sf_btn i_price"><span class="price-curry"><?=i18n::money_format( $ad->price, $ad->currency())?></span></span>
										<?endif?>
									<?elseif ($ad->price>0):?>
										<span class="sf_btn i_price"><span class="price-curry"><?=i18n::money_format( $ad->price, $ad->currency())?></span></span>
									<?elseif (($ad->price==0 OR $ad->price == NULL) AND core::config('advertisement.free')==1):?>
										<span class="sf_btn i_price"><?=_e('Free');?></span>
									<?else:?>
										<span class="sf_btn i_price">N/A</span>
									<?endif?>
								</div>
								<?if ($ad->can_contact()):?>
									<div class="seller_f_block">
										<?if ((core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE) AND !Auth::instance()->logged_in()):?>
											<a class="sf_btn" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal"><?=_e('Send Message')?></a>
										<?else:?>
											<a class="sf_btn" href="#" data-toggle="modal" data-target="#contact-modal"><?=_e('Send Message')?></a>
										<?endif?>
									</div>
								<?endif?>
							</div>
						</div>
					</div>
				</div>

				<div class="clear"></div>

				<br />
				<hr />
				<br />
			</div>

			<!-- Start Bottom Tabbed Ad Info -->
			<div class="col-xs-12">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#addesc" aria-controls="addesc" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-info-sign"></i></a></li>
					<?if($ad->comments()!=FALSE):?>
						<li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-comment"></i></a></li>
					<?endif?>
					<?if($ad->related()):?>
						<li role="presentation"><a href="#related" aria-controls="related" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-th-list"></i></a></li>
					<?endif?>
					<?if(core::config('advertisement.sharing')==1 OR core::config('advertisement.qr_code')==1):?>
						<li role="presentation"><a href="#share" aria-controls="share" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-share"></i></a></li>
					<?endif?>
				</ul>

				<div class="tab-content">
					<div role="tabpanel" class="tab-pane clearfix fade in active" id="addesc">
						<div class="ad_info">
							<?if(core::config('advertisement.description')!=FALSE):?>
								<div class="ad_desc"><?=Text::bb2html($ad->description,TRUE)?></div>
							<?endif?>
							<?if(core::config('advertisement.report')==1):?>
						        <?=$ad->flagad()?>
						    <?endif?>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade clearfix" id="comments">
						<div class="comments_section">
						<?if($ad->comments()):?>
							<?=$ad->comments()?>
						<?else:?>
							<div class="no_results text-center">
								<span class="nr_badge"><i class="glyphicon glyphicon-comment"></i></span>
								<p class="nr_info"><?=_e('Sorry, comments are unavailable..')?></p>
							</div>
						<?endif?>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade clearfix" id="related">
						<div class="pad_10">
							<?=$ad->related()?>
						</div>
					</div>

					<div role="tabpanel" class="tab-pane fade" id="share">
						<div class="pad_10">
							<?if(core::config('advertisement.sharing')==1):?>
								<div class="well">
									<h1><?=_e('Share')?></h1>
									<?=View::factory('share')?>
								</div>
								<div class="clearfix"></div><br>
							<?endif?>
							<?=$ad->qr()?>
						</div>
					</div>
				</div>
			</div>
			<br><br>
			<!-- END Tabbed Ad Info -->
			<!-- modal-gallery is the modal dialog used for the image gallery -->
			<div class="modal fade" id="modal-gallery">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="modal-image"></div>
						</div>
						<div class="modal-footer">
							<a class="btn btn-info modal-prev"><i class="glyphicon glyphicon-arrow-left glyphicon"></i> <?=_e('Previous')?></a>
							<a class="btn btn-primary modal-next"><?=_e('Next')?> <i class="glyphicon glyphicon-arrow-right glyphicon"></i></a>
							<a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="glyphicon glyphicon-play glyphicon"></i> <?=_e('Slideshow')?></a>
							<a class="btn modal-download" target="_blank"><i class="glyphicon glyphicon-download"></i> <?=_e('Download')?></a>
						</div>
					</div>
				</div>
			</div>
			<!-- The modal dialog, which will be used to wrap the lightbox content -->
			<div id="blueimp-gallery" class="blueimp-gallery">
				<div class="slides"></div>
				<h3 class="title"></h3>
				<a class="prev">‹</a>
				<a class="next">›</a>
				<a class="close">×</a>
				<a class="play-pause"></a>
				<ol class="indicator"></ol>

				<div class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="pad_10">
								<button type="button" class="close" aria-hidden="true">&times;</button>
								<div class="modal-body next"></div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-base-dark pull-left prev"><i class="glyphicon glyphicon-chevron-left"></i></button>
								<button type="button" class="btn btn-base-dark pull-right next"><i class="glyphicon glyphicon-chevron-right"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?if ($ad->can_contact()):?>
				<?if ((core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE) AND !Auth::instance()->logged_in()):?>
					<div class="clear"></div>
				<?else:?>
					<div id="contact-modal" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
									<h3><?=_e('Contact')?></h3>
								</div>
								<div class="modal-body">
									<?=Form::errors()?>
									<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'user_contact', 'id'=>$ad->id_ad)), array('class'=>'clean_form', 'enctype'=>'multipart/form-data'))?>
									<fieldset>
										<?if (!Auth::instance()->get_user()):?>
											<dl class="form-group clearfix">
												<dt><?= FORM::label('name', _e('Name'), array('class'=>'control-label', 'for'=>'name'))?></dt>
												<dd><?= FORM::input('name', Core::request('name'), array('placeholder' => __('Name'), 'class'=>'form-control', 'id' => 'name', 'required'))?></dd>
											</dl>
											<dl class="form-group clearfix">
												<dt><?= FORM::label('email', _e('Email'), array('class'=>'control-label', 'for'=>'email'))?></dt>
												<dd><?= FORM::input('email', Core::request('email'), array('placeholder' => __('Email'), 'class'=>'form-control', 'id' => 'email', 'type'=>'email','required'))?></dd>
											</dl>
										<?endif?>
										<?if(core::config('general.messaging') != TRUE):?>
											<dl class="form-group clearfix">
												<dt><?= FORM::label('subject', _e('Subject'), array('class'=>'control-label', 'for'=>'subject'))?></dt>
												<dd><?= FORM::input('subject', Core::request('subject'), array('placeholder' => __('Subject'), 'class'=>'form-control', 'id' => 'subject'))?></dd>
											</dl>
										<?endif?>
										<dl class="form-group clearfix">
											<dt><?= FORM::label('message', _e('Message'), array('class'=>'control-label', 'for'=>'message'))?></dt>
											<dd><?= FORM::textarea('message', Core::request('message'), array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>5, 'required'))?></dd>
										</dl>
										<?if(core::config('general.messaging') AND
		                                    core::config('advertisement.price') AND
		                                    core::config('advertisement.contact_price')):?>
											<dl class="form-group clearfix">
												<dt><?= FORM::label('price', _e('Price'), array('class'=>'control-label', 'for'=>'price'))?></dt>
												<dd><?= FORM::input('price', Core::post('price'), array('placeholder' => html_entity_decode(i18n::money_format(1, $ad->currency())), 'class' => 'form-control', 'id' => 'price', 'type'=>'text'))?></dd>
											</dl>
										<?endif?>
										<!-- file to be sent-->
										<?if(core::config('advertisement.upload_file') AND core::config('general.messaging') != TRUE):?>
											<dl class="form-group clearfix">
												<dt><?= FORM::label('file', _e('File'), array('class'=>'control-label', 'for'=>'file'))?></dt>
												<dd><?= FORM::file('file', array('placeholder' => __('File'), 'class'=>'form-control', 'id' => 'file'))?></dd>
											</dl>
										<?endif?>
										<?if (core::config('advertisement.captcha') != FALSE):?>
											<dl class="capt form-group clearfix">
												<?=FORM::label('captcha', _e('Captcha'), array('class'=>'control-label hidden', 'for'=>'captcha'))?>
												<?if (Core::config('general.recaptcha_active')):?>
                                                    <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
												<?else:?>
													<dt><?=captcha::image_tag('contact')?></dt>
													<dd><?= FORM::input('captcha', "", array('class'=>'form-control', 'placeholder' => __('Captcha'), 'id' => 'captcha', 'required'))?></dd>
												<?endif?>
											</dl>
										<?endif?>
										<dl class="modal-footer text-center">
											<?= FORM::button(NULL, _e('Send Message'), array('type'=>'submit', 'class'=>'btn btn-base-dark', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'user_contact' , 'id'=>$ad->id_ad))))?>
										</dl>
									</fieldset>
									<?= FORM::close()?>
								</div>
							</div>
						</div>
					</div>
				<?endif?>
			<?endif?>
			<?=$ad->structured_data()?>
			<?endif?>
		</div>
	</div>
</div>
