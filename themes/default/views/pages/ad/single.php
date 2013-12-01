<?php defined('SYSPATH') or die('No direct script access.');?>

	<?if ($ad->status != Model_Ad::STATUS_PUBLISHED && $permission === FALSE && ($ad->id_user != $user)):?>

	<div class="page-header">
		<h1><?= __("This advertisement doesn't exist, or is not yet published!")?></h1>
	</div>

	<?else:?>
	<?=Form::errors()?>
	<div class="page-header">
		<h1><?= $ad->title;?></h1>
	</div>

	<!-- PAYPAL buttons to featured and to top -->
	<?if ((Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == 10 ) OR 
		(Auth::instance()->logged_in() AND $ad->user->id_user == Auth::instance()->get_user()->id_user)):?>
	<?if((core::config('payment.pay_to_go_on_top') > 0  
			&& core::config('payment.to_top') != FALSE )
			OR (core::config('payment.pay_to_go_on_feature') > 0 
			&& core::config('payment.to_featured') != FALSE)):?>
		<div id="advise" class="well advise clearfix">
			<?if(core::config('payment.pay_to_go_on_top') > 0 && core::config('payment.to_top') != FALSE):?>
				<p class="text-info"><?=__('Your Advertisement can go on top again! For only ').core::config('payment.pay_to_go_on_top').' '.core::config('payment.paypal_currency');?></p>
				<a class="btn btn-mini btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>"><?=__('Go Top!')?></a>
			<?endif?>
			<?if(core::config('payment.pay_to_go_on_feature') > 0 && core::config('payment.to_featured') != FALSE):?>
				<p class="text-info"><?=__('Your Advertisement can go to featured! For only ').core::config('payment.pay_to_go_on_feature').' '.core::config('payment.paypal_currency');?></p>
				<a class="btn btn-mini btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>"><?=__('Go Featured!')?></a>
			<?endif?>
		</div>
	<?endif?>
	<?endif?>
	<!-- end paypal button -->
	<div class="row">
	<?$images = $ad->get_images()?>
	<?if($images):?>
		<div id="gallery" class="col-md-9">
			
				<?foreach ($images as $path => $value):?>
				<?if( isset($value['thumb']) AND isset($value['image']) ):?>
					<div class="col-md-3">
					<a href="<?=URL::base('http')?><?= $value['image']?>" class="thumbnail gallery-item" data-gallery>
					<img src="<?=URL::base('http')?><?= $value['thumb']?>" class="img-rounded" alt="<?= $value['image']?>" />
					</a>
					</div>
				<?endif?>	
				<?endforeach?>
			
		</div>
	</div>
	<?endif?>

        <div class="well ">
            <?if ($ad->price>0):?>
            <span class="label label-danger"><?= __('Price');?> : <?=i18n::money_format( $ad->price)?></span>
            <?endif?>
            <a class="label label-default" href="<?=Route::url('profile',  array('seoname'=>$ad->user->seoname))?>"><?=$ad->user->name?></a>
            <div class="pull-right">
                <span class="label label-info"><?= Date::format($ad->published, core::config('general.date_format'))?></span>
                <span class="label label-info"><?=$hits?> <?=__('Hits')?></span> 
            </div>    
        </div>

        <br/>

        <div>
            <?= Text::bb2html($ad->description,TRUE)?>
            <?if (Valid::url($ad->website)):?>
            <p><a href="<?=$ad->website?>" rel="nofollow"><?=$ad->website?></a></p>
            <?endif?>
        </div>  
        
	    <?if(core::config('payment.paypal_seller') AND $ad->price != NULL AND $ad->price != 0):?>
	    	<a class="btn btn-primary" type="button" type="post" href="https://www.paypal.com/cgi-bin/webscr?business=<?=$ad->user->email?>&cmd=_xclick&currency_code=<?=core::config("payment.paypal_currency")?>&amount=<?=number_format($ad->price, 2, ".",'')?>&item_name=<?=$ad->title?>"><?=__('Buy Now')?></a>
	    <?endif?>

        <a class="btn btn-primary" target="_blank" 
        href="http://panel.adserum.com/new-advertisement.html?name=<?=$ad->user->name?>&title=<?=$ad->title?>&desc=<?=$ad->description?>&url=<?=core::config('general.base_url')?><?=substr($_SERVER['REQUEST_URI'],1)?>&durl=<?=core::config('general.base_url')?>">
        <i class="glyphicon glyphicon-globe"></i> <?=__('Promote at Adserum')?></a>
        
        <hr />
        <?if ($ad->can_contact()):?>
		<button class="btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal"><i class="glyphicon glyphicon-envelope"></i> <?=__('Send Message')?></button>

        <?if (core::config('advertisement.phone')==1 AND strlen($ad->phone)>1):?>
            <a class="btn btn-warning" href="tel:<?=$ad->phone?>"><?=__('Phone').': '.$ad->phone?></a>
        <?endif?>
		
		<div id="contact-modal" class="modal fade">
			<div class="modal-dialog">
	    		<div class="modal-content">
			    	<div class="modal-header">
			     		<a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
						<h3><?=__('Contact')?></h3>
			    	</div>
		            <div class="modal-body">
						
							<?=Form::errors()?>
							
							<?= FORM::open(Route::url('default', array('controller'=>'contact', 'action'=>'user_contact', 'id'=>$ad->id_ad)), array('class'=>'form-horizontal well', 'enctype'=>'multipart/form-data'))?>
							<fieldset>
								<div class="form-group">
								<?= FORM::label('name', __('Name'), array('class'=>'col-sm-2 control-label', 'for'=>'name'))?>
									<div class="col-md-4 ">
										<?= FORM::input('name', '', array('placeholder' => __('Name'), 'class'=>'form-control', 'id' => 'name', 'required'))?>
									</div>
								</div>
								<div class="form-group">
								<?= FORM::label('email', __('Email'), array('class'=>'col-sm-2 control-label', 'for'=>'email'))?>
									<div class="col-md-4 ">
										<?= FORM::input('email', '', array('placeholder' => __('Email'), 'class'=>'form-control', 'id' => 'email', 'type'=>'email','required'))?>
									</div>
								</div>
								<div class="form-group">
								<?= FORM::label('subject', __('Subject'), array('class'=>'col-sm-2 control-label', 'for'=>'subject'))?>
									<div class="col-md-4 ">
										<?= FORM::input('subject', "", array('placeholder' => __('Subject'), 'class'=>'form-control', 'id' => 'subject'))?>
									</div>
								</div>
								<div class="form-group">
								<?= FORM::label('message', __('Message'), array('class'=>'col-sm-2 control-label', 'for'=>'message'))?>
									<div class="col-md-6">
										<?= FORM::textarea('message', "", array('class'=>'form-control', 'placeholder' => __('Message'), 'name'=>'message', 'id'=>'message', 'rows'=>2, 'required'))?>	
									</div>
								</div>
								<!-- file to be sent-->
								<?if(core::config('advertisement.upload_file')):?>
								<div class="form-group">
								<?= FORM::label('file', __('File'), array('class'=>'col-sm-2 control-label', 'for'=>'file'))?>
									<div class="col-md-6">
										<?= FORM::file('file', array('placeholder' => __('File'), 'class'=>'form-control', 'id' => 'file'))?>
									</div>
								</div>
								<?endif?>
								<?if (core::config('advertisement.captcha') != FALSE):?>
								<div class="form-group">
									<div class="col-md-4">
										<?=__('Captcha')?>*:<br />
										<?=captcha::image_tag('contact')?><br />
										<?= FORM::input('captcha', "", array('class'=>'form-control', 'id' => 'captcha', 'required'))?>
									</div>
								</div>
								<?endif?>
		  						<div class="modal-footer">	
									<?= FORM::button('submit', __('Contact Us'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'contact', 'action'=>'user_contact' , 'id'=>$ad->id_ad))))?>
								</div>
							</fieldset>
							<?= FORM::close()?>
		    			</div>
		    		</div>
	    		</div>
    		</div>
        <?endif?>

        <?=$ad->map()?>

	<?endif?>
	<!-- modal-gallery is the modal dialog used for the image gallery -->
	<div class="modal fade" id="modal-gallery">
		<div class="modal-dialog">
	    	<div class="modal-content">
			    <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    </div>
			    <div class="modal-body"><div class="modal-image"></div></div>
			    <div class="modal-footer">
			        <a class="btn btn-info modal-prev"><i class="glyphicon glyphicon-arrow-left glyphicon"></i> <?=__('Previous')?></a>
			        <a class="btn btn-primary modal-next"><?=__('Next')?> <i class="glyphicon glyphicon-arrow-right glyphicon"></i></a>
			        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="glyphicon glyphicon-play glyphicon"></i> <?=__('Slideshow')?></a>
			        <a class="btn modal-download" target="_blank"><i class="glyphicon glyphicon-download"></i> <?=__('Download')?></a>
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
	                <div class="modal-header">
	                    <button type="button" class="close" aria-hidden="true">&times;</button>
	                    <h4 class="modal-title"></h4>
	                </div>
	                <div class="modal-body next"></div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-default pull-left prev">
	                        <i class="glyphicon glyphicon-chevron-left"></i>
	                    </button>
	                    <button type="button" class="btn btn-primary pull-left next">
	                        <i class="glyphicon glyphicon-chevron-right"></i>
	                    </button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<?=$ad->disqus()?>
