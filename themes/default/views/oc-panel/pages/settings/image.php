<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title">
    <?=__('Media settings')?>
</h1>
<hr>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <?=FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'image')), array('class'=>'config ajax-load', 'enctype'=>'multipart/form-data'))?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4><?=__('Image configuration')?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-configure-image-settings/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>

                    <?foreach ($config as $c):?>
                        <? $forms[$c->config_key] = array('key'=>'image['.$c->config_key.'][]', 'id'=>$c->config_key, 'value'=>$c->config_value)?>
                    <?endforeach?>

                    <div class="form-group">
                        <?=FORM::label($forms['allowed_formats']['id'], __('Allowed image formats'), array('class'=>'control-label', 'for'=>$forms['allowed_formats']['id']))?>
                        <?=FORM::select($forms['allowed_formats']['key'], array('jpeg'=>'jpeg','jpg'=>'jpg','png'=>'png','webp'=>'webp','gif'=>'gif'), explode(',', $forms['allowed_formats']['value']), array(
                            'placeholder' => $forms['allowed_formats']['value'],
                            'multiple' => 'true',
                            'class' => 'tips form-control',
                        ))?>
                        <span class="help-block">
                            <?=__("Set this up to restrict image formats that are being uploaded to your server.")?>
                        </span>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['max_image_size']['id'], __('Max image size'), array('class'=>'control-label', 'for'=>$forms['max_image_size']['id']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['max_image_size']['key'], $forms['max_image_size']['value'], array(
                                'placeholder' => "5", 
                                'class' => 'tips form-control', 
                                'id' => $forms['max_image_size']['id'],
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                                'type' => 'number',
                            ))?>
                            <span class="input-group-addon">MB</span>
                        </div>
                        <span class="help-block">
                            <?=__("Control the size of images being uploaded. Enter an integer value to set maximum image size in mega bites(Mb).")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['height']['id'], __('Image height'), array('class'=>'control-label', 'for'=>$forms['height']['id']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['height']['key'], $forms['height']['value'], array(
                                'placeholder' => "700", 
                                'class' => 'tips form-control', 
                                'id' => $forms['height']['id'], 
                                'data-rule-digits' => 'true',
                                'type' => 'number',
                                ))?>
                            <span class="input-group-addon">px</span>
                        </div>
                        <span class="help-block">
                            <?=__("Each image is resized when uploaded. This is the height of big image. Note: you can leave this field blank to set AUTO height resize.")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['width']['id'], __('Image width'), array('class'=>'control-label', 'for'=>$forms['width']['id']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['width']['key'], $forms['width']['value'], array(
                                'placeholder' => "1024", 
                                'class' => 'tips form-control', 
                                'id' => $forms['width']['id'],
                                'data-rule-digits' => 'true',
                                'type' => 'number',
                            ))?>
                            <span class="input-group-addon">px</span>
                        </div>
                        <span class="help-block">
                            <?=__("Each image is resized when uploaded. This is the width of big image.")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['height_thumb']['id'], __('Thumb height'), array('class'=>'control-label', 'for'=>$forms['height_thumb']['id']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['height_thumb']['key'], $forms['height_thumb']['value'], array(
                                'placeholder' => "200", 
                                'class' => 'tips form-control', 
                                'id' => $forms['height_thumb']['id'],
                                'data-rule-digits' => 'true',
                                'type' => 'number',
                            ))?>
                            <span class="input-group-addon">px</span>
                        </div>
                        <span class="help-block">
                            <?=__("Thumb is a small image resized to fit certain elements. This is the height for this image.")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['width_thumb']['id'], __('Thumb width'), array('class'=>'control-label', 'for'=>$forms['width_thumb']['id']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['width_thumb']['key'], $forms['width_thumb']['value'], array(
                                'placeholder' => "200", 
                                'class' => 'tips form-control', 
                                'id' => $forms['width_thumb']['id'],
                                'data-rule-digits' => 'true',
                                'type' => 'number',
                                ))?>
                            <span class="input-group-addon">px</span>
                        </div>
                        <span class="help-block">
                            <?=__("Thumb is a small image resized to fit certain elements. This is width of this image.")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['quality']['id'], __('Image quality'), array('class'=>'control-label', 'for'=>$forms['quality']['id']))?>
                        <div class="input-group">
                            <?=FORM::input($forms['quality']['key'], $forms['quality']['value'], array(
                                'placeholder' => "95", 
                                'class' => 'tips form-control', 
                                'id' => $forms['quality']['id'],
                                'type' => 'number',
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                            ))?>
                            <span class="input-group-addon">%</span>
                        </div>
                        <span class="help-block">
                            <?=__("Choose the quality of the stored images (1-100% of the original).")?>
                        </span>
                    </div>
        
                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['watermark']['id'], __('Watermark'), array('class'=>'control-label', 'for'=>$forms['watermark']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-add-a-watermark/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['watermark']['key'], 1, (bool) $forms['watermark']['value'], array('id' => $forms['watermark']['key'].'1'))?>
                            <?=Form::label($forms['watermark']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['watermark']['key'], 0, ! (bool) $forms['watermark']['value'], array('id' => $forms['watermark']['key'].'0'))?>
                            <?=Form::label($forms['watermark']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>
        
                    <div class="form-group">
                        <?=FORM::label($forms['watermark_path']['id'], __('Watermark path'), array('class'=>'control-label', 'for'=>$forms['watermark_path']['id']))?>
                        <?=FORM::input($forms['watermark_path']['key'], $forms['watermark_path']['value'], array(
                            'placeholder' => "images/watermark.png", 
                            'class' => 'tips form-control', 
                            'id' => $forms['watermark_path']['id'],
                        ))?> 
                        <span class="help-block">
                            <?=__("Relative path to the image to use as watermark")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?=FORM::label($forms['watermark_position']['id'], __('Watermark position'), array('class'=>'control-label', 'for'=>$forms['watermark_position']['id']))?>
                        <?=FORM::select($forms['watermark_position']['key'], array(0=>"Center",1=>"Bottom",2=>"Top"), $forms['watermark_position']['value'], array(
                            'placeholder' => $forms['watermark_position']['value'], 
                            'class' => 'tips form-control', 
                            'id' => $forms['watermark_position']['id'],
                        ))?> 
                    </div>
        
                    <hr>
        
                    <div class="form-group">
                        <?=FORM::label($forms['disallow_nudes']['id'], __('Disallow nude pictures'), array('class'=>'control-label', 'for'=>$forms['disallow_nudes']['id']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['disallow_nudes']['key'], 1, (bool) $forms['disallow_nudes']['value'], array('id' => $forms['disallow_nudes']['key'].'1'))?>
                            <?=Form::label($forms['disallow_nudes']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['disallow_nudes']['key'], 0, ! (bool) $forms['disallow_nudes']['value'], array('id' => $forms['disallow_nudes']['key'].'0'))?>
                            <?=Form::label($forms['disallow_nudes']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <?= FORM::label($forms['aws_s3_active']['id'], __('Amazon S3 active'), array('class'=>'control-label', 'for'=>$forms['aws_s3_active']['id']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['aws_s3_active']['key'], 1, (bool) $forms['aws_s3_active']['value'], array('id' => $forms['aws_s3_active']['key'].'1'))?>
                            <?=Form::label($forms['aws_s3_active']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['aws_s3_active']['key'], 0, ! (bool) $forms['aws_s3_active']['value'], array('id' => $forms['aws_s3_active']['key'].'0'))?>
                            <?=Form::label($forms['aws_s3_active']['key'].'0', __('Disabled'))?>
                        </div>
                        <span class="help-block">
                            <?=__("Activating Amazon S3 will move all the images to the S3 bucket.")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['aws_access_key']['id'], __('AWS Access Key'), array('class'=>'control-label', 'for'=>$forms['aws_access_key']['id']))?>
                        <?=FORM::input($forms['aws_access_key']['key'], $forms['aws_access_key']['value'], array(
                            'placeholder' => "AWS Access Key", 
                            'class' => 'tips form-control', 
                            'id' => $forms['aws_access_key']['id'],
                        ))?> 
                        <span class="help-block">
                            <?=__("AWS Access Key")?>
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['aws_secret_key']['id'], __('AWS Secret Key'), array('class'=>'control-label', 'for'=>$forms['aws_secret_key']['id']))?>
                        <?=FORM::input($forms['aws_secret_key']['key'], $forms['aws_secret_key']['value'], array(
                            'placeholder' => "AWS Secret Key", 
                            'class' => 'tips form-control', 
                            'id' => $forms['aws_secret_key']['id'],
                        ))?>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['aws_s3_bucket']['id'], __('Amazon S3 Bucket Name'), array('class'=>'control-label', 'for'=>$forms['aws_s3_bucket']['id']))?>
                        <?=FORM::input($forms['aws_s3_bucket']['key'], $forms['aws_s3_bucket']['value'], array(
                            'placeholder' => "Amazon S3 Bucket Name", 
                            'class' => 'tips form-control', 
                            'id' => $forms['aws_s3_bucket']['id'],
                        ))?>
                    </div>
                    
                    <?  if (($aws_s3_domain = strpos($forms['aws_s3_domain']['value'], 's3.amazonaws.com')) !== FALSE) 
                            $forms['aws_s3_domain']['value'] = ($aws_s3_domain > 0) ? 'bn-s3' : 's3-bn';
                    ?>
                    <div class="form-group">
                        <?= FORM::label($forms['aws_s3_domain']['id'], __('S3 Domain Name'), array('class'=>'control-label', 'for'=>$forms['aws_s3_domain']['id']))?>
                        <?=FORM::select($forms['aws_s3_domain']['key'], array("bn"=>"bucket_name","bn-s3"=>"bucket_name.s3.amazonaws.com","s3-bn"=>"s3.amazonaws.com/bucket_name"), $forms['aws_s3_domain']['value'], array(
                            'placeholder' => $forms['aws_s3_domain']['value'], 
                            'class' => 'tips form-control', 
                            'id' => $forms['aws_s3_domain']['id'],
                        ))?>
                    </div>

                    <hr>

                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'image'))))?>
                </div>
            </div>
        <?=FORM::close()?>
	</div><!--end col-md-8-->
</div>