<form action="" method="post">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
        <h3 class="modal-title"><?=__('Add New Review')?></h3>
    </div>
    <div class="modal-body">
        <?=Form::errors()?>
        <div class="form-group">
            <div id="review_raty" data-baseurl="<?=Route::url('default')?>"></div>
        </div>

        <div class="form-group">
            <?=FORM::label('description', __('Review'), array('for'=>'description'))?>
            <div class="controls">
                <?=FORM::textarea('description', core::post('description',''), array('placeholder' => __('Review'), 'class' => 'form-control', 'name'=>'description', 'id'=>'description', 'required'))?>
            </div>
        </div>

        <?if (core::config('advertisement.captcha') != FALSE):?>
            <div class="form-group">
                <?if (Core::config('general.recaptcha_active')):?>
                    <?=View::factory('recaptcha', ['id' => 'recaptcha1'])?>
                <?else:?>
                    <div class="row">
                        <div class="col-md-4">
                            <?=FORM::label('captcha', __('Captcha'), array('for'=>'captcha'))?>
                            <span id="helpBlock" class="help-block"><?=captcha::image_tag('review')?></span>
                            <?=FORM::input('captcha', "", array('class'=>'form-control', 'id' => 'captcha', 'required'))?>
                        </div>
                    </div>
                <?endif?>
            </div>
            <div class="clearfix"></div>
        <?endif?>
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="<?=__('Post Review')?>" />
    </div>
</form>
