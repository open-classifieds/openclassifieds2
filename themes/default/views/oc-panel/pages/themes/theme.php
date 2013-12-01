<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<div class="row">
<div class="page-header">
	<h1><?=__('Themes')?></h1>
    <p><?=__('You can change the look and feel of your website here.')?><a href="http://open-classifieds.com/2013/08/21/how-to-change-theme/" target="_blank"><?=__('Read more')?></a></p>
</div>
<!-- end install themeform -->
<div class="col-md-7 col-sm-10 col-xs-10">
<? $esc_html_Title=HTML::chars($selected['Name']);
if ( $scr = Theme::get_theme_screenshot(Theme::$theme)):?>
    <img class="media-object pull-left" src="<?=$scr?>" width="150" height="100" style="width:150px;height:100px;margin-right:20px;" alt="<?=$esc_html_Title?>" title="<?=$esc_html_Title?>" />
    <?else:?>
    <img class="media-object pull-left" src="http://www.placehold.it/150x100&text=<?=$selected['Name']?>" width="150" height="100" style="width:150px;height:100px;margin-right:20px;" alt="<?=$esc_html_Title?>" title="<?=$esc_html_Title?>" />
  <?endif?>
    <div class="media-body">
        <h4 class="media-heading"><?=$selected['Name']?></h4>
        <p>
            <span class="label label-info"><?=__('Current Theme')?></span>
            <?if (Theme::has_options()):?>
            <a class="btn btn-xs btn-primary" title="<?=HTML::chars(__('Theme Options'))?>" 
                href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>">
                <i class="glyphicon glyphicon-wrench glyphicon"></i> </a>
            <?endif?>
        </p>
        <p><?=$selected['Description']?></p>
        <?if(Core::config('appearance.theme_mobile')!=''):?>
            <p>
                <?=__('Using mobile theme')?> <code><?=Core::config('appearance.theme_mobile')?></code>
                <a class="btn btn-xs btn-warning" title="<?=HTML::chars(__('Disable'))?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>'disable'))?>">
                    <i class="glyphicon   glyphicon-remove"></i>
                </a>
                <a class="btn btn-xs btn-primary" title="<?=HTML::chars(__('Options'))?>" 
                    href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options','id'=>Core::config('appearance.theme_mobile')))?>">
                <i class="glyphicon  glyphicon-wrench glyphicon"></i></a>
            </p>
        <?endif?>
    </div>
</div>
<!-- install theme form -->
<?= FORM::open(Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
<div class="well col-md-5 col-sm-10 col-xs-10">
    <span class="label label-info"><?=__('Install theme')?></span><p><?=__('To install new theme choose zip file.')?></p>
    
    <div class="controll-group">
        <input type="file" name="theme_file" id="theme_file" />
    </div>
    <div class="controll-group">
        <?= FORM::button('submit', __('Submit'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'theme','action'=>'install_theme'))))?>
    </div>
</div>
<?= FORM::close()?>
</div>
<div class="row">
<div class="col-sm-12">
<? if (count($themes)>1):?>
<div class="page-header">
<h2><?=__('Available Themes')?></h2>
</div>

<?$i=0;
foreach ($themes as $theme=>$info):?>
    <?if(Theme::$theme!==$theme):?>
    <?if ($i%3==0):?></div></div><div class="row"><div class="col-sm-12"><?endif?>
    
    <div class="thumbnail col-md-3 col-sm-3 ">
        <?if ($scr = Theme::get_theme_screenshot($theme)):?>
            <img src="<?=$scr?>" width="300" height="200" style="width:300px;height:200px;" alt="<?=HTML::chars($info['Name'])?>" title="<?=HTML::chars($info['Name'])?>" />
        <?endif?>
        

        <div class="caption">
            <h3><?=$info['Name']?></h3>
            <p><?=$info['Description']?></p>
            <p><?=$info['License']?> v<?=$info['Version']?></p>
            <p>
                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'index','id'=>$theme))?>"><?=__('Activate')?></a>
                <?if (Core::config('appearance.allow_query_theme')=='1'):?>
                <a class="btn btn-default" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a> 
                <?endif?>   
            </p>
        </div>
    </div>
    
    <?$i++;
    endif?>
<?endforeach?>

</div>
</div><!--/row-->

<?endif?>


<?
$a_m_themes = count($mobile_themes);
if(Core::config('appearance.theme_mobile')!='')
    $a_m_themes--;

if ($a_m_themes>0):?>
<h2><?=__('Available Mobile Themes')?></h2>
<div class="row">
<ul class="thumbnails">
<?$i=0;
foreach ($mobile_themes as $theme=>$info):?>
    <?if(Core::config('appearance.theme_mobile')!==$theme):?>
    <?if ($i%3==0):?></ul></div><div class="row"><ul class="thumbnails"><?endif?>
    <li class="col-md-4">
    <div class="thumbnail">

        <?if ($scr = Theme::get_theme_screenshot($theme)):?>
            <img src="<?=$scr?>" width="300" height="200" style="width:300px;height:200px;" alt="<?=HTML::chars($info['Name'])?>" title="<?=HTML::chars($info['Name'])?>" />
        <?endif?>

        <div class="caption">
            <h3><?=$info['Name']?></h3>
            <p><?=$info['Description']?></p>
            <p><?=$info['License']?> v<?=$info['Version']?></p>
            <p>
                <a class="btn btn-primary" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'mobile','id'=>$theme))?>"><?=__('Activate')?></a>
                <a class="btn btn-default" target="_blank" href="<?=Route::url('default')?>?theme=<?=$theme?>"><?=__('Preview')?></a>    
            </p>
        </div>
    </div>
    </li>
    <?$i++;
    endif?>
<?endforeach?>
</ul>
</div><!--/row-->    
<?endif?>

<? if (count($market)>0):?>
<h2><?=__('Themes Market')?></h2>
<p><?=__('Here you can find a selection of our premium themes.')?></p>
<p class="text-success"><?=__('All themes include support, updates and 1 site license.')?></p> <?=__('Also white labeled and free of ads')?>!

<?=View::factory('oc-panel/pages/market/listing',array('market'=>$market))?>    
<?endif?>