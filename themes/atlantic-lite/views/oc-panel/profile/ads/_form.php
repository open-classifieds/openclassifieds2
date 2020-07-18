<?= FORM::open(Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad)), array('class'=>'edit_ad_form', 'enctype'=>'multipart/form-data'))?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= _e('Ad Details') ?></h5>
                <? if (Core::config('general.multilingual')) : ?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= Form::label('locale', _e('Language'), array('for'=>'locale'))?>
                            <?= Form::select('locale', i18n::get_selectable_languages(), $ad->locale, array('class' => 'form-control', 'id' => 'locale', 'required'))?>
                        </div>
                    </div>
                <? endif ?>

                <div class="form-row">
                    <div class="form-group col-sm-8">
                        <?= FORM::label('title', _e('Title'), array('for'=>'title'))?>
                        <?= FORM::input('title', $ad->title, array('placeholder' => __('Title'), 'class' => 'form-control', 'id' => 'title', 'required'))?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-8">
                        <?= FORM::label('category', _e('Category'), array('for'=>'category'))?>
                        <div id="category-chained" class="row d-none"
                            data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'categories'))?>"
                            data-price0="<?=i18n::money_format(0)?>"
                            <?=(core::config('advertisement.parent_category')) ? 'data-isparent' : NULL?>
                        >
                            <div id="select-category-template" class="col-md-6 d-none">
                                <select class="disable-select2 select-category" placeholder="<?=__('Pick a category...')?>"></select>
                            </div>
                            <div id="paid-category" class="col-md-12 d-none">
                                <span class="help-block" data-title="<?=__('Category %s is a paid category: %d')?>"><span class="text-warning"></span></span>
                            </div>
                        </div>
                        <div id="category-edit" class="row">
                            <div class="col-md-8 col-xs-12">
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="<?=$ad->category->name?>" disabled>
                                    <span class="input-group-append">
                                        <button class="btn btn-primary" type="button"><?=_e('Edit category')?></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input id="category-selected" name="category" value="<?=$ad->id_category?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;" required></input>
                    </div>
                </div>

                <?if(core::config('advertisement.location')):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('locations', _e('Location'), array('for'=>'location'))?>
                            <div id="location-chained" class="row d-none" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                <div id="select-location-template" class="col-md-6 d-none">
                                    <select class="disable-select2 select-location" placeholder="<?=__('Pick a location...')?>"></select>
                                </div>
                            </div>
                            <div id="location-edit" class="row">
                                <div class="col-md-8 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="<?=$ad->location->name?>" disabled>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="button"><?=_e('Edit location')?></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <input id="location-selected" name="location" value="<?=$ad->id_location?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;" required></input>
                        </div>
                    </div>
                <?endif?>

                <?if(core::config('advertisement.description') != FALSE):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('description', _e('Description'), array('for'=>'description', 'spellcheck'=>TRUE))?>
                            <?= FORM::textarea('description', $ad->description, array('class'=>'form-control col-md-9 col-sm-9 col-xs-12'.((Core::config("advertisement.description_bbcode"))?NULL:' disable-bbcode'), 'name'=>'description', 'id'=>'description', 'rows'=>8, 'required'))?>
                        </div>
                    </div>
                <?endif?>

                <?if(core::config('advertisement.phone') != FALSE):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('phone', _e('Phone'), array('for'=>'phone'))?>
                            <?= FORM::input('phone', $ad->phone, array('class'=>'form-control', 'id'=>'phone', 'placeholder'=>__('Phone'), 'data-country' => core::config('general.country')))?>
                        </div>
                    </div>
                <?endif?>

                <?if(core::config('advertisement.address') != FALSE):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('address', _e('Address'), array('for'=>'address'))?>
                            <?if(core::config('advertisement.map_pub_new')):?>
                                <?if (Core::is_HTTPS()):?>
                                    <div class="input-group">
                                        <?= FORM::input('address', $ad->address, array('class'=>'form-control', 'id'=>'address', 'placeholder'=>__('Address')))?>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary locateme" type="button"><?=_e('Locate me')?></button>
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
                            <div
                                class="map-inner"
                                id="map"
                                data-lat="<?=($ad->latitude)? $ad->latitude:core::config('advertisement.center_lat')?>"
                                data-lon="<?=($ad->longitude)? $ad->longitude:core::config('advertisement.center_lon')?>"
                                data-zoom="<?=core::config('advertisement.map_zoom')?>"
                                style="height:200px;max-width:400px;margin-bottom:5px;"
                            ></div>
                        </div>
                        <input type="hidden" name="latitude" id="publish-latitude" value="<?=$ad->latitude?>" <?=is_null($ad->latitude) ? 'disabled': NULL?>>
                        <input type="hidden" name="longitude" id="publish-longitude" value="<?=$ad->longitude?>" <?=is_null($ad->longitude) ? 'disabled': NULL?>>
                    <?endif?>
                <?endif?>

                <?if(core::config('payment.stock')):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('stock', _e('In Stock'), array('for'=>'stock'))?>
                            <div class="input-prepend">
                            <?= FORM::input('stock', $ad->stock, array('placeholder' => '10', 'class' => 'form-control', 'id' => 'stock', 'type'=>'text'))?>
                            </div>
                        </div>
                    </div>
                <?endif?>

                <?if(core::config('advertisement.price') != FALSE):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('price', _e('Price'), array('for'=>'price'))?>
                            <div class="input-prepend">
                                <?= FORM::input('price', i18n::format_currency_without_symbol($ad->price), array('placeholder'=>html_entity_decode(i18n::money_format(1)),'class'=>'form-control', 'id' => 'price', 'data-error' => __('Please enter only numbers.'), 'data-decimal_point' => i18n::get_decimal_point()))?>
                            </div>
                        </div>
                    </div>
                <?endif?>

                <?if(core::config('advertisement.website') != FALSE):?>
                    <div class="form-row">
                        <div class="form-group col-sm-8">
                            <?= FORM::label('website', _e('Website'), array('for'=>'website'))?>
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
                        <div id="custom-field-template" class="form-group d-none">
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
        </div>
    </div>
<?= FORM::close()?>
