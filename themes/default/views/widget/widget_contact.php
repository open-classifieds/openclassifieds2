<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
	<div class="panel-heading">
		<h3 class="panel-title"><?=$widget->text_title?></h3>
	</div>
<?endif?>

<div class="panel-body">
	<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'user_contact', 'id'=>$widget->id_ad)), array('class'=>'form-horizontal ', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
	
			<?if (!Auth::instance()->logged_in()):?>
			<div class="form-group">
				<div class="col-xs-12">
				<?= FORM::label('name', __('Name'), array('class'=>'control-label', 'for'=>'name'))?>
					<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class' => 'form-control', 'id' => 'name', 'required'))?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-12">
				<?= FORM::label('email', __('Email'), array('class'=>'control-label', 'for'=>'email'))?>
					<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class' => 'form-control', 'id' => 'email', 'type'=>'email','required'))?>
				</div>
			</div>
			<?endif?>
	
			<div class="form-group">
				<div class="col-xs-12">
				<?= FORM::label('subject', __('Subject'), array('class'=>'control-label', 'for'=>'subject'))?>
					<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class' => 'form-control', 'id' => 'subject'))?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-12">
				<?= FORM::label('message', __('Message'), array('class'=>'control-label', 'for'=>'message'))?>
					<?= FORM::textarea('message', "", array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>	
					</div>
			</div>
			<!-- file to be sent-->
			<?if(core::config('advertisement.upload_file')):?>
			<div class="form-group">
				<div class="col-xs-12">
					<?= FORM::label('file', __('File'), array('class'=>'control-label', 'for'=>'file'))?>
					<?= FORM::file('file', array('placeholder' => __('File'), 'class' => 'input-xlarge', 'id' => 'file'))?>
				</div>
			</div>
			<?endif?>
	
			<?if (core::config('advertisement.captcha') != FALSE):?>
			<div class="form-group">
				<div class="col-xs-12">
					<?if (Core::config('general.recaptcha_active')):?>
						<?=Captcha::recaptcha_display()?>
						<div id="recaptcha2"></div>
					<?else:?>
						<?=__('Captcha')?>*:<br />
						<?=captcha::image_tag('contact')?><br />
						<?= FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
					<?endif?>
				</div>
			</div>
			<?endif?>
	
				<div class="modal-footer">	
				<?= FORM::button('submit', __('Send Message'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'user_contact' , 'id'=>$widget->id_ad))))?>
			</div>
		</fieldset>
	<?= FORM::close()?>
</div>
