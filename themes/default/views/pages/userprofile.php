<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h3><?= __('User Profile')?></h3>
</div>
<?if(is_file(DOCROOT."images/users/".$user->id_user.".png")):?>
<div class="row">
	<div class="col-md-3">
		<a class="thumbnail">
			<img src="<?=URL::base('http')?>images/users/<?=$user->id_user?>.png" class="img-rounded" alt="<?=HTML::chars(__('Profile Picture'))?>" height="200" />
		</a>
	</div>
</div>

	<?endif?>
<div class="page-header">
	<article class="list well clearfix">
		<h3><?=$user->name?></h3>
		<p><b><?=__('Created')?>: </b><?= Date::format($user->created, core::config('general.date_format')) ?></p>
		<p><b><?=__('Last Login')?>: </b><?= Date::format($user->last_login, core::config('general.date_format'))?></p>

		<!-- Popup contact form -->
			<button class="btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i> <?=__('Send Message')?></button>
			<div id="contact-modal" class="modal hide fade">
	        	<div class="modal-header">
	         		<a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
					<h3><?=__('Contact')?></h3>
	        	</div>
	        
	            <div class="modal-body">
					
						<?=Form::errors()?>
						
						<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact', 'id'=>$user->id_user)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
						<fieldset>
							<div class="form-group">
								<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
								<div class="col-md-4 ">
									<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => '', 'id' => 'name', 'required'))?>
								</div>
							</div>
							<div class="form-group">
								
								<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
								<div class="col-md-4 ">
									<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class' => '', 'id' => 'email', 'type'=>'email','required'))?>
								</div>
							</div>
							<div class="form-group">
								
								<?= FORM::label('subject', __('Subject'), array('class'=>'control-label', 'for'=>'subject'))?>
								<div class="col-md-4 ">
									<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class' => '', 'id' => 'subject'))?>
								</div>
							</div>
							<div class="form-group">
								<?= FORM::label('message', __('Message'), array('class'=>'control-label', 'for'=>'message'))?>
								<div class="col-md-4">
									<?= FORM::textarea('message', "", array('class'=>'', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>	
									</div>
							</div>
							
							<?if (core::config('advertisement.captcha') != FALSE):?>
							<div class="form-group">
								<div class="col-md-4">
									<?=__('Captcha')?>*:<br />
									<?=captcha::image_tag('contact')?><br />
									<?= FORM::input('captcha', "", array('class' => '', 'id' => 'captcha', 'required'))?>
								</div>
							</div>
							<?endif?>
	  						
	  						<div class="modal-footer">	
								<?= FORM::button('submit', __('Send Message'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'userprofile_contact' , 'id'=>$user->id_user))))?>
							</div>
						</fieldset>
						<?= FORM::close()?>
	    		</div>
			</div>
	</article>
</div>
<div class="page-header">
	<h3><?=$user->name.' '.__(' advertisements')?></h3>

	<?if($profile_ads!==NULL):?>
		<?foreach($profile_ads as $ads):?>			 
		<?if($ads->featured >= Date::unix2mysql(time())):?>
	    	<article class="list well clearfix featured">
	    <?else:?>
		<article class="list well clearfix">
		<?endif?>
		
			<h4><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ads->category->seoname,'seotitle'=>$ads->seotitle))?>"><?=$ads->title?></a></h4>
			<p><strong>Description: </strong><?=Text::removebbcode($ads->description)?><p>
			<p><b><?=__('Publish Date');?>:</b> <?= Date::format($ads->published, core::config('general.date_format'))?><p>
		

		<?$visitor = Auth::instance()->get_user()?>
		
		<?if ($visitor != FALSE && $visitor->id_role == 10):?>
			<br />
			<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ads->id_ad))?>"><?=__("Edit");?></a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ads->id_ad))?>" 
				onclick="return confirm('<?=__('Deactivate?')?>');"><?=__("Deactivate");?>
			</a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ads->id_ad))?>" 
				onclick="return confirm('<?=__('Spam?')?>');"><?=__("Spam");?>
			</a> |
			<a href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ads->id_ad))?>" 
				onclick="return confirm('<?=__('Delete?')?>');"><?=__("Delete");?>
			</a>

			<?elseif($visitor != FALSE && $visitor->id_user == $ads->id_user):?>
			<br/>
			<a href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ads->id_ad))?>"><?=__("Edit");?></a> 
		<?endif?>
		</article>
		<?endforeach?>
	<?endif?>
</div>
	
