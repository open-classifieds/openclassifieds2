<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-info" href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'geonames'])?><?=Core::get('id_location') ? '?id_location='.HTML::chars(Core::get('id_location')) : NULL?>" title="<?=__('Import Locations')?>">
            <?=__('Import Geonames Locations')?>
        </a>
    </li>
    <li>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#quick-creator">
            <?=__('Quick creator')?>
        </button>
    </li>
    <li>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#import-tool">
            <i class="fa fa-upload"></i>&nbsp;  <?=__('Import')?>
        </button>
    </li>
    <li>
        <a class="btn btn-primary" href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'create'])?><?=Core::get('id_location') ? '?id_location_parent='.HTML::chars(Core::get('id_location')) : NULL?>" title="<?=__('New Location')?>">
            <i class="fa fa-plus-circle"></i>&nbsp;  <?=__('New Location')?>
        </a>
    </li>
</ul>

<h1 class="page-header page-title">
    <?=__('Locations')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-add-locations/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<p>
    <?=__('Change the order of your locations. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).')?>
</p>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    <?=($location->id_location == 1) ? __('Home location') : $location->name?>
                </div>
            </div>
            <?=FORM::open(Route::url('oc-panel',['controller'=>'location','action'=>'delete']), ['enctype'=>'multipart/form-data'])?>
                <div class="panel-body">
                    <ol class='plholder' id="ol_<?=$location->id_location?>" data-id="<?=$location->id_location?>">
                        <?foreach ($locs as $loc) :?>
                            <li data-id="<?=$loc->id_location?>" id="li_<?=$loc->id_location?>">
                                <div class="drag-item">
                                    <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                    <div class="drag-name">
                                        <?=$loc->name?>
                                    </div>
                                    <a class="drag-action ajax-load" title="<?=__('Browse childs')?>"
                                        href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'index'])?>?id_location=<?=$loc->id_location?>">
                                        <?=__('Browse')?>
                                    </a>
                                    <a class="drag-action" title="<?=__('Edit')?>"
                                        href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'update','id'=>$loc->id_location])?>">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a
                                        href="<?=Route::url('oc-panel', ['controller'=> 'location', 'action'=>'delete','id'=>$loc->id_location])?>"
                                        class="drag-action index-delete"
                                        title="<?=__('Are you sure you want to delete? We will move the siblings locations and ads to the parent of this location.')?>"
                                        data-id="li_<?=$loc->id_location?>"
                                        data-placement="left"
                                        data-href="<?=Route::url('oc-panel', ['controller'=> 'location', 'action'=>'delete','id'=>$loc->id_location])?>"
                                        data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                        data-btnCancelLabel="<?=__('No way!')?>">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                    <span class="drag-action">
                                        <div class="checkbox check-success">
                                            <input name="locations[]" value="<?=$loc->id_location?>" type="checkbox" id="checkbox_<?=$loc->id_location?>">
                                            <label for="checkbox_<?=$loc->id_location?>"></label>
                                        </div>
                                    </span>
                                </div>

                            </li><!--li_<?=$loc->id_location?>-->
                        <?endforeach?>
                    </ol><!--ol_1-->

                    <span id='ajax_result' data-url='<?=Route::url('oc-panel',['controller'=>'location','action'=>'saveorder'])?>'></span>
                </div>
                <?if(core::count($locs) > 1) :?>
                    <div class="panel-footer">
                        <div class="text-right">
                            <button type="button" data-toggle="modal" data-target="#delete-all" class="btn btn-danger">
                                <?=__('Delete all locations')?>
                            </button>

                            <button name="delete" type="submit" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i>&nbsp; <?=__('Delete selected locations')?>
                            </button>
                        </div>
                    </div>
                <?endif?>
            <?=FORM::close()?>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-all" tabindex="-1" role="dialog" aria-labelledby="deleteLocations" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= FORM::open(Route::url('oc-panel',['controller'=>'location','action'=>'delete_all'], ['class'=>'form-horizontal', 'role'=>'form']))?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 id="deleteLocations" class="modal-title"><?=__('Are you sure you want to delete all the locations?')?></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <p><?=__('We will move all the ads to home location.')?> <?=__('This is permanent! No backups, no restores, no magic undo button. We warned you, ok?')?></p>
                    </div>
                </div>
                <div class="modal-body text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel')?></button>
                    <button type="submit" class="btn btn-danger" name="confirmation" value="1"><?=__('Delete')?></button>
                </div>
            <input type="hidden" name="id_location" value="<?=HTML::chars(Core::get('id_location'))?>"></div>
            <?= FORM::close()?>
        </div>
    </div>
