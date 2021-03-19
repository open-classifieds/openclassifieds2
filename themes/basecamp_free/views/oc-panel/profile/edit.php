<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="pad_10tb">
	<div class="container">
		<div class="col-xs-12">
			<?=Form::errors()?>
			<?if( Core::config('payment.stripe_connect')==1):?>
	            <div class="panel panel-default">
	                <div class="panel-heading" id="page-edit-profile">
	                    <h3 class="panel-title"><?=_e('Stripe Connect')?></h3>
	                    <p><?=sprintf(__('Sell your items with credit card using stripe. Our platform charges %s percentage, per transaction.'),Core::config('payment.stripe_appfee'))?></p>
	                </div>
	                <div class="panel-body">
	                    <div class="row">
	                        <div class="col-md-8">
	                            <?if ($user->stripe_user_id!=''):?>
	                                Stripe connected <?=$user->stripe_user_id?>
	                                <br>
	                                Reconnect:
	                                <br>
	                            <?endif?>
	                            <a class="btn btn-primary" href="<?=Route::url('default', array('controller'=>'stripe','action'=>'connect','id'=>'now'))?>">
	                                <span class="fas fa-money-bill" aria-hidden="true"></span> Connect with Stripe
	                            </a>

	                        </div>
	                    </div>
	                </div>
	            </div>
	        <?endif?>

            <?if( Core::config('payment.escrow_pay')==1):?>
                <div class="panel panel-default">
                    <div class="panel-heading" id="page-edit-profile">
                        <h3 class="panel-title"><?=_e('Escrow Pay')?></h3>
                        <p><?=__('Buy and sell items with Escrow')?></p>
                    </div>
                    <div class="panel-body">
                        <?if ($user->escrow_api_key!=''):?>
                            <div class="alert alert-success"><strong><?= __('Escrow connected.') ?></strong></div>
                        <?endif?>

                        <div class="row">
                            <div class="col-md-8">
                                <?= FORM::open(Route::url('oc-panel',array('controller'=>'escrow','action'=>'update_api_key')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>

                                <div class="form-group">
                                    <?= FORM::label('escrow_email', _e('Escrow email'), array('class'=>'col-xs-4 control-label', 'for'=>'escrow_email'))?>
                                    <div class="col-sm-8">
                                        <?= FORM::input('escrow_email', $user->escrow_email, array('class'=>'form-control', 'id'=>'escrow_email', 'type'=>'escrow_email' ,'required','placeholder'=>__('Email')))?>
                                        <p class="help-block small"><a href="https://www.escrow.com/signup-page" target="_blank"><?= __('Create an Escrow account.') ?></a></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= FORM::label('escrow_api_key', _e('API Key'), array('class'=>'col-xs-4 control-label', 'for'=>'escrow_api_key'))?>
                                    <div class="col-sm-8">
                                        <?= FORM::input('escrow_api_key', $user->escrow_api_key, array('class'=>'form-control', 'id'=>'escrow_api_key', 'required', 'placeholder'=>__('API Key')))?>
                                        <p class="help-block small"><a href="https://www.escrow.com/integrations/portal/api" target="_blank"><?= __('Create an API key.') ?></a></p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-offset-4 col-md-8">
                                        <button type="submit" class="btn btn-primary">
                                            <?if ($user->escrow_api_key == ''):?>
                                                <?=_e('Connect')?>
                                            <?else:?>
                                                <?=_e('Reconnect')?>
                                            <?endif?>
                                        </button>
                                    </div>
                                </div>
                                <?= FORM::close()?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif?>

            <div class="panel panel-default">
				<div class="panel-heading" id="page-edit-profile">
					<h3 class="panel-title"><?=_e('Edit Profile')?></h3>
				</div>

				<div class="panel-body">
					<div class="pad_10">
						<?= FORM::open(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')), array('class'=>'form', 'enctype'=>'multipart/form-data'))?>
							<div class="form-group clearfix">
								<?= FORM::label('name', _e('Name'), array('class'=>'col-xs-4 control-label', 'for'=>'name'))?>
								<div class="col-sm-8">
									<?= FORM::input('name', $user->name, array('class'=>'form-control', 'id'=>'name', 'required', 'placeholder'=>__('Name')))?>
								</div>
							</div>
							<div class="form-group clearfix">
								<?= FORM::label('email', _e('Email'), array('class'=>'col-xs-4 control-label', 'for'=>'email'))?>
								<div class="col-sm-8">
									<?= FORM::input('email', $user->email, array('class'=>'form-control', 'id'=>'email', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
								</div>
							</div>
                            <div class="form-group">
                                <?if (core::config('general.sms_auth')==TRUE):?>
                                	<label class="col-xs-4 control-label"><?=_e('Mobile phone number')?></label>
                                <?else:?>
                                	<?= FORM::label('phone', _e('Phone'), array('class'=>'col-xs-4 control-label', 'for'=>'phone'))?>
                                <?endif?>
                                <div class="col-sm-8">
                                    <?if (core::config('general.sms_auth')==TRUE):?>
                                    	<?= FORM::input('phone', $user->phone, array('class'=>'form-control', 'id'=>'phone', 'type'=>'phone' ,'required','placeholder'=>__('Phone'), 'data-country' => core::config('general.country')))?>
                                    	<span class="help-block"><?=_e('Used for SMS authentication.')?></span>
                                    <?else:?>
                                    	<?= FORM::input('phone', $user->phone, array('class'=>'form-control', 'id'=>'phone', 'type'=>'phone' ,'placeholder'=>__('Phone')))?>
                                    	<br><br>
                                    <?endif?>
                                </div>
                            </div>

                            <!-- location select -->
                            <?if(core::config('advertisement.location')):?>
                                <div class="form-group">
                                    <?= FORM::label('locations', _e('Location'), array('for'=>'location', 'class'=>'col-xs-4 control-label'))?>
                                    <div id="location-chained" class="col-sm-8 <?=($id_location === NULL) ? NULL : 'hidden'?>" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                        <div id="select-location-template" class="hidden">
                                            <select class="disable-select2 select-location" placeholder="<?=__('Pick a location...')?>"></select>
                                        </div>
                                    </div>
                                    <?if($id_location !== NULL):?>
                                        <div id="location-edit">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" placeholder="<?=$selected_location->translate_name()?>" disabled>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><?=_e('Select another')?></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?endif?>
                                    <input id="location-selected" name="location" value="<?=$id_location?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;"></input>
                                </div>
                            <?endif?>

                            <div class="form-group clearfix">
                                <?= FORM::label('address', _e('Address'), array('class'=>'col-xs-4 control-label', 'for'=>'address'))?>
                                <div class="col-sm-8">
                                    <?if(core::config('advertisement.map_pub_new')):?>
                                        <?if (Core::is_HTTPS()):?>
                                            <div class="input-group">
                                                <?= FORM::input('address', $user->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default locateme" type="button"><?=_e('Locate me')?></button>
                                                </span>
                                            </div>
                                        <?else:?>
                                            <?=FORM::input('address', $user->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                        <?endif?>
                                    <?else:?>
                                        <?= FORM::input('address', $user->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                    <?endif?>
                                </div>
                            </div>

                            <?if(core::config('advertisement.map_pub_new')):?>
                                <div class="form-group clearfix">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <div class="popin-map-container">
                                            <div class="map-inner" id="map"
                                                data-lat="<?=($user->latitude)? $user->latitude:core::config('advertisement.center_lat')?>"
                                                data-lon="<?=($user->longitude)? $user->longitude:core::config('advertisement.center_lon')?>"
                                                data-zoom="<?=core::config('advertisement.map_zoom')?>"
                                                style="height:200px;max-width:400px;margin-bottom:5px;">
                                            </div>
                                        </div>
                                        <input type="hidden" name="latitude" id="profile-latitude" value="<?=$user->latitude?>" <?=is_null($user->latitude) ? 'disabled': NULL?>>
                                        <input type="hidden" name="longitude" id="profile-longitude" value="<?=$user->longitude?>" <?=is_null($user->longitude) ? 'disabled': NULL?>>
                                    </div>
                                </div>
                            <?endif?>

							<div class="form-group clearfix">
								<?= FORM::label('description', _e('Description'), array('class'=>'col-xs-4 control-label', 'for'=>'description'))?>
								<div class="col-sm-8">
                                    <?=FORM::textarea('description', $user->description, array(
                                    'placeholder' => '',
                                    'rows' => 3, 'cols' => 50,
                                    'class' => 'form-control',
                                    'id' => 'description',
                                ))?>
                                </div>
							</div>
							<?foreach($custom_fields as $name=>$field):?>
								<?if($name!='verifiedbadge' OR Auth::instance()->get_user()->is_admin() OR Auth::instance()->get_user()->is_moderator()):?>
									<div class="form-group clearfix" id="cf_new">
										<?$cf_name = 'cf_'.$name?>
											<?if($field['type'] == 'select' OR $field['type'] == 'radio') {
												$select = array(''=>'');
												foreach ($field['values'] as $select_name) {
													$select[$select_name] = $select_name;
												}
											} else $select = $field['values']?>
												<?= FORM::label('cf_'.$name, $field['label'], array('class'=>'col-xs-4 control-label', 'for'=>'cf_'.$name))?>
												<div class="col-sm-8">
													<?=Form::cf_form_field('cf_'.$name, array(
													'display'   => $field['type'],
													'label'     => $field['label'],
													'tooltip'   => (isset($field['tooltip']))? $field['tooltip'] : "",
													'default'   => $user->$cf_name,
													'options'   => (!is_array($field['values']))? $field['values'] : $select,
													'required'  => $field['required'],
													))?>
												</div>
									</div>
								<?endif?>
							<?endforeach?>

							<div class="form-group clearfix">
								<div class="col-md-offset-4 col-md-8">
									<div class="checkbox">
										<label><input type="checkbox" name="subscriber" value="1" <?=($user->subscriber)?'checked':NULL?> > <?=_e('Subscribed to emails')?></label>
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="text-right">
									<button type="submit" class="btn btn-success"><?=_e('Update')?></button>
								</div>
							</div>
						<?= FORM::close()?>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading" id="page-edit-profile">
					<h3 class="panel-title"><?=_e('Change password')?></h3>
				</div>
				<div class="panel-body">
					<div class="pad_10">
						<form method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'changepass'))?>">

						<div class="form-group clearfix">
							<label class="col-xs-4 control-label"><?=_e('New password')?></label>
							<div class="col-sm-8">
								<input class="form-control" type="password" name="password1" placeholder="<?=__('Password')?>">
							</div>
						</div>
						<div class="form-group clearfix">
							<label class="col-xs-4 control-label"><?=_e('Repeat password')?></label>
								<div class="col-sm-8">
									<input class="form-control" type="password" name="password2" placeholder="<?=__('Password')?>">
									<p class="help-block">
										<?=_e('Type your password twice to change')?>
									</p>
								</div>
						</div>
						<div class="form-group">
							<div class="text-right">
								<button type="submit" class="btn btn-success"><?=_e('Update')?></button>
							</div>
						</div>

						</form>
					</div>
				</div>
			</div>

	        <?if( Core::config('general.google_authenticator')==TRUE):?>
	        <div class="panel panel-default">
	            <div class="panel-heading" id="page-edit-profile">
	                <h3 class="panel-title"><?=_e('2 Step Authentication')?></h3>
	            </div>
	            <div class="panel-body">
	                <div class="row">
	                    <div class="col-md-12">
	                        <?if ($user->google_authenticator!=''):?>
	                            <p><img src="<?=$user->google_authenticator_qr()?>"></p>
	                            <p><?=_e('Google Authenticator Code')?>: <?=$user->google_authenticator?></p>
	                            <p>
	                                <a class="btn btn-warning" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'disable'))?>">
	                                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=_e('Disable')?>
	                                </a>
	                            </p>
	                        <?else:?>
	                            <?
	                                require Kohana::find_file('vendor', 'GoogleAuthenticator');
	                                $ga = new PHPGangsta_GoogleAuthenticator();
	                                if( ($ga_secret_temp  = Session::instance()->get('ga_secret_temp'))==NULL )
	                                    Session::instance()->set('ga_secret_temp',$ga->createSecret());
	                            ?>
	                            <p><img src="<?=$ga->getQRCodeGoogleUrl(Kohana::$base_url,Session::instance()->get('ga_secret_temp'))?>"></p>
	                            <p>
	                                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'enable'))?>">
	                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=_e('Enable')?>
	                                </a>
	                            </p>
	                        <?endif?>
	                        <hr>
	                        <p><?=_e('2 step authentication provided by Google Authenticator.')?></p>
	                        <div class="btn-group">
	                            <a class="btn btn-default" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"><i class="fa fa-android"></i> Android</a>
	                            <a class="btn btn-default" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8"><i class="fa fa-apple"></i> iOS</a>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <?endif?>

			<div class="panel panel-default">
	            <div class="panel-heading" id="page-edit-profile">
	                <h3 class="panel-title"><?=_e('Profile pictures')?></h3>
	            </div>
	            <div class="panel-body">
	                <div class="row">
	                    <div class="col-md-12">
	                        <form enctype="multipart/form-data" class="upload_image" method="post" action="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'image'))?>">
	                            <?=Form::errors()?>
	                            <div class="form-group images"
	                                data-max-image-size="<?=core::config('image.max_image_size')?>"
	                                data-image-width="<?=core::config('image.width')?>"
	                                data-image-height="<?=core::config('image.height') ? core::config('image.height') : 0?>"
	                                data-image-quality="<?=core::config('image.quality')?>"
	                                data-swaltext="<?=sprintf(__('Is not of valid size. Size is limited to %s MB per image'),core::config('image.max_image_size'))?>">
	                                <?$images = $user->get_profile_images()?>
	                                <?if($images):?>
	                                    <div class="row">
	                                        <?foreach ($images as $key => $image):?>
	                                            <div id="img<?=$key?>" class="col-md-4 edit-image">
	                                                <a><img src="<?=$image?>" class="img-rounded thumbnail img-responsive"></a>
	                                                <?if ($key > 0) :?>
	                                                    <button class="btn btn-danger index-delete img-delete"
	                                                            data-title="<?=__('Are you sure you want to delete?')?>"
	                                                            data-btnOkLabel="<?=__('Yes, definitely!')?>"
	                                                            data-btnCancelLabel="<?=__('No way!')?>"
	                                                            type="submit"
	                                                            name="img_delete"
	                                                            value="<?=$key?>"
	                                                            href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'image'))?>">
	                                                            <?=_e('Delete')?>
	                                                    </button>
	                                                <?endif?>
	                                                <?if ($key > 1) :?>
	                                                    <button class="btn btn-info img-primary"
	                                                        type="submit"
	                                                        name="primary_image"
	                                                        value="<?=$key?>"
	                                                        href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'image'))?>"
	                                                        action="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'image'))?>"
	                                                    >
	                                                            <?=_e('Primary image')?>
	                                                    </button>
	                                                <?endif?>
	                                            </div>
	                                        <?endforeach?>
	                                    </div>
	                                <?endif?>
	                            </div>
	                            <?if (core::config('advertisement.num_images') > core::count($images)):?>
	                                <hr>
	                                <div class="form-group">
	                                    <h5><?=_e('Add image')?></h5>
	                                    <div>
	                                        <?for ($i = 0; $i < (core::config('advertisement.num_images') - core::count($images)); $i++):?>
	                                            <div class="fileinput fileinput-new <?=($i >= 1) ? 'hidden' : NULL?>" data-provides="fileinput">
	                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
	                                                <div>
	                                                    <span class="btn btn-default btn-file">
	                                                        <span class="fileinput-new"><?=_e('Select')?></span>
	                                                        <span class="fileinput-exists"><?=_e('Edit')?></span>
	                                                        <input type="file" name="<?='image'.$i?>" id="<?='fileInput'.$i?>" accept="<?='image/'.str_replace(',', ', image/', rtrim(core::config('image.allowed_formats'),','))?>">
	                                                    </span>
	                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"><?=_e('Delete')?></a>
	                                                </div>
	                                            </div>
	                                        <?endfor?>
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <button type="submit" class="btn btn-success"><?=_e('Upload')?></button>
	                                </div>
	                            <?endif?>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
		</div>
	</div>
</div>
