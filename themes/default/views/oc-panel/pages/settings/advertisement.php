<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title"><?=__('Advertisement Configuration')?></h1>

<hr>

<div class="row">
    <div class="col-lg-12">
        <?=FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')), array('class'=>'config ajax-load', 'enctype'=>'multipart/form-data'))?>
            <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left" id="tab-settings" style="background: #fff;">
                <li class="active">
                    <a data-toggle="tab" href="#tabSettingsListing" aria-expanded="true"><?=__('Listing Options')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsPublish" aria-expanded="false"><?=__('Publish Options')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsFields" aria-expanded="false"><?=__('Advertisement Fields')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsDisplay" aria-expanded="false"><?=__('Display Options')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsMap" aria-expanded="false"><?=__('Google Maps')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsReview" aria-expanded="false"><?=__('Reviews')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsDropbox" aria-expanded="false"><?=__('Dropbox')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsPicker" aria-expanded="false"><?=__('Google Picker')?></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tabSettingsSocial" aria-expanded="false"><?=__('Social')?></a>
                </li>
            </ul>
            <div class="tab-content" style="background: #fff;">
                <div id="tabSettingsListing" class="tab-pane active">
                    <h4><?=__('Listing Options')?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-change-settings-for-ads/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>
                    <div>
                        <?foreach ($config as $c):?>
                            <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                        <?endforeach?>
                        <div class="form-group">
                            <?=FORM::label($forms['advertisements_per_page']['key'], __('Advertisements per page'), array('class'=>'control-label', 'for'=>$forms['advertisements_per_page']['key']))?>
                            <?=FORM::input($forms['advertisements_per_page']['key'], $forms['advertisements_per_page']['value'], array(
                                'placeholder' => "20",
                                'class' => 'form-control',
                                'id' => $forms['advertisements_per_page']['key'],
                                'type' => 'number',
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                                ))?>
                            <span class="help-block">
                                <?=__("This is to control how many advertisements are being displayed per page. Insert an integer value, as a number limit.")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['feed_elements']['key'], __('Advertisements in RSS'), array('class'=>'control-label', 'for'=>$forms['feed_elements']['key']))?>
                            <?=FORM::input($forms['feed_elements']['key'], $forms['feed_elements']['value'], array(
                                'placeholder' => "20",
                                'class' => 'form-control',
                                'id' => $forms['feed_elements']['key'],
                                'type' => 'number',
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                            ))?>
                            <span class="help-block">
                                <?=__("How many ads are going to appear in the RSS of your site.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['map_elements']['key'], __('Advertisements in Map'), array('class'=>'control-label', 'for'=>$forms['map_elements']['key']))?>
                            <?=FORM::input($forms['map_elements']['key'], $forms['map_elements']['value'], array(
                                'placeholder' => "20",
                                'class' => 'form-control',
                                'id' => $forms['map_elements']['key'],
                                'type' => 'number',
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                            ))?>
                           <span class="help-block">
                                <?=__("How many ads are going to appear in the map of your site.")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['sort_by']['key'], __('Sort by in listing'), array('class'=>'control-label', 'for'=>$forms['sort_by']['key']))?>
                            <?= FORM::select($forms['sort_by']['key'], array('title-asc'=>"Name (A-Z)",
                                                                                 'title-desc'=>"Name (Z-A)",
                                                                                 'price-asc'=>"Price (Low)",
                                                                                 'price-desc'=>"Price (High)",
                                                                                 'featured'=>"Featured",
                                                                                 'rating'=>"Rating",
                                                                                 'favorited'=>"Favorited",
                                                                                 'published-desc'=>"Newest",
                                                                                 'published-asc'=>"Oldest",
                                                                                 'distance'=>"Distance"),
                                $forms['sort_by']['value'], array(
                                'placeholder' => $forms['sort_by']['value'],
                                'class' => 'tips form-control input-sm ',
                                'id' => $forms['sort_by']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Sort by in listing")?>
                            </span>
                        </div>

                        <?
                            $ads_in_home = array(0=>__('Latest Ads'),
                                                1=>__('Featured Ads'),
                                                4=>__('Featured Ads Random'),
                                                2=>__('Popular Ads last month'),
                                                3=>__('None'));

                            if(core::config('advertisement.count_visits')==0)
                                unset($ads_in_home[2]);
                        ?>

                        <div class="form-group">
                            <?=FORM::label($forms['ads_in_home']['key'], __('Advertisements in home'), array('class'=>'control-label', 'for'=>$forms['ads_in_home']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/manage-ads-slider/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <?=FORM::select($forms['ads_in_home']['key'], $ads_in_home
                                , $forms['ads_in_home']['value'], array(
                                'placeholder' => $forms['ads_in_home']['value'],
                                'class' => 'form-control ',
                                'id' => $forms['ads_in_home']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("You can choose what ads you want to display in home.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['delete_ad']['key'], __('Delete ads'), array('class'=>'control-label', 'for'=>$forms['delete_ad']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['delete_ad']['key'], 1, (bool) $forms['delete_ad']['value'], array('id' => $forms['delete_ad']['key'].'1'))?>
                                <?=Form::label($forms['delete_ad']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['delete_ad']['key'], 0, ! (bool) $forms['delete_ad']['value'], array('id' => $forms['delete_ad']['key'].'0'))?>
                                <?=Form::label($forms['delete_ad']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("With this option enabled your customers will be able to delete permanently his ads.")?>
                            </span>
                        </div>

                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
                <div id="tabSettingsPublish" class="tab-pane fade">
                    <h4><?=__('Publish Options')?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-configure-publish-options/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['login_to_post']['key'], __('Require login to post'), array('class'=>'control-label', 'for'=>$forms['login_to_post']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/force-registration-posting-new-ad/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['login_to_post']['key'], 1, (bool) $forms['login_to_post']['value'], array('id' => $forms['login_to_post']['key'].'1'))?>
                                <?=Form::label($forms['login_to_post']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['login_to_post']['key'], 0, ! (bool) $forms['login_to_post']['value'], array('id' => $forms['login_to_post']['key'].'0'))?>
                                <?=Form::label($forms['login_to_post']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Require only the logged in users to post.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['only_admin_post']['key'], __('Only administrators can publish'), array('class'=>'control-label', 'for'=>$forms['only_admin_post']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['only_admin_post']['key'], 1, (bool) $forms['only_admin_post']['value'], array('id' => $forms['only_admin_post']['key'].'1'))?>
                                <?=Form::label($forms['only_admin_post']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['only_admin_post']['key'], 0, ! (bool) $forms['only_admin_post']['value'], array('id' => $forms['only_admin_post']['key'].'0'))?>
                                <?=Form::label($forms['only_admin_post']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Only administrators can publish")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['expire_date']['key'], __('Ad expiration date'), array('class'=>'control-label', 'for'=>$forms['expire_date']['key']))?>
                            <div class="input-group">
                                <?=FORM::input($forms['expire_date']['key'], $forms['expire_date']['value'], array(
                                    'placeholder' => $forms['expire_date']['value'],
                                    'class' => 'form-control',
                                    'id' => $forms['expire_date']['key'],
                                    'type' => 'number',
                                    'data-rule-required'=>'true',
                                    'data-rule-digits' => 'true',
                                    ));?>
                                    <span class="input-group-addon"><?=__("Days")?></span>
                            </div>
                            <span class="help-block">
                                <?=__("After how many days an Ad will expire. 0 for never")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['expire_reactivation']['key'], __('Allow ad reactivation'), array('class'=>'control-label', 'for'=>$forms['expire_reactivation']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['expire_reactivation']['key'], 1, (bool) $forms['expire_reactivation']['value'], array('id' => $forms['expire_reactivation']['key'].'1'))?>
                                <?=Form::label($forms['expire_reactivation']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['expire_reactivation']['key'], 0, ! (bool) $forms['expire_reactivation']['value'], array('id' => $forms['expire_reactivation']['key'].'0'))?>
                                <?=Form::label($forms['expire_reactivation']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Allows reactivate ad after is expired.")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['parent_category']['key'], __('Parent category'), array('class'=>'control-label', 'for'=>$forms['parent_category']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['parent_category']['key'], 1, (bool) $forms['parent_category']['value'], array('id' => $forms['parent_category']['key'].'1'))?>
                                <?=Form::label($forms['parent_category']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['parent_category']['key'], 0, ! (bool) $forms['parent_category']['value'], array('id' => $forms['parent_category']['key'].'0'))?>
                                <?=Form::label($forms['parent_category']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Use parent categories")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['map_pub_new']['key'], __('Google Maps in Publish New'), array('class'=>'control-label', 'for'=>$forms['map_pub_new']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['map_pub_new']['key'], 1, (bool) $forms['map_pub_new']['value'], array('id' => $forms['map_pub_new']['key'].'1'))?>
                                <?=Form::label($forms['map_pub_new']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['map_pub_new']['key'], 0, ! (bool) $forms['map_pub_new']['value'], array('id' => $forms['map_pub_new']['key'].'0'))?>
                                <?=Form::label($forms['map_pub_new']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Displays the google maps in the Publish new form.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['description_bbcode']['key'], __('BBCODE editor on description field'), array('class'=>'control-label', 'for'=>$forms['description_bbcode']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['description_bbcode']['key'], 1, (bool) $forms['description_bbcode']['value'], array('id' => $forms['description_bbcode']['key'].'1'))?>
                                <?=Form::label($forms['description_bbcode']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['description_bbcode']['key'], 0, ! (bool) $forms['description_bbcode']['value'], array('id' => $forms['description_bbcode']['key'].'0'))?>
                                <?=Form::label($forms['description_bbcode']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("BBCODE editor appears in description field.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['captcha']['key'], __('Captcha'), array('class'=>'control-label', 'for'=>$forms['captcha']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['captcha']['key'], 1, (bool) $forms['captcha']['value'], array('id' => $forms['captcha']['key'].'1'))?>
                                <?=Form::label($forms['captcha']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['captcha']['key'], 0, ! (bool) $forms['captcha']['value'], array('id' => $forms['captcha']['key'].'0'))?>
                                <?=Form::label($forms['captcha']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Captcha appears in the form.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['leave_alert']['key'], __('Leave alert before submitting form'), array('class'=>'control-label', 'for'=>$forms['leave_alert']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['leave_alert']['key'], 1, (bool) $forms['leave_alert']['value'], array('id' => $forms['leave_alert']['key'].'1'))?>
                                <?=Form::label($forms['leave_alert']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['leave_alert']['key'], 0, ! (bool) $forms['leave_alert']['value'], array('id' => $forms['leave_alert']['key'].'0'))?>
                                <?=Form::label($forms['leave_alert']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Leave alert before submitting publish new form")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?$pages = array(''=>__('Deactivated'))?>
                            <?foreach (Model_Content::get_pages() as $key => $value) {
                                $pages[$value->seotitle] = $value->title;
                            }?>
                            <?=FORM::label($forms['tos']['key'], __('Terms of Service'), array('class'=>'control-label', 'for'=>$forms['tos']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/how_to_add_pages/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <?=FORM::select($forms['tos']['key'], $pages, $forms['tos']['value'], array(
                                'placeholder' => "http://foo.com/",
                                'class' => 'tips form-control',
                                'id' => $forms['tos']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("If you choose to use terms of service, you can select activate. And to edit content, select link 'Content' on your admin panel sidebar. Find page named 'Terms of service' click 'Edit'. In section 'Description' add content that suits you.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['thanks_page']['key'], __('Thanks page'), array('class'=>'control-label', 'for'=>$forms['thanks_page']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/thanks-page/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <?=FORM::select($forms['thanks_page']['key'], $pages, $forms['thanks_page']['value'], array(
                                'placeholder' => "",
                                'class' => 'form-control',
                                'id' => $forms['tos']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Content that will be displayed to the user after he publishes an ad")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['banned_words']['key'], __('Banned words'), array('class'=>'control-label', 'for'=>$forms['banned_words']['key']))?>
                            <div>
                                <?=FORM::input($forms['banned_words']['key'], $forms['banned_words']['value'], array(
                                    'placeholder' => __('For banned word push enter.'),
                                    'class' => 'form-control',
                                    'id' => $forms['banned_words']['key'],
                                    'data-role'=>'tagsinput',
                                ))?>
                            </div>
                            <span class="help-block">
                                <?=__("You need to write your banned words to enable the service.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['validate_banned_words']['key'], __('Validate banned words'), array('class'=>'control-label', 'for'=>$forms['validate_banned_words']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['validate_banned_words']['key'], 1, (bool) $forms['validate_banned_words']['value'], array('id' => $forms['validate_banned_words']['key'].'1'))?>
                                <?=Form::label($forms['validate_banned_words']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['validate_banned_words']['key'], 0, ! (bool) $forms['validate_banned_words']['value'], array('id' => $forms['validate_banned_words']['key'].'0'))?>
                                <?=Form::label($forms['validate_banned_words']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enables banned words validation")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['banned_words_replacement']['key'], __('Banned words replacement'), array('class'=>'control-label', 'for'=>$forms['banned_words_replacement']['key']))?>
                            <?=FORM::input($forms['banned_words_replacement']['key'], $forms['banned_words_replacement']['value'], array(
                                'placeholder' => "xxx",
                                'class' => 'form-control',
                                'id' => $forms['banned_words_replacement']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Banned word replacement replaces selected array with the string you provided.")?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
                <div id="tabSettingsFields" class="tab-pane fade">
                    <h4><?=__('Advertisement Fields')?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-manage-advertisement-fields//">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=FORM::label($forms['description']['key'], __('Description'), array('class'=>'control-label', 'for'=>$forms['description']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['description']['key'], 1, (bool) $forms['description']['value'], array('id' => $forms['description']['key'].'1'))?>
                                    <?=Form::label($forms['description']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['description']['key'], 0, ! (bool) $forms['description']['value'], array('id' => $forms['description']['key'].'0'))?>
                                    <?=Form::label($forms['description']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Displays the field Description in the Ad form.")?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=FORM::label($forms['address']['key'], __('Address'), array('class'=>'control-label', 'for'=>$forms['address']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['address']['key'], 1, (bool) $forms['address']['value'], array('id' => $forms['address']['key'].'1'))?>
                                    <?=Form::label($forms['address']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['address']['key'], 0, ! (bool) $forms['address']['value'], array('id' => $forms['address']['key'].'0'))?>
                                    <?=Form::label($forms['address']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Displays the field Address in the Ad form.")?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=FORM::label($forms['phone']['key'], __('Phone'), array('class'=>'control-label', 'for'=>$forms['phone']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['phone']['key'], 1, (bool) $forms['phone']['value'], array('id' => $forms['phone']['key'].'1'))?>
                                    <?=Form::label($forms['phone']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['phone']['key'], 0, ! (bool) $forms['phone']['value'], array('id' => $forms['phone']['key'].'0'))?>
                                    <?=Form::label($forms['phone']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Displays the field Phone in the Ad form.")?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= FORM::label($forms['website']['key'], __('Website'), array('class'=>'control-label', 'for'=>$forms['website']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['website']['key'], 1, (bool) $forms['website']['value'], array('id' => $forms['website']['key'].'1'))?>
                                    <?=Form::label($forms['website']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['website']['key'], 0, ! (bool) $forms['website']['value'], array('id' => $forms['website']['key'].'0'))?>
                                    <?=Form::label($forms['website']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Displays the field Website in the Ad form.")?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=FORM::label($forms['location']['key'], __('Location'), array('class'=>'control-label', 'for'=>$forms['location']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['location']['key'], 1, (bool) $forms['location']['value'], array('id' => $forms['location']['key'].'1'))?>
                                    <?=Form::label($forms['location']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['location']['key'], 0, ! (bool) $forms['location']['value'], array('id' => $forms['location']['key'].'0'))?>
                                    <?=Form::label($forms['location']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Displays the Select Location in the Ad form.")?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=FORM::label($forms['price']['key'], __('Price'), array('class'=>'control-label', 'for'=>$forms['price']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['price']['key'], 1, (bool) $forms['price']['value'], array('id' => $forms['price']['key'].'1'))?>
                                    <?=Form::label($forms['price']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['price']['key'], 0, ! (bool) $forms['price']['value'], array('id' => $forms['price']['key'].'0'))?>
                                    <?=Form::label($forms['price']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Displays the field Price in the Ad form.")?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?=FORM::label($forms['num_images']['key'], __('Number of images'), array('class'=>'control-label', 'for'=>$forms['num_images']['key']))?>
                                <?=FORM::input($forms['num_images']['key'], $forms['num_images']['value'], array(
                                    'placeholder' => "4",
                                    'class' => 'form-control',
                                    'id' => $forms['num_images']['key'],
                                    'data-rule-required'=>'true',
                                    'data-rule-digits' => 'true',
                                ))?>
                                <span class="help-block">
                                    <?=__("Number of images displayed")?>
                                </span>
                            </div>
                        </div>

                        <?if (!Core::config('general.messaging')) :?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?=FORM::label($forms['upload_file']['key'], __('Upload file'), array('class'=>'control-label', 'for'=>$forms['upload_file']['key']))?>
                                    <div class="radio radio-primary">
                                        <?=Form::radio($forms['upload_file']['key'], 1, (bool) $forms['upload_file']['value'], array('id' => $forms['upload_file']['key'].'1'))?>
                                        <?=Form::label($forms['upload_file']['key'].'1', __('Enabled'))?>
                                        <?=Form::radio($forms['upload_file']['key'], 0, ! (bool) $forms['upload_file']['value'], array('id' => $forms['upload_file']['key'].'0'))?>
                                        <?=Form::label($forms['upload_file']['key'].'0', __('Disabled'))?>
                                    </div>
                                    <span class="help-block">
                                        <?=__("Allows buyer to upload a file in the Ad contact form.")?>
                                    </span>
                                </div>
                            </div>
                        <?else:?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?=FORM::label($forms['upload_file']['key'], __('Upload file'), array('class'=>'control-label', 'for'=>$forms['upload_file']['key']))?>
                                    <div class="radio radio-primary">
                                        <?=Form::radio($forms['upload_file']['key'], 1, FALSE, array('id' => $forms['upload_file']['key'].'1', 'disabled'))?>
                                        <?=Form::label($forms['upload_file']['key'].'1', __('Enabled'))?>
                                        <?=Form::radio($forms['upload_file']['key'], 0, TRUE, array('id' => $forms['upload_file']['key'].'0', 'disabled'))?>
                                        <?=Form::label($forms['upload_file']['key'].'0', __('Disabled'))?>
                                    </div>
                                    <span class="help-block">
                                        <?=__("Disable the Messaging System to be able to use this feature.")?>
                                    </span>
                                </div>
                            </div>
                        <?endif?>
                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
                <div id="tabSettingsDisplay" class="tab-pane fade">
                    <h4><?=__('Advertisement Display Options')?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-configure-advertisement-display-option/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['contact']['key'], __('Contact form'), array('class'=>'control-label', 'for'=>$forms['contact']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['contact']['key'], 1, (bool) $forms['contact']['value'], array('id' => $forms['contact']['key'].'1'))?>
                                <?=Form::label($forms['contact']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['contact']['key'], 0, ! (bool) $forms['contact']['value'], array('id' => $forms['contact']['key'].'0'))?>
                                <?=Form::label($forms['contact']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Contact form appears in the ad.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['login_to_view_ad']['key'], __('Require login to view ad'), array('class'=>'control-label', 'for'=>$forms['login_to_view_ad']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['login_to_view_ad']['key'], 1, (bool) $forms['login_to_view_ad']['value'], array('id' => $forms['login_to_view_ad']['key'].'1'))?>
                                <?=Form::label($forms['login_to_view_ad']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['login_to_view_ad']['key'], 0, ! (bool) $forms['login_to_view_ad']['value'], array('id' => $forms['login_to_view_ad']['key'].'0'))?>
                                <?=Form::label($forms['login_to_view_ad']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Require only the logged in users to view ads.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['login_to_contact']['key'], __('Require login to contact'), array('class'=>'control-label', 'for'=>$forms['login_to_contact']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['login_to_contact']['key'], 1, (bool) $forms['login_to_contact']['value'], array('id' => $forms['login_to_contact']['key'].'1'))?>
                                <?=Form::label($forms['login_to_contact']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['login_to_contact']['key'], 0, ! (bool) $forms['login_to_contact']['value'], array('id' => $forms['login_to_contact']['key'].'0'))?>
                                <?=Form::label($forms['login_to_contact']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Require only the logged in users to contact.")?>
                            </span>
                        </div>

                        <?if (Core::config('general.messaging')) :?>
                            <div class="form-group">
                                <?=FORM::label($forms['contact']['key'], __('Price on contact form'), array('class'=>'control-label', 'for'=>$forms['contact']['key']))?>
                                <div class="radio radio-primary">
                                    <?=Form::radio($forms['contact_price']['key'], 1, (bool) $forms['contact_price']['value'], array('id' => $forms['contact_price']['key'].'1'))?>
                                    <?=Form::label($forms['contact_price']['key'].'1', __('Enabled'))?>
                                    <?=Form::radio($forms['contact_price']['key'], 0, ! (bool) $forms['contact_price']['value'], array('id' => $forms['contact_price']['key'].'0'))?>
                                    <?=Form::label($forms['contact_price']['key'].'0', __('Disabled'))?>
                                </div>
                                <span class="help-block">
                                    <?=__("Show price field on contact form.")?>
                                </span>
                            </div>
                        <?endif?>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['qr_code']['key'], __('Show QR code'), array('class'=>'control-label', 'for'=>$forms['qr_code']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['qr_code']['key'], 1, (bool) $forms['qr_code']['value'], array('id' => $forms['qr_code']['key'].'1'))?>
                                <?=Form::label($forms['qr_code']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['qr_code']['key'], 0, ! (bool) $forms['qr_code']['value'], array('id' => $forms['qr_code']['key'].'0'))?>
                                <?=Form::label($forms['qr_code']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Show QR code")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['map']['key'], __('Google Maps in Ad and Profile page'), array('class'=>'control-label', 'for'=>$forms['map']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['map']['key'], 1, (bool) $forms['map']['value'], array('id' => $forms['map']['key'].'1'))?>
                                <?=Form::label($forms['map']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['map']['key'], 0, ! (bool) $forms['map']['value'], array('id' => $forms['map']['key'].'0'))?>
                                <?=Form::label($forms['map']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Displays the google maps in the Ad.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['count_visits']['key'], __('Count visits ads'), array('class'=>'control-label', 'for'=>$forms['count_visits']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['count_visits']['key'], 1, (bool) $forms['count_visits']['value'], array('id' => $forms['count_visits']['key'].'1'))?>
                                <?=Form::label($forms['count_visits']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['count_visits']['key'], 0, ! (bool) $forms['count_visits']['value'], array('id' => $forms['count_visits']['key'].'0'))?>
                                <?=Form::label($forms['count_visits']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("You can choose if you wish to display amount of visits at each advertisement.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['sharing']['key'], __('Show sharing buttons'), array('class'=>'control-label', 'for'=>$forms['sharing']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['sharing']['key'], 1, (bool) $forms['sharing']['value'], array('id' => $forms['sharing']['key'].'1'))?>
                                <?=Form::label($forms['sharing']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['sharing']['key'], 0, ! (bool) $forms['sharing']['value'], array('id' => $forms['sharing']['key'].'0'))?>
                                <?=Form::label($forms['sharing']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("You can choose if you wish to display sharing buttons at each advertisement.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['report']['key'], __('Show Report this ad button'), array('class'=>'control-label', 'for'=>$forms['sharing']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['report']['key'], 1, (bool) $forms['report']['value'], array('id' => $forms['report']['key'].'1'))?>
                                <?=Form::label($forms['report']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['report']['key'], 0, ! (bool) $forms['report']['value'], array('id' => $forms['report']['key'].'0'))?>
                                <?=Form::label($forms['report']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("You can choose if you wish to display Report this ad button at each advertisement.")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['related']['key'], __('Related ads'), array('class'=>'control-label', 'for'=>$forms['related']['key']))?>
                            <?=FORM::input($forms['related']['key'], $forms['related']['value'], array(
                                'placeholder' => $forms['related']['value'],
                                'class' => 'tips form-control ',
                                'id' => $forms['related']['key'],
                                'type' => 'number',
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                            ))?>
                            <span class="help-block">
                                <?=__("You can choose if you wish to display random related ads at each advertisement")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['free']['key'], __('Show Free tag'), array('class'=>'control-label', 'for'=>$forms['free']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['free']['key'], 1, (bool) $forms['free']['value'], array('id' => $forms['free']['key'].'1'))?>
                                <?=Form::label($forms['free']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['free']['key'], 0, ! (bool) $forms['free']['value'], array('id' => $forms['free']['key'].'0'))?>
                                <?=Form::label($forms['free']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("You can choose if you wish to display free tag when price is equal to zero.")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['fbcomments']['key'], __('Facebook comments'), array('class'=>'control-label', 'for'=>$forms['fbcomments']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/add-facebook-comments/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <?=FORM::input($forms['fbcomments']['key'], $forms['fbcomments']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['fbcomments']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("You need to write your Facebook APP ID to enable the service.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['disqus']['key'], __('Disqus'), array('class'=>'control-label', 'for'=>$forms['disqus']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/how-to-activate-comments-with-disqus/">
                                <i class="fa fa-question-circle"></i>
                            </a>
                            <?=FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['disqus']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("You need to write your disqus ID to enable the service.")?>
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?=FORM::label($forms['logbee']['key'], "Logbee", array('class'=>'control-label', 'for'=>$forms['logbee']['key']))?>
                            <a target="_blank" href="http://www.logbee.com/">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['logbee']['key'], 1, (bool) $forms['logbee']['value'], array('id' => $forms['logbee']['key'].'1'))?>
                                <?=Form::label($forms['logbee']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['logbee']['key'], 0, ! (bool) $forms['logbee']['value'], array('id' => $forms['logbee']['key'].'0'))?>
                                <?=Form::label($forms['logbee']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Integrates your site with Logbee")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['rich_snippets']['key'], __('Rich Snippets'), array('class'=>'control-label', 'for'=>$forms['rich_snippets']['key']))?>
                            <a target="_blank" href="https://developers.google.com/structured-data/rich-snippets/products">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['rich_snippets']['key'], 1, (bool) $forms['rich_snippets']['value'], array('id' => $forms['rich_snippets']['key'].'1'))?>
                                <?=Form::label($forms['rich_snippets']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['rich_snippets']['key'], 0, ! (bool) $forms['rich_snippets']['value'], array('id' => $forms['rich_snippets']['key'].'0'))?>
                                <?=Form::label($forms['rich_snippets']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enables rich snippets for products")?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
                <div id="tabSettingsMap" class="tab-pane fade">
                    <h4><?=__('Google Maps Settings')?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-configure-Google-Map-Settings/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['gm_api_key']['key'], __('Google Maps API Key'), array('class'=>'control-label', 'for'=>$forms['gm_api_key']['key']))?>
                            <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <?=FORM::input($forms['gm_api_key']['key'], $forms['gm_api_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['gm_api_key']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Google maps API Key")?>
                            </span>
                        </div>
                        <?$map_styles = array(
                                __('None')                => '',
                                'Subtle Grayscale'        => '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]',
                                'Shades of Grey'          => '[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]',
                                'Blue water'              => '[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]',
                                'Pale Dawn'               => '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}]',
                                'Blue Essence'            => '[{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]}]',
                                'Apple Maps-esque'        => '[{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]',
                                'Ultra Light with Labels' => '[{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}]',
                                'Midnight Commander'      => '[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#146474"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#021019"}]}]',
                                'Light Monochrome'        => '[{"featureType":"administrative.locality","elementType":"all","stylers":[{"hue":"#2c2e33"},{"saturation":7},{"lightness":19},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":31},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"hue":"#bbc0c4"},{"saturation":-93},{"lightness":-2},{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#e9ebed"},{"saturation":-90},{"lightness":-8},{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":10},{"lightness":69},{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#e9ebed"},{"saturation":-78},{"lightness":67},{"visibility":"simplified"}]}]',
                                'Paper'                   => '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#0066ff"},{"saturation":74},{"lightness":100}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"off"},{"weight":0.6},{"saturation":-85},{"lightness":61}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#5f94ff"},{"lightness":26},{"gamma":5.86}]}]',
                                'Retro'                   => '[{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"visibility":"off"}]},{"featureType":"road.local","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"color":"#84afa3"},{"lightness":52}]},{"stylers":[{"saturation":-17},{"gamma":0.36}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#3f518c"}]}]',
                                'Flat Map'                => '[{"featureType":"all","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f3f4f4"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"weight":0.9},{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#83cead"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"on"},{"color":"#fee379"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"on"},{"color":"#fee379"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#7fc8ed"}]}]',
                                'Cool Grey'               => '[{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"stylers":[{"hue":"#00aaff"},{"saturation":-100},{"gamma":2.15},{"lightness":12}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness": 57 } ] } ]',
                                'Black and White'         => '[{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"weight":1}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"weight":0.8}]},{"featureType":"landscape","stylers":[{"color":"#ffffff"}]},{"featureType":"water","stylers":[{"visibility":"off"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"elementType":"labels","stylers":[{"visibility":"off"}]},{"elementType":"labels.text","stylers":[{"visibility":"on"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#000000"}]},{"elementType":"labels.icon","stylers":[{"visibility":"on" } ] } ]',
                                'light dream'             => '[{"featureType":"landscape","stylers":[{"hue":"#FFBB00"},{"saturation":43.400000000000006},{"lightness":37.599999999999994},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#FFC200"},{"saturation":-61.8},{"lightness":45.599999999999994},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":51.19999999999999},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":52},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#0078FF"},{"saturation":-13.200000000000003},{"lightness":2.4000000000000057},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#00FF6A"},{"saturation":-1.0989010989011234},{"lightness":11.200000000000017},{"gamma":1}]}]',
                                'Greyscale'               => '[{"featureType":"all","elementType":"all","stylers":[{"saturation":-100},{"gamma":0.5}]}]',
                                'becomeadinosaur'         => '[{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]',
                                'Neutral Blue'            => '[{"featureType": "water","elementType": "geometry","stylers": [{ "color": "#193341" }]},{"featureType": "landscape","elementType": "geometry","stylers": [{ "color": "#2c5a71" }]},{"featureType": "road","elementType": "geometry","stylers": [{ "color": "#29768a" },{ "lightness": -37 }]},{"featureType": "poi","elementType": "geometry","stylers": [{ "color": "#406d80" }]},{"featureType": "transit","elementType": "geometry","stylers": [{ "color": "#406d80" }]},{"elementType": "labels.text.stroke","stylers": [{ "visibility": "on" },{ "color": "#3e606f" },{ "weight": 2 },{ "gamma": 0.84 }]},{"elementType": "labels.text.fill","stylers": [{ "color": "#ffffff" }]},{"featureType": "administrative","elementType": "geometry","stylers": [{ "weight": 0.6 },{ "color": "#1a3541" }]},{"elementType": "labels.icon","stylers": [{ "visibility": "off" }]},{"featureType": "poi.park","elementType": "geometry","stylers": [{ "color": "#2c5a71" }]}]',
                                'Gowalla'                 => '[{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#f49935"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#fad959"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#a1cdfc"},{"saturation":30},{"lightness":49}]}]',
                                'MapBox'                  => '[{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]',
                                'Shift Worker'            => '[{"stylers":[{"saturation":-100},{"gamma":1}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"on"},{"saturation":50},{"gamma":0},{"hue":"#50a5d1"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"color":"#333333"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"weight":0.5},{"color":"#333333"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"gamma":1},{"saturation":50}]}]',
                                'RouteXL'                 => '[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":40}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-10},{"lightness":30}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":10}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":60}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]}]',
                                'Avocado World'           => '[{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#aee2e0"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#abce83"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#769E72"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#7B8758"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#EBF4A4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#8dab68"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#5B5B3F"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ABCE83"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#A4C67D"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#9BBF72"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#EBF4A4"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#87ae79"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#7f2200"},{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"visibility":"on"},{"weight":4.1}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#495421"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"off"}]}]',
                                'Subtle Greyscale Map'    => '[{"featureType":"poi","elementType":"all","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#000000"},{"saturation":0},{"lightness":-100},{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"transit","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":0},{"lightness":-100},{"visibility":"off"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#bbbbbb"},{"saturation":-100},{"lightness":26},{"visibility":"on"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"hue":"#dddddd"},{"saturation":-100},{"lightness":-3},{"visibility":"on"}]}]',
                            )
                        ?>

                        <div class="form-group">
                            <?=FORM::label($forms['map_style']['key'], __("Google map style"), array('class'=>'control-label', 'for'=>$forms['map_style']['key']))?>
                            <?=FORM::select($forms['map_style']['key'], array_flip($map_styles), $forms['map_style']['value'], array(
                                'placeholder' => "http://foo.com/",
                                'class' => 'form-control',
                                'id' => $forms['map_style']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Custom Google Maps styling")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['map_zoom']['key'], __('Google map zoom level'), array('class'=>'control-label', 'for'=>$forms['map_zoom']['key']))?>
                            <?=FORM::input($forms['map_zoom']['key'], $forms['map_zoom']['value'], array(
                                'placeholder' => "16",
                                'class' => 'form-control',
                                'id' => $forms['map_zoom']['key'],
                                'type' => 'number',
                                'data-rule-digits' => 'true',
                            ))?>
                            <span class="help-block">
                                <?=__("Google map default zoom level")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['center_lat']['key'], __('Map latitude coordinates'), array('class'=>'control-label', 'for'=>$forms['center_lat']['key']))?>
                            <?=FORM::input($forms['center_lat']['key'], $forms['center_lat']['value'], array(
                                'placeholder' => "40",
                                'class' => 'tips form-control',
                                'id' => $forms['center_lat']['key'],
                                'data-rule-number' => 'true',
                            ))?>
                            <span class="help-block">
                                <?=__("Google map default latitude coordinates")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['center_lon']['key'], __('Map longitude coordinates'), array('class'=>'control-label', 'for'=>$forms['center_lon']['key']))?>
                            <?=FORM::input($forms['center_lon']['key'], $forms['center_lon']['value'], array(
                                'placeholder' => "3",
                                'class' => 'tips form-control',
                                'id' => $forms['center_lon']['key'],
                                'data-rule-number' => 'true',
                            ))?>
                            <span class="help-block">
                                <?=__("Google map default longitude coordinates")?>
                            </span>
                        </div>

                        <?if (Core::config('general.auto_locate') == 1) :?>
                            <div class="form-group">
                                <?=FORM::label($forms['auto_locate_distance']['key'], __('Auto locate distance'), array('class'=>'control-label', 'for'=>$forms['auto_locate_distance']['key']))?>
                                <div class="input-group">
                                    <?=FORM::input($forms['auto_locate_distance']['key'], $forms['auto_locate_distance']['value'], array(
                                        'placeholder' => "100",
                                        'class' => 'tips form-control',
                                        'id' => $forms['auto_locate_distance']['key'],
                                        'data-rule-number' => 'true',
                                    ))?>
                                    <div class="input-group-addon"><?=Core::config('general.measurement') == 'metric' ? 'Kilometers' : 'Miles'?></div>
                                </div>
                                <span class="help-block">
                                    <?=__("Sets maximum distance of closest suggested locations to the visitor.")?>
                                </span>
                            </div>
                        <?else :?>
                            <?= FORM::hidden($forms['auto_locate_distance']['key'], $forms['auto_locate_distance']['value']);?>
                        <?endif?>
                    </div>
                    
                    <hr>
                    
                    <h4><?=__('Google Maps on Homepage')?>
                        <a target="_blank" href="https://docs.yclas.com//">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>

                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['homepage_map']['key'], __("Homepage Map"), array('class'=>'control-label', 'for'=>$forms['homepage_map']['key']))?>
                            <?=FORM::select($forms['homepage_map']['key'], array('None', 'Top', 'Bottom'), $forms['homepage_map']['value'], array(
                                'placeholder' => "None",
                                'class' => 'form-control',
                                'id' => $forms['homepage_map']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Select where to show the map in the homepage.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['homepage_map_height']['key'], __('Homepage Map height'), array('class'=>'control-label', 'for'=>$forms['homepage_map_height']['key']))?>
                            <?=FORM::input($forms['homepage_map_height']['key'], $forms['homepage_map_height']['value'], array(
                                'placeholder' => "400",
                                'class' => 'form-control',
                                'id' => $forms['homepage_map_height']['key'],
                                'type' => 'number',
                                'data-rule-digits' => 'true',
                            ))?>
                            <span class="help-block">
                                <?=__("Enter the height of the homepage map.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['homepage_map_allowfullscreen']['key'], "Homepage Map full screen option", array('class'=>'control-label', 'for'=>$forms['homepage_map_allowfullscreen']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['homepage_map_allowfullscreen']['key'], 1, (bool) $forms['homepage_map_allowfullscreen']['value'], array('id' => $forms['homepage_map_allowfullscreen']['key'].'1'))?>
                                <?=Form::label($forms['homepage_map_allowfullscreen']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['homepage_map_allowfullscreen']['key'], 0, ! (bool) $forms['homepage_map_allowfullscreen']['value'], array('id' => $forms['homepage_map_allowfullscreen']['key'].'0'))?>
                                <?=Form::label($forms['homepage_map_allowfullscreen']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enable full screen option.")?>
                            </span>
                        </div>
                    </div>
                    
                    <hr>

                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
                <div id="tabSettingsReview" class="tab-pane fade">
                    <h4><?=__('Reviews Configuration')?>
                        <a target="_blank" href="https://docs.yclas.com/review-system-works/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>
                    <hr>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['reviews']['key'], __("Enable reviews"), array('class'=>'control-label', 'for'=>$forms['reviews']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['reviews']['key'], 1, (bool) $forms['reviews']['value'], array('id' => $forms['reviews']['key'].'1'))?>
                                <?=Form::label($forms['reviews']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['reviews']['key'], 0, ! (bool) $forms['reviews']['value'], array('id' => $forms['reviews']['key'].'0'))?>
                                <?=Form::label($forms['reviews']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enables reviews for ads and the users.")?>
                            </span>
                        </div>

                        <div class="form-group">
                            <?=FORM::label($forms['reviews_paid']['key'], __("Only for paid transactions"), array('class'=>'control-label', 'for'=>$forms['reviews_paid']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['reviews_paid']['key'], 1, (bool) $forms['reviews_paid']['value'], array('id' => $forms['reviews_paid']['key'].'1'))?>
                                <?=Form::label($forms['reviews_paid']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['reviews_paid']['key'], 0, ! (bool) $forms['reviews_paid']['value'], array('id' => $forms['reviews_paid']['key'].'0'))?>
                                <?=Form::label($forms['reviews_paid']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("You need to enable paypal link to allow only reviews on purchases.")?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
                <div id="tabSettingsDropbox" class="tab-pane fade">
                    <h4><?=__('Dropbox Configuration')?></h4>
                    <hr>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['dropbox_app_key']['key'], __('Dropbox App Key'), array('class'=>'control-label', 'for'=>$forms['dropbox_app_key']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/sell-digital-goods/">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <?=FORM::input($forms['dropbox_app_key']['key'], $forms['dropbox_app_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['dropbox_app_key']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Dropbox App Key")?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>


                <div id="tabSettingsPicker" class="tab-pane fade">
                    <h4><?=__('Google Picker Configuration')?></h4>
                    <hr>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['picker_api_key']['key'], __('Google Picker API Key'), array('class'=>'control-label', 'for'=>$forms['picker_api_key']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/sell-digital-goods/">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <?=FORM::input($forms['picker_api_key']['key'], $forms['picker_api_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['picker_api_key']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Google Picker API Key")?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <?=FORM::label($forms['picker_client_id']['key'], __('Google Picker Client ID'), array('class'=>'control-label', 'for'=>$forms['picker_client_id']['key']))?>
                            <a target="_blank" href="https://docs.yclas.com/sell-digital-goods/">
                                <i class="fa fa-external-link-square"></i>
                            </a>
                            <?=FORM::input($forms['picker_client_id']['key'], $forms['picker_client_id']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['picker_client_id']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Google Picker Client ID")?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>

                <div id="tabSettingsSocial" class="tab-pane fade">
                    <h4><?=__('Social Configuration')?>
                        <a target="_blank" href="https://docs.yclas.com/auto-post-social-media/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </h4>

                    <hr>

                    <div class="form-group">
                        <?=FORM::label($forms['social_post_only_featured']['key'], __('Only Featured Ads'), array('class'=>'control-label', 'for'=>$forms['social_post_only_featured']['key']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['social_post_only_featured']['key'], 1, (bool) $forms['social_post_only_featured']['value'], array('id' => $forms['social_post_only_featured']['key'].'1'))?>
                            <?=Form::label($forms['social_post_only_featured']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['social_post_only_featured']['key'], 0, ! (bool) $forms['social_post_only_featured']['value'], array('id' => $forms['social_post_only_featured']['key'].'0'))?>
                            <?=Form::label($forms['social_post_only_featured']['key'].'0', __('Disabled'))?>
                        </div>
                        <span class="help-block">
                            <?=__("Enable to post only featured ads.")?>
                        </span>
                    </div>

                    <hr>

                    <div>
                        <h4><?=__('Facebook')?></h4>
                        <hr>
                        <div class="form-group">
                            <?=FORM::label($forms['facebook']['key'], __('Auto Post'), array('class'=>'control-label', 'for'=>$forms['facebook']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['facebook']['key'], 1, (bool) $forms['facebook']['value'], array('id' => $forms['facebook']['key'].'1'))?>
                                <?=Form::label($forms['facebook']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['facebook']['key'], 0, ! (bool) $forms['facebook']['value'], array('id' => $forms['facebook']['key'].'0'))?>
                                <?=Form::label($forms['facebook']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enable to post new ads on facebook automatically.")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['facebook_app_id']['key'], __('Facebook App Id'), array('class'=>'control-label', 'for'=>$forms['facebook_app_id']['key']))?>
                            <?=FORM::input($forms['facebook_app_id']['key'], $forms['facebook_app_id']['value'], array(
                                'placeholder' => "Facebook App Id",
                                'class' => 'tips form-control',
                                'id' => $forms['facebook_app_id']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Facebook App Id")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['facebook_app_secret']['key'], __('Facebook App Secret'), array('class'=>'control-label', 'for'=>$forms['facebook_app_secret']['key']))?>
                            <?=FORM::input($forms['facebook_app_secret']['key'], $forms['facebook_app_secret']['value'], array(
                                'placeholder' => "Facebook App Secret",
                                'class' => 'tips form-control',
                                'id' => $forms['facebook_app_secret']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Facebook App Secret")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['facebook_access_token']['key'], __('Facebook Access Token'), array('class'=>'control-label', 'for'=>$forms['facebook_access_token']['key']))?>
                            <?=FORM::input($forms['facebook_access_token']['key'], $forms['facebook_access_token']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['facebook_access_token']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Facebook Access Token")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['facebook_id']['key'], __('Facebook Id'), array('class'=>'control-label', 'for'=>$forms['facebook_id']['key']))?>
                            <?=FORM::input($forms['facebook_id']['key'], $forms['facebook_id']['value'], array(
                                'placeholder' => "Facebook Id",
                                'class' => 'tips form-control',
                                'id' => $forms['facebook_id']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Facebook Id")?>
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <h4><?=__('Twitter')?></h4>
                        <hr>
                        <div class="form-group">
                            <?=FORM::label($forms['twitter']['key'], __('Auto Post'), array('class'=>'control-label', 'for'=>$forms['twitter']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['twitter']['key'], 1, (bool) $forms['twitter']['value'], array('id' => $forms['twitter']['key'].'1'))?>
                                <?=Form::label($forms['twitter']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['twitter']['key'], 0, ! (bool) $forms['twitter']['value'], array('id' => $forms['twitter']['key'].'0'))?>
                                <?=Form::label($forms['twitter']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enable to post new ads on twitter automatically.")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['twitter_consumer_key']['key'], __('Consumer Key'), array('class'=>'control-label', 'for'=>$forms['twitter_consumer_key']['key']))?>
                            <?=FORM::input($forms['twitter_consumer_key']['key'], $forms['twitter_consumer_key']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['twitter_consumer_key']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Twitter Consumer Key")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['twitter_consumer_secret']['key'], __('Consumer Secret'), array('class'=>'control-label', 'for'=>$forms['twitter_consumer_secret']['key']))?>
                            <?=FORM::input($forms['twitter_consumer_secret']['key'], $forms['twitter_consumer_secret']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['twitter_consumer_secret']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Twitter Consumer Secret")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['access_token']['key'], __('Access Token'), array('class'=>'control-label', 'for'=>$forms['access_token']['key']))?>
                            <?=FORM::input($forms['access_token']['key'], $forms['access_token']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['access_token']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Access Token")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['access_token_secret']['key'], __('Access Token Secret'), array('class'=>'control-label', 'for'=>$forms['access_token_secret']['key']))?>
                            <?=FORM::input($forms['access_token_secret']['key'], $forms['access_token_secret']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['access_token_secret']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Access Token Secret")?>
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <h4><?=__('Instagram')?></h4>
                        <hr>
                        <div class="form-group">
                            <?=FORM::label($forms['instagram']['key'], __('Auto Post'), array('class'=>'control-label', 'for'=>$forms['instagram']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['instagram']['key'], 1, (bool) $forms['instagram']['value'], array('id' => $forms['instagram']['key'].'1'))?>
                                <?=Form::label($forms['instagram']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['instagram']['key'], 0, ! (bool) $forms['instagram']['value'], array('id' => $forms['instagram']['key'].'0'))?>
                                <?=Form::label($forms['instagram']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enable to post new ads on instagram automatically.")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['instagram_username']['key'], __('Instagram Username'), array('class'=>'control-label', 'for'=>$forms['instagram_username']['key']))?>
                            <?=FORM::input($forms['instagram_username']['key'], $forms['instagram_username']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['instagram_username']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Instagram Username")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['instagram_password']['key'], __('Instagram Password'), array('class'=>'control-label', 'for'=>$forms['instagram_password']['key']))?>
                            <?=FORM::input($forms['instagram_password']['key'], $forms['instagram_password']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'type' => 'password',
                                'id' => $forms['instagram_password']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Instagram Password")?>
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <h4><?=__('Pinterest')?></h4>
                        <hr>
                        <div class="form-group">
                            <?=FORM::label($forms['pinterest']['key'], __('Auto Post'), array('class'=>'control-label', 'for'=>$forms['pinterest']['key']))?>
                            <div class="radio radio-primary">
                                <?=Form::radio($forms['pinterest']['key'], 1, (bool) $forms['pinterest']['value'], array('id' => $forms['pinterest']['key'].'1'))?>
                                <?=Form::label($forms['pinterest']['key'].'1', __('Enabled'))?>
                                <?=Form::radio($forms['pinterest']['key'], 0, ! (bool) $forms['pinterest']['value'], array('id' => $forms['pinterest']['key'].'0'))?>
                                <?=Form::label($forms['pinterest']['key'].'0', __('Disabled'))?>
                            </div>
                            <span class="help-block">
                                <?=__("Enable to post new ads on pinterest automatically.")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['pinterest_app_id']['key'], __('App ID'), array('class'=>'control-label', 'for'=>$forms['pinterest_app_id']['key']))?>
                            <?=FORM::input($forms['pinterest_app_id']['key'], $forms['pinterest_app_id']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['pinterest_app_id']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Pinterest App ID")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['pinterest_app_secret']['key'], __('App secret'), array('class'=>'control-label', 'for'=>$forms['pinterest_app_secret']['key']))?>
                            <?=FORM::input($forms['pinterest_app_secret']['key'], $forms['pinterest_app_secret']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['pinterest_app_secret']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Pinterest App secret")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['pinterest_access_token']['key'], __('Pinterest Access token'), array('class'=>'control-label', 'for'=>$forms['pinterest_access_token']['key']))?>
                            <?=FORM::input($forms['pinterest_access_token']['key'], $forms['pinterest_access_token']['value'], array(
                                'placeholder' => "",
                                'class' => 'tips form-control',
                                'id' => $forms['pinterest_access_token']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Pinterest Access token")?>
                            </span>
                        </div>
                        <div class="form-group">
                            <?=FORM::label($forms['pinterest_board']['key'], __('Board'), array('class'=>'control-label', 'for'=>$forms['pinterest_board']['key']))?>
                            <?=FORM::input($forms['pinterest_board']['key'], $forms['pinterest_board']['value'], array(
                                'placeholder' => "username/board",
                                'class' => 'tips form-control',
                                'id' => $forms['pinterest_board']['key'],
                            ))?>
                            <span class="help-block">
                                <?=__("Board")?>
                            </span>
                        </div>
                    </div>

                    <hr>
                    <p>
                        <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                    </p>
                </div>
            </div>

        <?= FORM::close()?>
    </div>
</div>
