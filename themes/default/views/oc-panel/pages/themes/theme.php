<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<ul class="list-inline pull-right">
    <li>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#install-theme">
            <i class="fa fa-download"></i> <?=__('Install theme')?>
        </button>
    </li>
</ul>

<h1 class="page-header page-title" id="page-themes">
    <?=__('Themes')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-change-theme/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<p><?=__('You can change the look and feel of your website here.')?></p>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?=__('Current Theme')?></div>
            </div>
            <div class="panel-body">
                <div class="media">
                    <?if ($scr = Theme::get_theme_screenshot(Theme::$theme))?>
                    <div class="media-left">
                        <img class="media-object" style="max-width:150px;" src="<?=$scr?>">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?=$selected['Name']?>
                            <?if (Theme::has_options()):?>
                                <a class="btn btn-xs btn-primary ajax-load" title="<?=__('Theme Options')?>" 
                                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                                    <i class="fa fa-wrench"></i> <?=__('Theme Options')?>
                                </a>
                            <?endif?>
                        </h4>
                        <p><?=$selected['Description']?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h2 class="page-header page-title">
    <?=__('Available Themes')?>
</h2>

<hr>

<? if (core::count($themes)>1):?>
    <div class="row">
        <?$i=0;
        foreach ($themes as $theme=>$info):?>
            <?if(Theme::$theme!==$theme):?>
            <?if ($i%3==0):?><div class="clearfix"></div><?endif?>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?if ($scr = Theme::get_theme_screenshot($theme)):?>
                            <img class="img-rounded img-responsive" src="<?=$scr?>">
                        <?endif?>
                        
                        <div class="caption">
                            <h3><?=$info['Name']?></h3>
                            <p><?=$info['Description']?></p>
                            <p><?=$info['License']?> v<?=$info['Version']?></p>
                            <p>
                                <a class="btn btn-primary btn-block" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                                <?if (Core::config('appearance.allow_query_theme')=='1'):?>
                                <a class="btn btn-default btn-block" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a> 
                                <?endif?>   
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?$i++;
            endif?>
        <?endforeach?>
    </div>
<?endif?>


<?if (core::count($market)>0):?>
<h2><?=__('Themes Market')?></h2>
<p><?=__('Here you can find a selection of our premium themes.')?></p>
<p class="text-success"><?=__('All themes include support, updates and 1 site license.')?></p> <?=__('Also white labeled and free of ads')?>!

<?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    
<?endif?>

<div class="modal fade" id="install-theme" tabindex="-1" role="dialog" aria-labelledby="installTheme" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                <h4 id="installTheme" class="modal-title"><?=__('Install theme')?></h4>
            </div>
            <div class="modal-body">
                <?=FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'download')))?>
                    <div class="form-group">
                        <?=FORM::label('license', __('Install theme from license.'), array('class'=>'control-label', 'for'=>'license' ))?> 
                        <input type="text" name="license" id="license" placeholder="<?=__('license')?>" class="form-control"/>
                    </div>
                    <button 
                        type="button" 
                        class="btn btn-primary submit" 
                        title="<?=__('Are you sure?')?>" 
                        data-text="<?=sprintf(__('License will be activated in %s domain.'), parse_url(URL::base(), PHP_URL_HOST))?>"
                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                        data-btnCancelLabel="<?=__('No way!')?>">
                        <?=__('Download')?>
                    </button>
                <?=FORM::close()?>
                
                <hr>

                <?=FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme')), array('enctype'=>'multipart/form-data'))?>
                    <div class="form-group">
                        <?=FORM::label('theme_file', __('To install new theme choose zip file.'), array('class'=>'control-label', 'for'=>'theme_file' ))?> 
                        <input type="file" name="theme_file" id="theme_file" class="form-control" />
                    </div>
                    <?=FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme'))))?>
                <?=FORM::close()?>
            </div>
        </div>
    </div>
</div>
