<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title">
    <?=__('Custom CSS')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-use-custom-css/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<p>
    <?=__('Please insert here your custom CSS.')?>
    <? if (Theme::get_custom_css()): ?>
        <?=__('Current CSS file')?>  <?=HTML::anchor(Theme::get_custom_css())?>
    <? endif ?>
</p>

<div class="row">
    <div class="col-md-12">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <?=FORM::label('css_active', __('Custom CSS'), array('class'=>'control-label', 'for'=>'css_active'))?>
                        <div class="radio radio-primary">
                            <?=Form::radio('css_active', 1, (bool) $css_active, array('id' => 'css_active'.'1'))?>
                            <?=Form::label('css_active'.'1', __('Enabled'))?>
                            <?=Form::radio('css_active', 0, ! (bool) $css_active, array('id' => 'css_active'.'0'))?>
                            <?=Form::label('css_active'.'0', __('Disabled'))?>
                        </div>
                     </div>

                    <div class="form-group">
                        <label class="control-label"><?=__('CSS')?></label>
                        <textarea rows="30" class="form-control" name="css"><?=$css_content?></textarea>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
                </div>
            </div>
        </form>
    </div>
</div>
