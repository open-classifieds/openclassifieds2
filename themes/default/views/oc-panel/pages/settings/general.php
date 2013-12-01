<?php defined('SYSPATH') or die('No direct script access.');?>


 <?=Form::errors()?>
<div class="page-header">
	<h1><?=__('General Configuration')?></h1>
    <p class="">
        <?=__('General site settings.')?>
        <a class="btn btn-default pull-right" href="<?=Route::url('oc-panel',array('controller'=>'config'))?>"><?=__('All configurations')?></a>

    </p>
</div>

<div class="well">
<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
	<fieldset>
		
		
        <div class="form-group">
            <?= FORM::label($forms['maintenance']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/10/11/activate-access-terms-alert/'>".__("Maintenance Mode")."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['maintenance']['key']))?>
            <div class="col-sm-4">
                <?= FORM::select($forms['maintenance']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['maintenance']['value'], array(
                'placeholder' => "TRUE or FALSE", 
                'class' => 'tips form-control', 
                'id' => $forms['maintenance']['key'], 
                'data-content'=> __("Enables the site to maintenance"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Maintenance Mode"),
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['landing_page']['key'], __('Landing page'), array('class'=>'control-label col-sm-3', 'for'=>$forms['landing_page']['key']))?>
            <div class="col-sm-4">
                <?= FORM::select($forms['landing_page']['key'], array('{"controller":"home","action":"index"}'=>'HOME','{"controller":"ad","action":"listing"}'=>'LISTING'), $forms['landing_page']['value'], array(
                'class' => 'tips form-control', 
                'id' => $forms['landing_page']['key'], 
                'data-content'=> __("It changes landing page of website"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Landing page"),
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['site_name']['key'], __('Site name'), array('class'=>'control-label col-sm-3', 'for'=>$forms['site_name']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms['site_name']['key'], $forms['site_name']['value'], array(
                'placeholder' => 'Open-classifieds', 
                'class' => 'tips form-control', 
                'id' => $forms['site_name']['key'],
                'data-content'=> __("Here you can declare your display name. This is seen by everyone!"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Site Name"), 
                ))?> 
            </div>
        </div>

        <div class="form-group">
            <?= FORM::label($forms['site_description']['key'], __('Site description'), array('class'=>'control-label col-sm-3', 'for'=>$forms['site_description']['key']))?>
            <div class="col-sm-4">
                <?= FORM::input($forms['site_description']['key'], $forms['site_description']['value'], array(
                'placeholder' => '', 
                'class' => 'tips form-control', 
                'id' => $forms['site_description']['key'],
                ))?> 
            </div>
        </div>

		<div class="form-group">
			<?= FORM::label($forms['base_url']['key'], __('Base URL'), array('class'=>'control-label col-sm-3', 'for'=>$forms['base_url']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms['base_url']['key'], $forms['base_url']['value'], array(
				'placeholder' => "http://foo.com/", 
				'class' => 'tips form-control', 
				'id' => $forms['base_url']['key'],
				'data-content'=> __("Is a base path of your site (e.g. http://open-classifieds.com/). Everything else is built based on this field."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Base URL path"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms['moderation']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/08/30/how-to-earn-money/'>".__('Moderation')."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['moderation']['key']))?>
			<div class="col-sm-4">
				<?= FORM::select($forms['moderation']['key'], array(0=>"Post directly",1=>"Moderation on",2=>"Payment on",3=>"Email confirmation on", 4=>"Email confirmation with Moderation", 5=>"Payment with Moderation"), $forms['moderation']['value'], array(
				'placeholder' => $forms['moderation']['value'], 
				'class' => 'tips form-control ', 
				'id' => $forms['moderation']['key'],
				'data-content'=> __("Moderation is how you control newly created advertisements. You can set it up to fulfill your needs. For example, 'Post directly' will enable new ads to be posted directly, and get published as soon they submit."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Moderation col-sm-4"), 
				))?> 
			</div>
		</div>
		
		<div class="form-group">
			<?= FORM::label($forms['advertisements_per_page']['key'], __('Advertisements per page'), array('class'=>'control-label col-sm-3', 'for'=>$forms['advertisements_per_page']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms['advertisements_per_page']['key'], $forms['advertisements_per_page']['value'], array(
				'placeholder' => "20", 
				'class' => 'tips form-control', 
				'id' => $forms['advertisements_per_page']['key'], 
				'data-content'=> __("This is to control how many advertisements are being displayed per page. Insert an integer value, as a number limit."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Number of ads per page"),
				))?> 
			</div>
		</div>
		<div class="form-group">
           <?= FORM::label($forms['feed_elements']['key'], __('Advertisements in RSS'), array('class'=>'control-label col-sm-3', 'for'=>$forms['feed_elements']['key']))?>
           <div class="col-sm-4">
               <?= FORM::input($forms['feed_elements']['key'], $forms['feed_elements']['value'], array(
               'placeholder' => "20", 
               'class' => 'tips form-control', 
               'id' => $forms['feed_elements']['key'], 
               'data-content'=> __("Number of Ads"),
               'data-trigger'=>"hover",
               'data-placement'=>"right",
               'data-toggle'=>"popover",
               'data-original-title'=>__("How many ads are going to appear in the RSS of your site."),
               ))?> 
           </div>
       </div>
       <div class="form-group">
           <?= FORM::label($forms['map_elements']['key'], __('Advertisements in Map'), array('class'=>'control-label col-sm-3', 'for'=>$forms['map_elements']['key']))?>
           <div class="col-sm-4">
               <?= FORM::input($forms['map_elements']['key'], $forms['map_elements']['value'], array(
               'placeholder' => "20", 
               'class' => 'tips form-control', 
               'id' => $forms['map_elements']['key'], 
               'data-content'=> __("Number of Ads"),
               'data-trigger'=>"hover",
               'data-placement'=>"right",
               'data-toggle'=>"popover",
               'data-original-title'=>__("How many ads are going to appear in the map of your site."),
               ))?> 
           </div>
       </div>
		<div class="form-group">
			
                <?= FORM::label($forms['number_format']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/08/06/how-to-currency-format/'>".__('Money format')."</a>", array('class'=>'control-label col-sm-3','for'=>$forms['number_format']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms['number_format']['key'], $forms['number_format']['value'], array(
				'placeholder' => "20", 
				'class' => 'tips form-control', 
				'id' => $forms['number_format']['key'],
				'data-content'=> __("Number format is how you want to display numbers related to advertisements. More specific advertisement price. Every country have a specific way of dealing with decimal digits."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Decimal representation"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms['date_format']['key'], __('Date format'), array('class'=>'control-label col-sm-3', 'for'=>$forms['date_format']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms['date_format']['key'], $forms['date_format']['value'], array(
				'placeholder' => "d/m/Y", 
				'class' => 'tips form-control', 
				'id' => $forms['date_format']['key'], 
				'data-content'=> __("Each advertisement have a publish date. By selecting format, you can change how it is shown on your website."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Date format"),
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms['analytics']['key'], __("Analytics Tracking ID"), array('class'=>'control-label col-sm-3', 'for'=>$forms['analytics']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms['analytics']['key'], $forms['analytics']['value'], array(
				'placeholder' => 'UA-XXXXX-YY', 
				'class' => 'tips form-control', 
				'id' => $forms['analytics']['key'],
				'data-content'=> __("Once logged in your Google Analytics, you can find the Tracking ID in the Accounts List or in the Property Settings"),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Analytics Tracking ID"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['allowed_formats']['key'], __('Allowed image formats'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['allowed_formats']['key']))?>
			<div class="col-sm-4">
				<?= FORM::select("allowed_formats[]", array('jpeg'=>'jpeg','jpg'=>'jpg','png'=>'png','raw'=>'raw','gif'=>'gif'), explode(',', $forms_img['allowed_formats']['value']), array(
				'placeholder' => $forms_img['allowed_formats']['value'],
				'multiple' => 'true',
				'class' => 'tips form-control', 
				'id' => $forms_img['allowed_formats']['key'],
				'data-content'=> __("Set this up to restrict image formats that are being uploaded to your server."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Allowed image formats"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['max_image_size']['key'], __('Max image size'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['max_image_size']['key']))?>
			<div class="col-sm-4">
				<div class="input-group">
					<?= FORM::input($forms_img['max_image_size']['key'], $forms_img['max_image_size']['value'], array(
					'placeholder' => "5", 
					'class' => 'tips form-control span', 
					'id' => $forms_img['max_image_size']['key'],
					'data-content'=> __("Control the size of images being uploaded. Enter an integer value to set maximum image size in mega bites(Mb)."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image size in mega bites(Mb)"), 
					))?>
					<span class="input-group-addon">MB</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['height']['key'], __('Image height'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['height']['key']))?>
			<div class="col-sm-4">
				<div class="input-group">
					<?= FORM::input($forms_img['height']['key'], $forms_img['height']['value'], array(
					'placeholder' => "700", 
					'class' => 'tips form-control', 
					'id' => $forms_img['height']['key'], 
					'data-content'=> __("Each image is resized when uploaded. This is the height of big image. Note: you can leave this field blank to set AUTO height resize."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image height in pixels(px)"),
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['width']['key'], __('Image width'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['width']['key']))?>
			<div class="col-sm-4">
				<div class="input-group">
					<?= FORM::input($forms_img['width']['key'], $forms_img['width']['value'], array(
					'placeholder' => "1024", 
					'class' => 'tips form-control', 
					'id' => $forms_img['width']['key'],
					'data-content'=> __("Each image is resized when uploaded. This is the width of big image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Image width in pixels(px)"), 
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['height_thumb']['key'], __('Thumb height'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['height_thumb']['key']))?>
			<div class="col-sm-4">
				<div class="input-group">
					<?= FORM::input($forms_img['height_thumb']['key'], $forms_img['height_thumb']['value'], array(
					'placeholder' => "200", 
					'class' => 'tips form-control', 
					'id' => $forms_img['height_thumb']['key'],
					'data-content'=> __("Thumb is a small image resized to fit certain elements. This is height of this image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb height in pixels(px)"), 
					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['width_thumb']['key'], __('Thumb width'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['width_thumb']['key']))?>
			<div class="col-sm-4">
				<div class="input-group">
					<?= FORM::input($forms_img['width_thumb']['key'], $forms_img['width_thumb']['value'], array(
					'placeholder' => "200", 
					'class' => 'tips form-control', 
					'id' => $forms_img['width_thumb']['key'],
					'data-content'=> __("Thumb is a small image resized to fit certain elements. This is width of this image."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Thumb width in pixels(px)"),

					))?>
					<span class="input-group-addon">px</span>
				</div> 
			</div>
		</div>
        <div class="form-group">
            <?= FORM::label($forms_img['quality']['key'], __('Image quality'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['quality']['key']))?>
            <div class="col-sm-4">
                <div class="input-group">
                    <?= FORM::input($forms_img['quality']['key'], $forms_img['quality']['value'], array(
                    'placeholder' => "95", 
                    'class' => 'tips form-control', 
                    'id' => $forms_img['quality']['key'],
                    'data-content'=> __("Choose the quality of the stored images (1-100% of the original)."),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Image Quality"),

                    ))?>
                    <span class="input-group-addon">%</span>
                </div> 
            </div>
        </div>
        
        <div class="form-group">
            <?= FORM::label($forms_img['watermark']['key'], __('Watermark'), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['watermark']['key']))?>
            <div class="col-sm-4">
                <?= FORM::select($forms_img['watermark']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms_img['watermark']['value'], array(
                'placeholder' => "TRUE or FALSE", 
                'class' => 'tips form-control', 
                'id' => $forms_img['watermark']['key'], 
                'data-content'=> __("Appends watermark to images"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Watermark"),
                ))?> 
            </div>
        </div>
        
        <div class="form-group">
			<?= FORM::label($forms_img['watermark_path']['key'], __("Watermark path"), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['watermark_path']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms_img['watermark_path']['key'], $forms_img['watermark_path']['value'], array(
				'placeholder' => "images/watermark.png", 
				'class' => 'tips form-control', 
				'id' => $forms_img['watermark_path']['key'],
				'data-content'=> __("Relative path to the image to use as watermark"),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Watermark path"), 
				))?> 
			</div>
		</div>
		<div class="form-group">
			<?= FORM::label($forms_img['watermark_position']['key'], __("Watermark position"), array('class'=>'control-label col-sm-3', 'for'=>$forms_img['watermark_position']['key']))?>
			<div class="col-sm-4">
				<?= FORM::select($forms_img['watermark_position']['key'], array(0=>"Center",1=>"Bottom",2=>"Top"), $forms_img['watermark_position']['value'], array(
				'placeholder' => $forms_img['watermark_position']['value'], 
				'class' => 'tips form-control ', 
				'id' => $forms_img['watermark_position']['key'],
				'data-content'=> __(""),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Watermark position"), 
				))?> 
			</div>
		</div>

		<div class="form-group">
			<?= FORM::label($forms['akismet_key']['key'], "<a target='_blank' href='http://akismet.com/'>".__('Akismet Key')."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['akismet_key']['key']))?>
			<div class="col-sm-4">
				<?= FORM::input($forms['akismet_key']['key'], $forms['akismet_key']['value'], array(
				'placeholder' => "", 
				'class' => 'tips form-control', 
				'id' => $forms['akismet_key']['key'],
				'data-content'=> __("Providing akismet key will activate this feature. This feature deals with spam posts and emails."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Akismet Key"), 
				))?> 
			</div>
		</div>
		<?$pages = array(''=>__('Deactivated'))?>
		<?foreach (Model_Content::get_pages() as $key => $value) {
			$pages[$value->seotitle] = $value->title;
		}?>
		<div class="form-group">
			<?= FORM::label($forms['alert_terms']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/10/11/activate-access-terms-alert/'>".__('Accept Terms Alert')."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['alert_terms']['key']))?>
			<div class="col-sm-4">
				<?= FORM::select($forms['alert_terms']['key'], $pages, $forms['alert_terms']['value'], array( 
				'class' => 'tips form-control', 
				'id' => $forms['alert_terms']['key'], 
				'data-content'=> __("If you choose to use alert terms, you can select page you want to render. And to edit content, select link 'Content' on your admin panel sidebar. Find page named <name_you_specified> click 'Edit'. In section 'Description' add content that suits you."),
				'data-trigger'=>"hover",
				'data-placement'=>"right",
				'data-toggle'=>"popover",
				'data-original-title'=>__("Accept Terms Alert"),
				))?> 
			</div>
		</div>
		<div class="form-group">
            <?= FORM::label($forms['search_by_description']['key'], __("Include search by description"), array('class'=>'control-label col-sm-3', 'for'=>$forms['search_by_description']['key']))?>
            <div class="col-sm-4">
                <?= FORM::select($forms['search_by_description']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['search_by_description']['value'], array(
                'placeholder' => "TRUE or FALSE", 
                'class' => 'tips form-control', 
                'id' => $forms['search_by_description']['key'], 
                'data-content'=> __("Once set to TRUE, enables search to look for key words in description"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Include search by description"),
                ))?> 
            </div>
        </div>
        <div class="form-group">
            <?= FORM::label($forms['blog']['key'], __("Activates Blog posting"), array('class'=>'control-label col-sm-3', 'for'=>$forms['blog']['key']))?>
            <div class="col-sm-4">
                <?= FORM::select($forms['blog']['key'], array(FALSE=>'FALSE',TRUE=>'TRUE'), $forms['blog']['value'], array(
                'placeholder' => "TRUE or FALSE", 
                'class' => 'tips form-control', 
                'id' => $forms['blog']['key'], 
                'data-content'=> __("Once set to TRUE, enables blog posts"),
                'data-trigger'=>"hover",
                'data-placement'=>"right",
                'data-toggle'=>"popover",
                'data-original-title'=>__("Activates Blog posting"),
                ))?> 
            </div>
        </div>
        <div class="form-group">
                <?= FORM::label($forms['blog_disqus']['key'], __('Disqus for blog'), array('class'=>'control-label col-sm-3', 'for'=>$forms['blog_disqus']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::input($forms['blog_disqus']['key'], $forms['blog_disqus']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips form-control', 
                    'id' => $forms['blog_disqus']['key'], 
                    'data-content'=> __("Disqus for Blog Comments"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("You need to write your disqus ID to enable the service."),
                    ))?> 
                </div>
            </div>
		
			<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
		
	</fieldset>	
</div><!--end well-->
