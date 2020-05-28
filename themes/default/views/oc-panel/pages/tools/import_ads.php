<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="list-inline pull-right">
    <li>
        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400" href="<?=Route::url('oc-panel', array('controller' => 'tools', 'action' => 'export'))?>">
            <?=__('Export')?>
        </a>
    </li>
</ul>

<h1 class="page-header page-title">
    <?=__('Import tool for ads')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-import-ads/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<div class="flex flex-wrap">
    <div class="md:w-1/2 pr-4 pl-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?=__('Upload CSV file')?></div>
            </div>
            <div class="panel-body">
                <p>
                    <?=__('Please use the correct CSV format')?> <a href="https://cdn.rawgit.com/yclas/yclas/master/install/samples/import/ads.csv"><?=__('download example')?>.</a>
                    <br /><br />
                    <span class="label label-info"><?=__('Hosting limit')?></span> 
                    <br>upload_max_filesize: <?=ini_get('upload_max_filesize')?>,<br>max_execution_time: <?=ini_get('max_execution_time')?> <?=__('seconds')?> <?=__('limited to 10.000 at a time')?>, <?=__('1 MB file')?>.
                </p>
                <?= FORM::open(Route::url('oc-panel',array('controller'=>'import','action'=>'csv')), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
                    <div class="mb-4">
                        <label for=""> <?=__('Import Ads')?></label>
                        <input type="file" name="csv_file_ads" id="csv_file_ads" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded"/>
                    </div>
                        <?= FORM::button('submit', __('Upload'), array('type'=>'submit','id'=>'csv_upload', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'import','action'=>'csv'))))?>
                <?= FORM::close()?>
            </div>
        </div>
    </div>

    <div class="md:w-1/2 pr-4 pl-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><?=__('Process Queue')?></div>
            </div>
            <div class="panel-body">
                <p>
                <?if($ads_import>0):?>
                    <div id="count_import"><?=sprintf(__('You got %d ads to get processed'),$ads_import)?></div>
                    <p>
                    <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-green-100 bg-green-500 hover:bg-green-400" id="import_process" href="<?=Route::url('oc-panel',array('controller'=>'import','action'=>'process'))?>">
                        <?=__('Process')?>
                    </a>
                    <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400 btn-xs" id="delete_queue" href="<?=Route::url('oc-panel',array('controller'=>'import','action'=>'deletequeue'))?>">
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

<?if (core::config('advertisement.gm_api_key')):?>
    <div class="flex flex-wrap">
        <div class="md:w-1/2 pr-4 pl-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?=__('Get Latitude and Longitude')?></div>
                </div>
                <div class="panel-body">
                    <p><?=__('Gets Ads Latitude and Longitude from Google Maps API using advertisements address')?></p>
                    <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'get_ads_latlgn'))?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Process')?><a/>
                </div>
            </div>
        </div>
    </div>
<?endif?>
