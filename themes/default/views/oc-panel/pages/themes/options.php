<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title">
    <?=__('Theme Options')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?>
</h1>


<hr>

<p><?=__('Here are listed specific theme configuration values. Replace input fields with new desired values for the theme.')?></p>

<div class="row">
    <div class="col-md-12">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post" enctype="multipart/form-data">
            <?  //get field categories
                $field_categories = array();
                foreach ($options as $field => $attributes)
                {
                    if (isset($attributes['category']) AND ! in_array($attributes['category'], $field_categories))
                        $field_categories[] = $attributes['category'];
                }
            ?>
            <?if (! empty($field_categories)):?>
                <div>
                    <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left" id="tab-options">
                        <?$i = 1; foreach ($field_categories as $key => $field_category):?>
                            <li class="<?=($i == 1) ? 'active' : NULL?>">
                                <a data-toggle="tab" href="#tabOptions<?=$key?>" aria-expanded="<?=($i == 1) ? 'true' : 'false'?>">
                                    <?=$field_category?>
                                </a>
                            </li>
                        <?$i++; endforeach?>
                    </ul>
                    <div class="tab-content">
                        <?$i = 1; foreach ($field_categories as $key => $field_category):?>
                            <div id="tabOptions<?=$key?>" class="tab-pane <?=($i == 1) ? 'active in' : NULL?> fade">
                                <h4><?=__(':name Options', [':name' => $field_category])?></h4>
                                <hr>
                                
                                <?foreach ($options as $field => $attributes):?>
                                    <?if (isset($attributes['category']) AND $attributes['category'] == $field_category):?>
                                        <div class="form-group">
                                            <?=FORM::form_tag($field, $attributes, (isset($data[$field])) ? $data[$field] : NULL)?>
                                            <?if ($attributes['display'] == 'select'):?>
                                                <div class="clearfix" style="height:28px;"></div>
                                            <?endif?>
                                        </div>
                                    <?endif?>
                                <?endforeach?>

                                <hr>
                                <p>
                                    <?=FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
                                </p>
                            </div>
                        <?$i++; endforeach?>
                    </div>
                </div>
            <?else:?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?foreach ($options as $field => $attributes):?>
                            <div class="form-group">
                                <?=FORM::form_tag($field, $attributes, (isset($data[$field]))?$data[$field]:NULL)?>
                            </div>
                        <?endforeach?>
                        
                        <hr>
                        <?=FORM::button('submit', __('Update'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
                    </div>
                </div>
            <?endif?>

        </form>
    </div>
</div>