</div>

<div class="modal fade" id="quick-creator" tabindex="-1" role="dialog" aria-labelledby="quickLocations" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?=FORM::open(Route::url('oc-panel',['controller'=>'location','action'=>'multy_locations'.'?id_location='.HTML::chars(Core::get('id_location', 1))]), ['role'=>'form','enctype'=>'multipart/form-data'])?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 id="quickLocations" class="modal-title"><?=__('Quick location creator.')?></h4>
                </div>
                <div class="modal-body">
                    <p><?=__('Add names for multiple locations, for each one push enter.')?></p>
                    <div class="form-group">
                        <?=FORM::label('multy_locations', __('Name'), ['class'=>'control-label', 'for'=>'multy_locations'])?>
                        <div>
                            <?=FORM::input('multy_locations', '', ['placeholder' => __('Hit enter to confirm'), 'class' => 'form-control', 'id' => 'multy_locations', 'type' => 'text','data-role'=>'tagsinput'])?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel')?></button>
                    <?=FORM::button('submit', __('Send'), ['type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',['controller'=>'location','action'=>'multy_locations'.'?id_location='.HTML::chars(Core::get('id_location', 1))])])?>
                </div>
            <?=FORM::close()?>
        </div>
    </div>
</div>

<div class="modal fade" id="import-tool" tabindex="-1" role="dialog" aria-labelledby="importLocations" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                <h4 id="importLocations" class="modal-title"><?=__('Upload CSV file')?></h4>
            </div>

            <div class="modal-body">
                <?= FORM::open(Route::url('oc-panel',['controller'=>'tools','action'=>'import_tool'.'?id_parent='.HTML::chars(Core::get('id_location', 1))]), ['enctype'=>'multipart/form-data'])?>
                    <h5><?=__('Country')?></h5>

                    <div class="form-group">
                        <?
                            $countries = [
                                'AD-Andorra' => 'Andorra',
                                'AF-Afghanistan' => 'Afghanistan',
                                'AG-Antigua-and-Barbuda' => 'Antigua and Barbuda',
                                'AL-Albania' => 'Albania',
                                'AO-Angola' => 'Angola',
                                'AR-Argentina' => 'Argentina',
                                'AT-Austria' => 'Austria',
                                'AU-Australia' => 'Australia',
                                'AW-Aruba' => 'Aruba',
                                'BB-Barbados' => 'Barbados',
                                'BE-Belgium' => 'Belgium',
                                'BM-Bermuda' => 'Bermuda',
                                'BO-Bolivia' => 'Bolivia',
                                'BR-Brazil' => 'Brazil',
                                'BS-Bahamas' => 'Bahamas',
                                'BZ-Belize' => 'Belize',
                                'CA-Canada' => 'Canada',
                                'CH-Switzerland' => 'Switzerland',
                                'CI-Ivory-Coast' => 'Ivory Coast',
                                'CL-Chile' => 'Chile',
                                'CN-China' => 'China',
                                'CR-Costa-Rica' => 'Costa-Rica',
                                'CU-Cuba' => 'Cuba',
                                'CZ-Czech-Republic' => 'Czech Republic',
                                'DE-Germany' => 'Germany',
                                'DK-Denmark' => 'Denmark',
                                'DO-Dominican-Republic' => 'Dominican Republic',
                                'DZ-Algeria' => 'Algeria',
                                'EC-Ecuador' => 'Ecuador',
                                'ES-Spain' => 'Spain',
                                'FI-Finland' => 'Finland',
                                'FR-France' => 'France',
                                'GB-United-Kingdom' => 'United Kingdom',
                                'GH-Ghana' => 'Ghana',
                                'GL-Greenland' => 'Greenland',
                                'GQ-Equatorial-Guinea' => 'Equatorial Guinea',
                                'GR-Greece' => 'Greece',
                                'GT-Guatemala' => 'Guatemala',
                                'GY-Guyana' => 'Guyana',
                                'HK-Hong-Kong' => 'Hong Kong',
                                'HN-Honduras' => 'Honduras',
                                'HR-Croatia' => 'Croatia',
                                'HT-Haiti' => 'Haiti',
                                'ID-Indonesia' => 'Indonesia',
                                'IE-Ireland' => 'Ireland',
                                'IL-Israel' => 'Israel',
                                'IN-India' => 'India',
                                'IR-Iran' => 'Iran',
                                'IT-Italy' => 'Italy',
                                'JM-Jamaica' => 'Jamaica',
                                'JP-Japan' => 'Japan',
                                'KE-Kenya' => 'Kenya',
                                'KH-Cambodia' => 'Cambodia',
                                'LB-Lebanon' => 'Lebanon',
                                'LC-Saint-Lucia' => 'Saint Lucia',
                                'LK-Sri-Lanka' => 'Afghanistan',
                                'LU-Luxembourg' => 'Luxembourg',
                                'LV-Latvia' => 'Latvia',
                                'MA-Morocco' => 'Morocco',
                                'MX-Mexico' => 'Mexico',
                                'MY-Malaysia' => 'Malaysia',
                                'NG-Nigeria' => 'Nigeria',
                                'NI-Nicaragua' => 'Nicaragua',
                                'NL-Netherlands' => 'Netherlands',
                                'NO-Norway' => 'Norway',
                                'PA-Panama' => 'Panama',
                                'PE-Peru' => 'Peru',
                                'PG-Papua-New-Guinea' => 'Papua New Guinea',
                                'PH-Philippines' => 'Philippines',
                                'PK-Pakistan' => 'Pakistan',
                                'PL-Poland' => 'Poland',
                                'PR-Puerto-Rico' => 'Puerto Rico',
                                'PS-Palestinian-Territory' => 'Palestinian Territory',
                                'PT-Portugal' => 'Portugal',
                                'PY-Paraguay' => 'Paraguay',
                                'RO-Romania' => 'Romania',
                                'RS-Serbia' => 'Serbia',
                                'RU-Russia' => 'Russia',
                                'SE-Sweden' => 'Sweden',
                                'SG-Singapore' => 'Singapore',
                                'SM-San-Marino' => 'San Marino',
                                'SV-El-Salvador' => 'El Salvador',
                                'TH-Thailand' => 'Thailand',
                                'TR-Turkey' => 'Turkey',
                                'UA-Ukraine' => 'Ukraine',
                                'US-United-States' => 'United States',
                                'UY-Uruguay' => 'Uruguay',
                                'VE-Venezuela' => 'Venezuela',
                                'ZA-South-Africa' => 'South Africa',
                                'ZW-Zimbabwe' => 'Zimbabwe',
                            ];
                        ?>
                        <?= FORM::select('country', $countries, NULL, [
                            'class' => 'tips form-control',
                            'id' => 'country',
                        ]) ?>
                    </div>

                    <?=FORM::button('submit', __('Import'), ['type'=>'submit', 'class'=>'btn btn-primary'])?>
                <?=FORM::close()?>

                <hr>

                <?= FORM::open(Route::url('oc-panel',['controller'=>'tools','action'=>'import_tool'.'?id_parent='.HTML::chars(Core::get('id_location', 1))]), ['enctype'=>'multipart/form-data'])?>
                    <h5><?=__('Upload CSV file')?></h5>

                    <p>
                        <?=__('Please use the correct CSV format')?> <a href="https://docs.google.com/uc?id=0B60e9iwQucDwa2VjRXAtV0FXVlk&export=download"><?=__('download example')?>.</a>
                    </p>

                    <div class="form-group">
                        <label class="control-label" for="csv_file_locations"><?=__('Select file')?></label>
                        <input type="file" name="csv_file_locations" id="csv_file_locations" class="form-control">
                    </div>

                    <?=FORM::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',['controller'=>'tools','action'=>'import_tool'.'?id_parent='.HTML::chars(Core::get('id_location', 1))])])?>
                <?=FORM::close()?>
            </div>
        </div>
    </div>
</div>
