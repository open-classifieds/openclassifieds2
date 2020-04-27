<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="pad_10tb">
	<div class="container profile-view">
		<div class="row">
			<div class="<?=(Theme::get('sidebar_position')!='none')?'col-xs-9':'col-xs-12'?> <?=(Theme::get('sidebar_position')=='left')?'pull-right':'pull-left'?>">
				<div class="pad_10">
					<article class="well clearfix">
						<div class="col-sm-3">
							<?$images = $user->get_profile_images(); if ($images):?>
					            <div id="gallery">
					                <?$i = 0; foreach ($images as $key => $image):?>
					                    <a href="<?=$image?>" class="thumbnail gallery-item <?=$i > 0 ? 'hidden' : NULL?>" data-gallery>
					                        <img class="img-rounded img-responsive" src="<?=Core::imagefly($image,200,200)?>" alt="<?=$user->name?>">
					                    </a>
					                <?$i++; endforeach?>
					            </div>
					            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
					                <div class="slides"></div>
					                <h3 class="title"></h3>
					                <a class="prev">‹</a>
					                <a class="next">›</a>
					                <a class="close">×</a>
					                <a class="play-pause"></a>
					                <ol class="indicator"></ol>
					            </div>
					        <?endif?>
						</div>
						<div class="col-sm-9">
							<h3><?=$user->name?></h3>
								<p><?=Text::bb2html($user->description,TRUE)?></p>
								<p>
									<ul class="list-unstyled">
									<li><strong><?=_e('Created')?>:</strong> <?= Date::format($user->created, core::config('general.date_format')) ?></li>
									<?if ($user->last_login!=NULL):?>
										<li><strong><?=_e('Last Login')?>:</strong> <?= Date::format($user->last_login, core::config('general.date_format'))?></li>
									<?endif?>
									</ul>
								</p>
						</div>

						<!-- Popup contact form -->
						<p class="text-right">
						<?if (core::config('general.messaging') == TRUE AND !Auth::instance()->logged_in()) :?>
							<a class="btn btn-base-dark" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
								<i class="glyphicon glyphicon-envelope"></i>
								<?=_e('Send Message')?>
							</a>
						<?else :?>
							<button class="btn btn-base-dark" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i> <?=_e('Send Message')?></button>
						<?endif?>
						<div id="contact-modal" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										 <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
										<h3><?=_e('Contact')?></h3>
									</div>
									<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact', 'id'=>$user->id_user)), array('class'=>'clean_form', 'enctype'=>'multipart/form-data'))?>
									<div class="modal-body">
										<?=Form::errors()?>
											<fieldset>
												<?if (!Auth::instance()->get_user()):?>
													<dl class="form-group">
														<dt><?= FORM::label('name', _e('Name'), array('class'=>'control-label', 'for'=>'name'))?></dt>
														<dd><?= FORM::input('name', Core::request('name'), array('placeholder' => __('Name'), 'class' => 'form-control', 'id' => 'name', 'required'))?></dd>
													</dl>
													<dl class="form-group">
													   <dt> <?= FORM::label('email', _e('Email'), array('class'=>'control-label', 'for'=>'email'))?></dt>
														<dd><?= FORM::input('email', Core::request('email'), array('placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'email', 'type'=>'email','required'))?></dd>
													</dl>
												<?endif?>
												<?if(core::config('general.messaging') != TRUE):?>
													<dl class="form-group">
														<dt><?= FORM::label('subject', _e('Subject'), array('class'=>'control-label', 'for'=>'subject'))?></dt>
														<dd><?= FORM::input('subject', Core::request('subject'), array('placeholder' => __('Subject'), 'class' => 'form-control', 'id' => 'subject'))?></dd>
													</dl>
												<?endif?>
												<dl class="form-group">
													<dt><?= FORM::label('message', _e('Message'), array('class'=>'control-label', 'for'=>'message'))?></dt>
													<dd><?= FORM::textarea('message', Core::post('subject'), array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>4, 'required'))?></dd>
												</dl>
												<?if (core::config('advertisement.captcha') != FALSE):?>
													<dl class="capt form-group clearfix">
													  <?= FORM::label('captcha', _e('Captcha'), array('class'=>'hidden', 'for'=>'captcha'))?>

															<?if (Core::config('general.recaptcha_active')):?>
                                                                <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
															<?else:?>
															  <dt>  <?=captcha::image_tag('contact')?></dt>
																<dd><?= FORM::input('captcha', "", array('class' => 'form-control', 'placeholder'=> __('Captcha'),'id' => 'captcha', 'required'))?></dd>
															<?endif?>
													</dl>
												<?endif?>
											</fieldset>
									</div>
									<div class="modal-footer text-center">
										<?= FORM::button(NULL, _e('Send Message'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact' , 'id'=>$user->id_user))))?>
									</div>
									<?= FORM::close()?>
								</div>
							</div>
						</div>
						</p>
				        <?if (core::config('advertisement.gm_api_key')):?>
				            <?if(Core::config('advertisement.map') AND $user->address !== NULL AND $user->latitude !== NULL AND $user->longitude !== NULL):?>
				                <h3><?=_e('Map')?></h3>
				                <p>
				                    <img class="img-responsive" src="//maps.googleapis.com/maps/api/staticmap?language=<?=i18n::get_gmaps_language(i18n::$locale)?>&amp;zoom=<?=Core::config('advertisement.map_zoom')?>&amp;scale=false&amp;size=600x300&amp;maptype=roadmap&amp;format=png&amp;visual_refresh=true&amp;markers=size:large%7Ccolor:red%7Clabel:·%7C<?=$user->latitude?>,<?=$user->longitude?>&amp;key=<?=core::config('advertisement.gm_api_key')?>" alt="<?=HTML::chars($user->name)?> <?=_e('Map')?>" style="width:100%;">
				                </p>
				                <p>
				                    <a class="btn btn-default btn-sm" href="<?=Route::url('map')?>?id_user=<?=$user->id_user?>" >
				                        <span class="glyphicon glyphicon-globe"></span> <?=_e('Map View')?>
				                    </a>
				                </p>
				        	<?elseif (Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin() AND !Core::config('advertisement.map')) :?>
				        		<p>
				                    <div class="alert alert-danger" role="alert">
				                        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>" class="alert-link">
				                            <?=__('Please enable "Google Maps in Ad and Profile page" to show user location on the map.')?>
				                        </a>
				                    </div>
				                </p>
				            <?elseif(Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_user == $user->id_user):?>
				                <p>
				                    <div class="alert alert-danger" role="alert">
				                        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>" class="alert-link">
				                            <?=__('Click here to enter your address.')?>
				                        </a>
				                    </div>
				                </p>
				            <?endif?>
				        <?elseif (Core::config('advertisement.map') AND Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()) :?>
				            <div class="alert alert-danger" role="alert">
				                <a href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))?>" class="alert-link">
				                    <?=__('Please set your Google API key on advertisement configuration.')?>
				                </a>
				            </div>
				        <?endif?>
					</article>
				</div>

				<?if($profile_ads!==NULL):?>
					<div class="pad_10">
						<div class="page-header">
							<h3><?=$user->name.' '._e(' advertisements')?></h3>
						</div>
						<?if (Theme::get('switch_rview')==1) : ?>
							<div class="clearfix">
								<div class="sort_opts btn-group pull-right">
									<a class="btn btn-sm btn-base-dark" id="gview_switch" href="#"><span class="glyphicon glyphicon-th-large"></span></a>
									<a class="btn btn-sm btn-base-dark" id="lview_switch" href="#"><span class="glyphicon glyphicon-th-list"></span></a>
								</div>
							</div>
						<?endif?>

						<div class="ad_listings">
							<ul class="ad_list list clearfix">
							<?$ci=0; foreach($profile_ads as $ads):?>
								<?if($ads->featured >= Date::unix2mysql(time())):?>
									<li class="ad_item clearfix featured_ad">
										<span class="feat_marker"><i class="glyphicon glyphicon-bookmark"></i></span>
								<?else:?>
									<li class="ad_item clearfix">
								<?endif?>

								<div class="ad_photo">
									<div class="ad_photo_inner">
										<a title="<?=HTML::chars($ads->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>">
											<?if($ads->get_first_image() !== NULL):?>
												<img src="<?=Core::imagefly($ads->get_first_image('image'),180,180)?>" class="img-responsive" alt="<?=HTML::chars($ads->title)?>" />
											<?else:?>
												<img data-src="holder.js/180x180?<?=str_replace('+', ' ', http_build_query(array('text' => $ads->category->translate_name(), 'size' => 14, 'auto' => 'yes')))?>" class="img-responsive" alt="<?=HTML::chars($ads->title)?>">
											<?endif?>
											<span class="gallery_only fm"><i class="glyphicon glyphicon-bookmark"></i></span>
											<?if ($ads->price!=0):?>
												<span class="gallery_only ad_gprice"><?=i18n::money_format( $ads->price, $ads->currency())?></span>
											<?elseif (($ads->price==0 OR $ads->price == NULL) AND core::config('advertisement.free')==1):?>
												<span class="gallery_only ad_gprice"><?=_e('Free');?></span>
											<?else:?>
												<span class="gallery_only ad_gprice">Check Listing</span>
											<?endif?>
										</a>
									</div>
								</div>

								<div class="ad_details">
									<div class="ad_details_inner">
										<h2>
											<a title="<?=HTML::chars($ads->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>"><?=$ads->title?></a>
										</h2>
										<p class="ad_meta clearfix">
										<?if ($ads->published!=0){?>
											<span><i class="glyphicon glyphicon-calendar"></i> <?=Date::format($ads->published, core::config('general.date_format'))?></span>
										<? }?>
										</p>
										<?if(core::config('advertisement.description')!=FALSE):?>
											<p class="ad_desc"><?=Text::limit_chars(Text::removebbcode($ads->description), 255, NULL, TRUE);?></p>
										<?endif?>
										<div class="ad_buttons">
											<?$visitor = Auth::instance()->get_user()?>
												<?if ($visitor != FALSE && $visitor->id_role == 10):?>
													<span class="ad_options">
														<a class="btn btn-warning" data-toggle="modal" data-dismiss="modal" href="<?=Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))?>#adcontrol<?=$ads->id_ad?>-modal"><i class="glyphicon glyphicon-cog"></i></a>
													</span>

													<div id="adcontrol<?=$ads->id_ad?>-modal" class="modal fade">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-body">
																	<a class="close" data-dismiss="modal" >Cancel</a>
																	<br />
																	<ul class="ad_controls_list">
																		<li><a class="btn btn-success" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ads->id_ad))?>"><i class="glyphicon glyphicon-edit"></i> <?=__("Edit");?></a></li>
																		<li><a class="btn btn-warning" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ads->id_ad))?>" onclick="return confirm('<?=__('Deactivate?')?>');"><i class="glyphicon glyphicon-off"></i> <?=_e("Deactivate");?></a></li>
																		<li><a class="btn btn-danger" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ads->id_ad))?>" onclick="return confirm('<?=__('Spam?')?>');"><i class="glyphicon glyphicon-fire"></i> <?=_e("Spam");?></a></li>
																		<li><a class="btn btn-danger" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ads->id_ad))?>" onclick="return confirm('<?=__('Delete?')?>');"><i class="glyphicon glyphicon-remove"></i> <?=_e("Delete");?></a></li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												<?elseif($visitor != FALSE && $visitor->id_user == $ads->id_user):?>
													<br>
													<span class="ad_options">
														<a class="btn btn-success" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ads->id_ad))?>"><i class="glyphicon glyphicon-edit"></i> <?=_e("Edit");?></a>
													</span>
												<?endif?>

												<?if ($ads->price!=0):?>
													<span class="ad_price">
														<a class="add-transition" title="<?=HTML::chars($ads->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>">
															<?=_e('Price');?>: <b><?=i18n::money_format( $ads->price, $ads->currency())?></b>
														</a>
													</span>
												<?elseif (($ads->price==0 OR $ads->price == NULL) AND core::config('advertisement.free')==1):?>
													<span class="ad_price">
													<a class="add-transition" title="<?=HTML::chars($ads->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>">
														<b><?=_e('Free');?></b>
													</a>
													</span>
												<?else:?>
													<span class="ad_price na">
														<a class="add-transition" title="<?=HTML::chars($ads->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>">Check Listing</a>
													</span>
												<?endif?>
										</div>
									</div>
								</div>
							</li>
								<? $ci++; if ($ci%4 == 0) echo '<div class="clear"></div>';?>
							<?endforeach?>
							</ul>
							<br>
							<div class="text-center">
								<?=$pagination?>
							</div>
						</div>
					</div>
				<?endif?>
			</div>

			<?=View::fragment('sidebar_front','sidebar')?>
        </div>
	</div>
</div>
