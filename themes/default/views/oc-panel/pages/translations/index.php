<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="btn-group pull-right">
    <div class="btn-group dropdown">
        <?if (class_exists('IntlCalendar')) :?>
        <button class="btn btn-primary" data-toggle="dropdown" type="button"><?=__('New translation')?></button>
        <div class="dropdown-menu dropdown-menu-right">
            <form class="col-sm-12" role="form" method="post" action="<?=Request::current()->url()?>">
                <div class="form-group">
                    <label class="sr-only" for="locale"><?=__('New translation')?></label>
                    <select class="form-control" id="locale" name="locale">
                        <?foreach (IntlCalendar::getAvailableLocales() as $locale):?>
                            <option value="<?=$locale?>"><?=$locale?></option>
                        <?endforeach?>
                    </select>
                    <p class="help-block"><?=__('If your locale is not listed, be sure your hosting has your locale installed.')?></p>
                </div>
                <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
            </form>
        </div>
        <?endif?>
    </div>
    <a class="btn btn-info" href="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'index'))?>?parse=1" >
        <?=__('Scan')?>
    </a>
</div>

<h1 class="page-header page-title">
    <?=__('Translations')?> 
    <a target="_blank" href="http://docs.yclas.com/how-to-change-language/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
<hr>

<p>
    <?=__('Translations files available in the system.')?>
</p>

<div class="panel panel-default">
    <table class="table" id="translations-table">
        <thead>
            <tr>
                <th><?=__('Language')?></th>
                <th style="width: 10%"></th>
            </tr>
        </thead>
        <tbody>
            <?foreach ($languages as $language):?>
                <tr class="<?=($language==$current_language)?'success':''?>">
                    <td><?=$language?></td>
                    <td class="nowrap">    
                        <ul class="list-inline">
                            <li>
                                <a class="btn btn-warning ajax-load" 
                                    href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>?translation_file=apps" 
                                    rel"tooltip" title="<?=__('Edit')?> Apps">
                                    <i class="glyphicon glyphicon-pencil"></i> Apps 
                                </a>
                            </li>
                            <li>
                                <a class="btn btn-success ajax-load" 
                                    href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>" 
                                    rel"tooltip" title="<?=__('Edit')?> Web">
                                    <i class="glyphicon glyphicon-pencil"></i> Web
                                </a>
                            </li>
                            <li>
                                <?if ($language!=$current_language):?>
                                    <a class="btn btn-default" 
                                        href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'index','id'=>$language))?>" 
                                        rel"tooltip" title="<?=__('Activate')?>">
                                        <?=__('Activate')?>
                                    </a>
                                <?else:?>
                                    <span class="label label-info"><?=__('Active')?></span>
                                <?endif?>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?endforeach?>
        </tbody>
    </table>
</div>