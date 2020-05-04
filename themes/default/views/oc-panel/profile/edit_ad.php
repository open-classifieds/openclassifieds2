<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="page-header">
    <a class="btn btn-primary pull-right" target="_blank" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
        <?=_e('View Advertisement')?>
    </a>
    <h1><?=$ad->title?> <small><?=_e('Edit Advertisement')?></small></h1>
    <?$str=NULL;switch ($ad->status) {
        case Model_Ad::STATUS_NOPUBLISHED:
            $str = _e('NOPUBLISHED');
            break;
        case Model_Ad::STATUS_PUBLISHED:
            $str = _e('PUBLISHED');
            break;
        case Model_Ad::STATUS_UNCONFIRMED:
            $str = _e('UNCONFIRMED');
            break;
        case Model_Ad::STATUS_SPAM:
            $str = _e('SPAM');
            break;
        case Model_Ad::STATUS_UNAVAILABLE:
            $str = _e('UNAVAILABLE');
            break;
        case Model_Ad::STATUS_SOLD:
            $str = _e('SOLD');
            break;
        default:
            break;
    }?>
    <p><span class="label label-warning label-as-badge"><?=$str?></span></p>

    <? if (in_array($ad->status, [Model_Ad::STATUS_UNAVAILABLE, Model_Ad::STATUS_SOLD]) AND !in_array(core::config('general.moderation'), Model_Ad::$moderation_status)
            ):?>
            <a
                href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'activate','id'=>$ad->id_ad))?>"
                class="btn btn-success"
                title="<?=__('Activate?')?>"
                data-toggle="confirmation"
                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                data-btnCancelLabel="<?=__('No way!')?>">
                <i class="glyphicon glyphicon-ok"></i> <?=_e('Activate')?>
            </a>
    <?endif?>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- PAYPAL buttons to featured and to top -->
        <?if((core::config('payment.pay_to_go_on_top') > 0
                AND core::config('payment.to_top') != FALSE )
                OR (core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql() )):?>
            <div id="recomentadion" class="well recomentadion clearfix">
                <?if(core::config('payment.pay_to_go_on_top') > 0 AND core::config('payment.to_top') != FALSE):?>
                    <p class="text-info"><?=_e('Your Advertisement can go on top again! For only ').i18n::format_currency(core::config('payment.pay_to_go_on_top'),core::config('payment.paypal_currency'));?></p>
                    <a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_top','controller'=>'ad','id'=>$ad->id_ad))?>"><?=_e('Go Top!')?></a>
                <?endif?>
                <?if(core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql()):?>
                    <p class="text-info"><?=_e('Your Advertisement can go to featured! For only ').i18n::format_currency(Model_Order::get_featured_price(),core::config('payment.paypal_currency'));?></p>
                    <a class="btn btn-xs btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>"><?=_e('Go Featured!')?></a>
                <?endif?>
            </div>
        <?endif?>
        <!-- end paypal button -->

        <?if (core::count($orders) > 0) :?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?foreach ($orders as $order):?>
                        <a class="btn btn-warning" href="<?=Route::url('default', array('controller'=> 'ad','action'=>'checkout' , 'id' => $order->id_order))?>">
                            <i class="glyphicon glyphicon-shopping-cart"></i> <?=_e('Pay')?> <?=$order->description?>  
                        </a>
                    <?endforeach?>
                </div>
            </div>
        <?endif?>

        <?if(Auth::instance()->get_user()->is_admin()):?>
            <div class="panel panel-default">
                <? $owner = new Model_User($ad->id_user)?>
                <table class="table table-bordered admin-table-user">
                    <tr>
                        <th><?=_e('Id_User')?></th>
                        <th><?=_e('Profile')?></th>
                        <th><?=_e('Name')?></th>
                        <th><?=_e('Email')?></th>
                        <th><?=_e('Status')?></th>
                    </tr>
                    <tbody>
                        <tr>
                            <td><p><?= $ad->id_user?></p></td>
                            <td>
                                <a href="<?=Route::url('profile', array('seoname'=>$owner->seoname))?>" alt="<?=HTML::chars($owner->seoname)?>"><?= $owner->seoname?></a>
                            </td>
                            <td><p><?= $owner->name?></p></td>
                            <td>
                                <a href="<?=Route::url('contact')?>"><?= $owner->email?></a>
                            </td>
                            <td>
                                <?$str=NULL;switch ($ad->status) {
                                    case Model_Ad::STATUS_NOPUBLISHED:
                                        $str = _e('NOPUBLISHED');
                                        break;
                                    case Model_Ad::STATUS_PUBLISHED:
                                        $str = _e('PUBLISHED');
                                        break;
                                    case Model_Ad::STATUS_UNCONFIRMED:
                                        $str = _e('UNCONFIRMED');
                                        break;
                                    case Model_Ad::STATUS_SPAM:
                                        $str = _e('SPAM');
                                        break;
                                    case Model_Ad::STATUS_UNAVAILABLE:
                                        $str = _e('UNAVAILABLE');
                                        break;
                                    case Model_Ad::STATUS_SOLD:
                                        $str = _e('SOLD');
                                        break;
                                    default:
                                        break;
                                }?>
                                <b><?=$str?></b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?endif?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= _e('Ad Details') ?></h3>
            </div>
            <div class="panel-body">
                <?= FORM::open(Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad)), array('class'=>'form-horizontal edit_ad_form', 'enctype'=>'multipart/form-data'))?>
                    <fieldset>

                        <? if (Core::config('general.multilingual')) : ?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= Form::label('locale', _e('Language'), array('class'=>'', 'for'=>'locale'))?>
                                    <?= Form::select('locale', i18n::get_selectable_languages(), $ad->locale, array('class' => 'form-control', 'id' => 'locale', 'required'))?>
                                </div>
                            </div>
                        <? endif ?>

                        <div class="form-group">
                            <div class="col-sm-8 col-xs-12">
                                <?= FORM::label('title', _e('Title'), array('class'=>'', 'for'=>'title'))?>
                                <?= FORM::input('title', $ad->title, array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
                            </div>
                        </div>
                        <!-- category select -->
                        <div class="form-group">
                            <div class="col-md-8 col-xs-12">
                                <?= FORM::label('category', _e('Category'), array('for'=>'category'))?>
                                <div id="category-chained" class="row hidden"
                                    data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'categories'))?>"
                                    data-price0="<?=i18n::money_format(0)?>"
                                    <?=(core::config('advertisement.parent_category')) ? 'data-isparent' : NULL?>
                                >
                                    <div id="select-category-template" class="col-md-6 hidden">
                                        <select class="disable-select2 select-category" placeholder="<?=__('Pick a category...')?>"></select>
                                    </div>
                                    <div id="paid-category" class="col-md-12 hidden">
                                        <span class="help-block" data-title="<?=__('Category %s is a paid category: %d')?>"><span class="text-warning"></span></span>
                                    </div>
                                </div>
                                <div id="category-edit" class="row">
                                    <div class="col-md-8 col-xs-12">
                                        <div class="input-group">
                                            <input class="form-control" type="text" placeholder="<?=$ad->category->name?>" disabled>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><?=_e('Edit category')?></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <input id="category-selected" name="category" value="<?=$ad->id_category?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;" required></input>
                            </div>
                        </div>

                        <!-- location select -->
                        <?if(core::config('advertisement.location')):?>
                            <div class="form-group">
                                <div class="col-md-8">
                                    <?= FORM::label('locations', _e('Location'), array('for'=>'location'))?>
                                    <div id="location-chained" class="row hidden" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                        <div id="select-location-template" class="col-md-6 hidden">
                                            <select class="disable-select2 select-location" placeholder="<?=__('Pick a location...')?>"></select>
                                        </div>
                                    </div>
                                    <div id="location-edit" class="row">
                                        <div class="col-md-8 col-xs-12">
                                            <div class="input-group">
                                                <input class="form-control" type="text" placeholder="<?=$ad->location->name?>" disabled>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button"><?=_e('Edit location')?></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <input id="location-selected" name="location" value="<?=$ad->id_location?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;" required></input>
                                </div>
                            </div>
                        <?endif?>

                        <?if(core::config('advertisement.description') != FALSE):?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= FORM::label('description', _e('Description'), array('class'=>'', 'for'=>'description', 'spellcheck'=>TRUE))?>
                                    <?= FORM::textarea('description', $ad->description, array('class'=>'form-control col-md-9 col-sm-9 col-xs-12'.((Core::config("advertisement.description_bbcode"))?NULL:' disable-bbcode'), 'name'=>'description', 'id'=>'description', 'rows'=>8, 'required'))?>
                                </div>
                            </div>
                        <?endif?>
                        <?if(core::config('advertisement.phone') != FALSE):?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= FORM::label('phone', _e('Phone'), array('class'=>'', 'for'=>'phone'))?>
                                    <?= FORM::input('phone', $ad->phone, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone'), 'data-country' => core::config('general.country')))?>
                                </div>
                            </div>
                        <?endif?>
                        <?if(core::config('advertisement.address') != FALSE):?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= FORM::label('address', _e('Address'), array('class'=>'', 'for'=>'address'))?>
                                    <?if(core::config('advertisement.map_pub_new')):?>
                                        <?if (Core::is_HTTPS()):?>
                                            <div class="input-group">
                                                <?= FORM::input('address', $ad->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default locateme" type="button"><?=_e('Locate me')?></button>
                                                </span>
                                            </div>
                                        <?else:?>
                                            <?=FORM::input('address', $ad->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                        <?endif?>
                                    <?else:?>
                                        <?= FORM::input('address', $ad->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                    <?endif?>
                                </div>
                            </div>
                            <?if(core::config('advertisement.map_pub_new')):?>
                                <div class="popin-map-container">
                                    <div class="map-inner" id="map"
                                        data-lat="<?=($ad->latitude)? $ad->latitude:core::config('advertisement.center_lat')?>"
                                        data-lon="<?=($ad->longitude)? $ad->longitude:core::config('advertisement.center_lon')?>"
                                        data-zoom="<?=core::config('advertisement.map_zoom')?>"
                                        style="height:200px;max-width:400px;margin-bottom:5px;">
                                    </div>
                                </div>
                                <input type="hidden" name="latitude" id="publish-latitude" value="<?=$ad->latitude?>" <?=is_null($ad->latitude) ? 'disabled': NULL?>>
                                <input type="hidden" name="longitude" id="publish-longitude" value="<?=$ad->longitude?>" <?=is_null($ad->longitude) ? 'disabled': NULL?>>
                            <?endif?>
                        <?endif?>
                        <?if(core::config('payment.stock')):?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= FORM::label('stock', _e('In Stock'), array('class'=>'', 'for'=>'stock'))?>
                                    <div class="input-prepend">
                                    <?= FORM::input('stock', $ad->stock, array('placeholder' => '10', 'class' => 'form-control', 'id' => 'stock', 'type'=>'text'))?>
                                    </div>
                                </div>
                            </div>
                        <?endif?>
                        <?if(core::config('advertisement.price') != FALSE):?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= FORM::label('price', _e('Price'), array('class'=>'', 'for'=>'price'))?>
                                    <div class="input-prepend">
                                        <?= FORM::input('price', i18n::format_currency_without_symbol($ad->price), array('placeholder'=>html_entity_decode(i18n::money_format(1)),'class'=>'form-control', 'id' => 'price', 'data-error' => __('Please enter only numbers.'), 'data-decimal_point' => i18n::get_decimal_point()))?>
                                    </div>
                                </div>
                            </div>
                        <?endif?>
                        <?if(core::config('advertisement.website') != FALSE):?>
                            <div class="form-group">
                                <div class="col-sm-8 col-xs-12">
                                    <?= FORM::label('website', _e('Website'), array('class'=>'', 'for'=>'website'))?>
                                    <?= FORM::input('website', $ad->website, array('class'=>'form-control', 'id'=>'website', 'placeholder'=>__('Website')))?>
                                </div>
                            </div>
                        <?endif?>
                        <!-- Fields coming from custom fields feature -->
                        <?if (Core::extra_features() == TRUE):?>
                            <div id="custom-fields"
                                data-customfield-values='<?=htmlspecialchars(json_encode($ad->custom_columns(0, TRUE)), ENT_QUOTES)?>'
                                <?=(Auth::instance()->get_user()->is_admin() OR Auth::instance()->get_user()->is_moderator()) ? 'data-admin-privilege': NULL?>
                            >
                                <div id="custom-field-template" class="form-group hidden">
                                    <div class="col-sm-8 col-xs-12">
                                        <div data-label></div>
                                        <div data-input></div>
                                    </div>
                                </div>
                            </div>
                        <?endif?>
                        <!-- /endcustom fields -->
                        <hr>
                        <?= FORM::button('submit_btn', (in_array(core::config('general.moderation'), Model_Ad::$moderation_status))?_e('Publish'):_e('Update'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))))?>
                    </fieldset>
                <?= FORM::close()?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= _e('Manage Images') ?></h3>
            </div>
            <div class="panel-body">
                <?= FORM::open(Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)), array('class' => 'form-horizontal edit_ad_photos_form', 'enctype' => 'multipart/form-data')) ?>
                    <fieldset>
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <? $images = $ad->get_images() ?>
                                    <? if ($images) : ?>
                                        <div id="gallery">
                                            <? foreach ($images as $key => $value) : ?>
                                                <? if (isset($value['thumb'])) : // only formated images (not originals)?>
                                                    <div id="img<?= $key ?>" class="col-md-4 col-sm-4 col-md-4 edit-image">
                                                        <a href="<?=$value['image']?>" class="gallery-item" data-gallery>
                                                            <img src="<?= $value['thumb'] ?>" class="thumbnail img-rounded img-responsive">
                                                        </a>
                                                        <button class="btn btn-danger index-delete img-delete"
                                                                data-title="<?= __('Are you sure you want to delete?') ?>"
                                                                data-btnOkLabel="<?= __('Yes, definitely!') ?>"
                                                                data-btnCancelLabel="<?= __('No way!') ?>"
                                                                type="submit"
                                                                name="img_delete"
                                                                value="<?= $key ?>"
                                                                href="<?= Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)) ?>">
                                                                <?= _e('Delete') ?>
                                                        </button>
                                                        <? if ($key > 1) : ?>
                                                            <button class="btn btn-info img-primary"
                                                                type="submit"
                                                                name="primary_image"
                                                                value="<?= $key ?>"
                                                                href="<?= Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)) ?>"
                                                                action="<?= Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)) ?>"
                                                            >
                                                                    <?= _e('Primary image') ?>
                                                            </button>
                                                        <? endif ?>
                                                    </div>
                                                <? endif ?>
                                            <? endforeach ?>
                                        </div>
                                    <? endif ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group images"
                             data-max-files="<?= (core::config("advertisement.num_images") - core::count($images)) ?>"
                             data-max-image-size="<?=core::config('image.max_image_size')?>"
                             data-image-width="<?=core::config('image.width')?>"
                             data-image-height="<?=core::config('image.height') ? core::config('image.height') : ''?>">
                            <div class="col-sm-12">
                                <label><?=_e('Add image')?></label>
                                <div class="dropzone" id="images-dropzone"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <?= FORM::button('submit_btn', _e('Upload'), array('id' => 'upload-photos-btn', 'type' => 'submit', 'class' => 'btn btn-primary', 'action' => Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)))) ?>
                            </div>
                        </div>
                    </fieldset>
                <?= FORM::close() ?>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-statc fade" id="processing-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-body">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?=_e('Processing...')?></h4>
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?if (core::config("advertisement.num_images") > 0 AND core::config('image.upload_from_url')):?>
    <?for ($i=0; $i < core::config("advertisement.num_images") ; $i++):?>
        <div class="modal fade" id="<?='urlInputimage'.$i?>" tabindex="-1" role="dialog" aria-labelledby="<?='urlInputimage'.$i?>Label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="imageURL">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="<?='urlInput'.$i?>Label"><?=_e('Insert Image')?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><?=_e('Image URL')?></label>
                                <input name="<?='image'.$i?>" class="note-image-url form-control" type="text">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><?=_e('Insert Image')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?endfor?>
<?endif?>

<!-- The modal dialog, which will be used to wrap the lightbox content -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<?=View::factory('pages/ad/new_scripts')?>
