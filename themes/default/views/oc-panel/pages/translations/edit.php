<h1 class="page-header page-title"><?=__('Translations')?> "<?=$edit_language?>" "<?=$translation_file?>"</h1>
<hr>

<p>
<?=__('Here you can modify any text you find in your web.')?> <a href="https://docs.yclas.com/how-to-change-texts/" target="_blank"><?=__('Read more')?></a><br>
<?=sprintf("Total of %u strings. %u strings already translated", $total_items, $total_items-$cont_untranslated)?>. <span class="error"><?=sprintf("%u strings yet to translate",$cont_untranslated)?>.</span>
</p>

<form class="form-inline" method="get" action="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'edit','id'=>$edit_language))?>">
    <div class="form-group">
        <input type="text" class="form-control input-sm search-query" name="search" placeholder="<?=__('search')?>" value="<?=HTML::chars(core::request('search'))?>">
    </div>
    <input type="hidden" name="translation_file" value="<?=$translation_file?>" >
    <button type="submit" class="btn btn-primary"><?=__('Search')?></button>
</form>

<form class="form-inline" method="post" action="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'replace','id'=>$edit_language))?>">
        <div class="form-group">
            <input type="text" class="form-control input-sm search-query" name="search" placeholder="<?=__('search')?>" value="<?=HTML::chars(core::request('search'))?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control input-sm search-query" name="replace" placeholder="<?=__('replace')?>" value="<?=HTML::chars(core::request('replace'))?>">
        </div>
        <select name="where" id="where" class="form-control disable-chosen disable-select2" >
            <option value="original"><?=__('Replace Original')?></option>
            <option value="translation"><?=__('Replace Translation')?></option>
        </select>
        <input type="hidden" name="translation_file" value="<?=$translation_file?>" >
    <button type="submit" class="btn btn-warning"><?=__('Replace')?></button>
</form>

<div class="panel panel-default">
    <div class="panel-body">
        <form enctype="multipart/form-data" class="form form-horizontal" accept-charset="utf-8" method="post" action="<?=str_replace('rel=ajax', '', URL::current())?>">
            <input type="hidden" name="translation_file" value="<?=$translation_file?>" >
            <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th><?=__('Original Translation')?></th>
                <th><button class="btn" id="button-copy-all" 
                        data-text="<?=__('Copy all?, Be aware this will replace all your texts.')?>" >
                        <i class="glyphicon glyphicon-arrow-right"></i></button>
                    <?if (strlen(Core::config('general.translate'))>0):?>
                        <button id="button-translate-all" class="btn" data-apikey="<?=Core::config('general.translate')?>"
                            data-text="<?=__('Translate all?, Be aware this will replace all your texts.')?>"
                            data-langsource="en" data-langtarget="<?=substr($edit_language,0,2)?>" ><i class="glyphicon glyphicon-globe"></i>
                        </button>
                    <?endif?>
                </th>
                <th><?=__('Translation')?> <?=$edit_language?></th>
            </tr>

            <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i> <?=__('Save')?></button>

            <a  class="btn btn-danger pull-right" href="<?=Request::current()->url()?>?translated=1&translation_file=<?=$translation_file?>" title="<?=__('Hide translated texts')?>" >
                <i class="glyphicon glyphicon-eye-close"></i> 
            </a>
            
            <a  class="btn btn-primary pull-right" href="<?=Request::current()->url()?>?translation_file=<?=$translation_file?>" title="<?=__('Show translated texts')?>">
                <i class="glyphicon glyphicon-eye-open"></i> 
            </a>
        
            <?foreach($translation_array as $key => $values):?>
                <?list($id,$original,$translated) = array_values($values);?>
                <tr id="tr_<?=$id?>" class="<?=(strlen($translated)>0)? 'success': 'error'?>">
                    <td width="5%"><?=$id?></td>
                    <td>
                        <textarea id="orig_<?=$id?>" disabled style="width: 100%"><?=$original?></textarea>
                    </td>
                    <td width="5%">
                        <button class="btn button-copy" data-orig="orig_<?=$id?>" data-dest="dest_<?=$id?>" data-tr="tr_<?=$id?>" ><i class="glyphicon glyphicon-arrow-right"></i></button>
                        <br>
                        <?if (strlen(Core::config('general.translate'))>0):?>
                            <button class="btn button-translate" data-orig="orig_<?=$id?>" data-dest="dest_<?=$id?>" data-tr="tr_<?=$id?>" ><i class="glyphicon glyphicon-globe"></i></button>
                        <?else:?>
                            <a target="_blank" class="btn" 
                            href="http://translate.google.com/#en/<?=substr($edit_language,0,2)?>/<?=urlencode($original)?>">
                            <i class="glyphicon glyphicon-globe"></i></a>
                        <?endif?>
                    </td>
                    <td>  
                        <textarea id="dest_<?=$id?>" style="width: 100%" name="translations[<?=$id?>]"><?=$translated?></textarea>
                    </td>
                </tr>
            <?endforeach;?>
        
            </table>
            <button type="submit" class="btn btn-primary pull-right" name="translation[submit]"><i class="glyphicon glyphicon-hdd"></i> <?=__('Save')?></button>        
        </form>
    </div>
</div>

<div class="text-center">
    <?=$pagination?>
</div>