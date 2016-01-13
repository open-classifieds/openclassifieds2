<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Import tool for ads')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-import-ads/"><?=__('Read more')?></a></span>
    </div>
</div>
    
<div class="row">    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?=__('Upload CSV file')?></h4>
            </div>
            <div class="panel-body">
                <p>
                    <?=__('Please use the correct CSV format')?> <a href="https://docs.google.com/uc?id=0B60e9iwQucDwRzlOT2NCem5maFU&export=download"><?=__('download example')?>.</a>
                    <br /><br />
                    <span class="label label-info"><?=__('Hosting limit')?></span> 
                    upload_max_filesize: <?=ini_get('upload_max_filesize')?>, max_execution_time: <?=ini_get('max_execution_time')?><?=__('seconds')?> <?=__('limited to 10.000 at a time')?>, <?=__('1 MB file')?>.
                </p>
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'import','action'=>'csv')), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
                    <div class="form-group">
                        <label for=""> <?=__('Import Ads')?></label>
                        <input type="file" name="csv_file_ads" id="csv_file_ads" class="form-control"/>
                    </div>
                        <?= FORM::button('submit', __('Upload'), array('type'=>'submit','id'=>'csv_upload', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'import','action'=>'csv'))))?>
                <?= FORM::close()?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?=__('Process Queue')?></h4>
            </div>
            <div class="panel-heading">
                <p>
                <?if($ads_import>0):?>
                    <div id="count_import"><?=sprintf(__('You got %d ads to get processed'),$ads_import)?></div>
                    <p>
                    <a class="btn btn-success" id="import_process" href="<?=Route::url('oc-panel',array('controller'=>'import','action'=>'process'))?>">
                        <?=__('Process')?>
                    </a>
                    <a class="btn btn-danger btn-xs" id="delete_queue" href="<?=Route::url('oc-panel',array('controller'=>'import','action'=>'deletequeue'))?>">
                        <?=__('Delete')?>
                    </a>
                    <p>
                <?else:?>
                    <?=__('Not any ad to be processed')?>
                <?endif?>
                </p>
            </div>
            
        </div>
    </div>

</div>
<div class="row">

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?=__('Get Latitude and Longitude')?></h4>
            </div>
            <div class="panel-body">
                <p><?=__('Gets Ads Latitude and Longitude from Google Maps API using advertisements address')?></p>
                <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'get_ads_latlgn'))?>" class="btn btn-primary"><?=__('Process')?><a/>
            </div>
        </div>
    </div>
    
</div>