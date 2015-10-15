<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="page-header">
    <h1><?=__('Advertisement Configuration')?></h1>
    <p><?=__('List of optional fields. To activate/deactivate select "ON/OFF" in desired field.')?></p>
</div>

<div class="row">
    <div class="col-md-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')), array('class'=>'form-horizontal config', 'enctype'=>'multipart/form-data'))?>
        <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/how-to-change-settings-for-ads/'>".__("Listing Options")."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <?foreach ($config as $c):?>
                        <?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
                    <?endforeach?>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['advertisements_per_page']['key'], __('Advertisements per page'), array('class'=>'control-label col-sm-4', 'for'=>$forms['advertisements_per_page']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['advertisements_per_page']['key'], $forms['advertisements_per_page']['value'], array(
                            'placeholder' => "20", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['advertisements_per_page']['key'], 
                            'data-content'=> __("This is to control how many advertisements are being displayed per page. Insert an integer value, as a number limit."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Number of ads per page"),
                            'data-rule-required'=>'true',
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['feed_elements']['key'], __('Advertisements in RSS'), array('class'=>'control-label col-sm-4', 'for'=>$forms['feed_elements']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['feed_elements']['key'], $forms['feed_elements']['value'], array(
                            'placeholder' => "20", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['feed_elements']['key'], 
                            'data-original-title'=> __("Number of Ads"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("How many ads are going to appear in the RSS of your site."),
                            'data-rule-required'=>'true',
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['map_elements']['key'], __('Advertisements in Map'), array('class'=>'control-label col-sm-4', 'for'=>$forms['map_elements']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['map_elements']['key'], $forms['map_elements']['value'], array(
                            'placeholder' => "20", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['map_elements']['key'], 
                            'data-original-title'=> __("Number of Ads"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("How many ads are going to appear in the map of your site."),
                            'data-rule-required'=>'true',
                            'data-rule-digits' => 'true',
                       ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['sort_by']['key'], __('Sort by in listing'), array('class'=>'control-label col-sm-4', 'for'=>$forms['sort_by']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['sort_by']['key'], array('title-asc'=>"Name (A-Z)",
                                                                             'title-desc'=>"Name (Z-A)",
                                                                             'price-asc'=>"Price (Low)",
                                                                             'price-desc'=>"Price (High)",
                                                                             'featured'=>"Featured",
                                                                             'rating'=>"Rating",
                                                                             'favorited'=>"Favorited",
                                                                             'published-desc'=>"Newest",
                                                                             'published-asc'=>"Oldest"), 
                            $forms['sort_by']['value'], array(
                            'placeholder' => $forms['sort_by']['value'], 
                            'class' => 'tips form-control input-sm ', 
                            'id' => $forms['sort_by']['key'],
                            'data-content'=> __("Sort by in listing"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Sort by in listing"), 
                            ))?> 
                        </div>
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
                        <?= FORM::label($forms['ads_in_home']['key'], "<a target='_blank' href='http://docs.yclas.com/manage-ads-slider/'>".__('Advertisements in home')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['ads_in_home']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['ads_in_home']['key'], $ads_in_home
                            , $forms['ads_in_home']['value'], array(
                            'placeholder' => $forms['ads_in_home']['value'], 
                            'class' => 'tips form-control ', 
                            'id' => $forms['ads_in_home']['key'],
                            'data-content'=> __("You can choose what ads you want to display in home."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Advertisements in home"), 
                            ))?> 
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/how-to-change-settings-for-ads/'>".__("Publish Options")."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['login_to_post']['key'], "<a target='_blank' href='http://docs.yclas.com/force-registration-posting-new-ad/'>".__('Require login to post')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['login_to_post']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['login_to_post']['key'], 0);?>
                                <?= FORM::checkbox($forms['login_to_post']['key'], 1, (bool) $forms['login_to_post']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['login_to_post']['key'], 
                                'data-original-title'=> __("Require login to post"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Require only the logged in users to post."),
                                ))?>
                                <?= FORM::label($forms['login_to_post']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['login_to_post']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['only_admin_post']['key'], __('Only administrators can publish'), array('class'=>'control-label col-sm-4', 'for'=>$forms['only_admin_post']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['only_admin_post']['key'], 0);?>
                                <?= FORM::checkbox($forms['only_admin_post']['key'], 1, (bool) $forms['only_admin_post']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['only_admin_post']['key'], 
                                'data-original-title'=> __("Only administrators can publish"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Only administrators can publish"),
                                ))?>
                                <?= FORM::label($forms['only_admin_post']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['only_admin_post']['key']))?>
                            </div>
                        </div>
                    </div>  
                    
                    <div class="form-group">
                        <?= FORM::label($forms['expire_date']['key'], __('Ad expiration date'), array('class'=>'control-label col-sm-4', 'for'=>$forms['expire_date']['key']))?>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <?= FORM::input($forms['expire_date']['key'], $forms['expire_date']['value'], array(
                                'placeholder' => $forms['expire_date']['value'], 
                                'class' => 'tips form-control col-sm-3', 
                                'id' => $forms['expire_date']['key'], 
                                'data-original-title'=> __("Expire days"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__('After how many days an Ad will expire. 0 for never'),
                                'data-rule-required'=>'true',
                                'data-rule-digits' => 'true',
                                ));?>
                                <span class="input-group-addon"><?=__("Days")?></span>
                            </div> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['parent_category']['key'], __('Parent category'), array('class'=>'control-label col-sm-4', 'for'=>$forms['parent_category']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['parent_category']['key'], 0);?>
                                <?= FORM::checkbox($forms['parent_category']['key'], 1, (bool) $forms['parent_category']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['parent_category']['key'], 
                                'data-original-title'=> __("parent_category field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Use parent categories"),
                                ))?>
                                <?= FORM::label($forms['parent_category']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['parent_category']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['map_pub_new']['key'], __('Google Maps in Publish New'), array('class'=>'control-label col-sm-4', 'for'=>$forms['map_pub_new']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['map_pub_new']['key'], 0);?>
                                <?= FORM::checkbox($forms['map_pub_new']['key'], 1, (bool) $forms['map_pub_new']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['map_pub_new']['key'], 
                                'data-original-title'=> __("Google Maps"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the google maps in the Publish new form."),
                                ))?>
                                <?= FORM::label($forms['map_pub_new']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['map_pub_new']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['description_bbcode']['key'], __('BBCODE editor on description field'), array('class'=>'control-label col-sm-4', 'for'=>$forms['description_bbcode']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['description_bbcode']['key'], 0);?>
                                <?= FORM::checkbox($forms['description_bbcode']['key'], 1, (bool) $forms['description_bbcode']['value'], array(
                                'placeholder' => __('BBCODE editor on description field'), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['description_bbcode']['key'], 
                                'data-original-title'=> __("BBCODE editor on description field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("BBCODE editor appears in description field."),
                                ))?>
                                <?= FORM::label($forms['description_bbcode']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['description_bbcode']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['captcha']['key'], __('Captcha'), array('class'=>'control-label col-sm-4', 'for'=>$forms['captcha']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['captcha']['key'], 0);?>
                                <?= FORM::checkbox($forms['captcha']['key'], 1, (bool) $forms['captcha']['value'], array(
                                'placeholder' => "http://foo.com/", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['captcha']['key'], 
                                'data-original-title'=> __("Enables Captcha"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Captcha appears in the form."),
                                ))?>
                                <?= FORM::label($forms['captcha']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['captcha']['key']))?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['leave_alert']['key'], __('Leave alert before submitting form'), array('class'=>'control-label col-sm-4', 'for'=>$forms['leave_alert']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['leave_alert']['key'], 0);?>
                                <?= FORM::checkbox($forms['leave_alert']['key'], 1, (bool) $forms['leave_alert']['value'], array(
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['leave_alert']['key'], 
                                'data-original-title'=> __("Enables leave alert before submitting publish new form"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Leave alert before submitting publish new form"),
                                ))?>
                                <?= FORM::label($forms['leave_alert']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['leave_alert']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <?$pages = array(''=>__('Deactivated'))?>
                    <?foreach (Model_Content::get_pages() as $key => $value) {
                        $pages[$value->seotitle] = $value->title;
                    }?>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['tos']['key'], "<a target='_blank' href='http://docs.yclas.com/how_to_add_pages/'>".__('Terms of Service')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['tos']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['tos']['key'], $pages, $forms['tos']['value'], array(
                            'placeholder' => "http://foo.com/", 
                            'class' => 'tips form-control', 
                            'id' => $forms['tos']['key'], 
                            'data-content'=> __("If you choose to use terms of service, you can select activate. And to edit content, select link 'Content' on your admin panel sidebar. Find page named 'Terms of service' click 'Edit'. In section 'Description' add content that suits you."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Terms of Service"),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['thanks_page']['key'], __('Thanks page'), array('class'=>'control-label col-sm-4', 'for'=>$forms['thanks_page']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['thanks_page']['key'], $pages, $forms['thanks_page']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['tos']['key'], 
                            'data-content'=> __('Content that will be displayed to the user after he publishes an ad'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Thanks page"),
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['banned_words']['key'], __('Banned words'), array('class'=>'control-label col-sm-4', 'for'=>$forms['banned_words']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['banned_words']['key'], $forms['banned_words']['value'], array(
                            'placeholder' => __('For banned word push enter.'), 
                            'class' => 'tips form-control', 
                            'id' => $forms['banned_words']['key'], 
                            'data-original-title'=> __("Banned words are separated with coma (,)"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-role'=>'tagsinput',
                            'data-content'=>__("You need to write your banned words to enable the service."),
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['banned_words_replacement']['key'], __('Banned words replacement'), array('class'=>'control-label col-sm-4', 'for'=>$forms['banned_words_replacement']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['banned_words_replacement']['key'], $forms['banned_words_replacement']['value'], array(
                            'placeholder' => "xxx", 
                            'class' => 'tips form-control', 
                            'id' => $forms['banned_words_replacement']['key'], 
                            'data-original-title'=> __("Replacement of a banned word"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Banned word replacement replaces selected array with the string you provided."),
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/how-to-manage-advertisement-fields/'>".__("Advertisement Fields")."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['address']['key'], __('Address'), array('class'=>'control-label col-sm-4', 'for'=>$forms['address']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['address']['key'], 0);?>
                                <?= FORM::checkbox($forms['address']['key'], 1, (bool) $forms['address']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['address']['key'], 
                                'data-original-title'=> __("Address field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the field Address in the Ad form."),
                                ))?>
                                <?= FORM::label($forms['address']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['address']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['phone']['key'], __('Phone'), array('class'=>'control-label col-sm-4', 'for'=>$forms['phone']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['phone']['key'], 0);?>
                                <?= FORM::checkbox($forms['phone']['key'], 1, (bool) $forms['phone']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['phone']['key'], 
                                'data-original-title'=> __("Phone field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the field Phone in the Ad form."),
                                ))?>
                                <?= FORM::label($forms['phone']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['phone']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['website']['key'], __('Website'), array('class'=>'control-label col-sm-4', 'for'=>$forms['website']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['website']['key'], 0);?>
                                <?= FORM::checkbox($forms['website']['key'], 1, (bool) $forms['website']['value'], array(
                                'placeholder' => "http://foo.com/", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['website']['key'], 
                                'data-original-title'=> __("Website field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the field Website in the Ad form."),
                                ))?>
                                <?= FORM::label($forms['website']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['website']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['location']['key'], __('Location'), array('class'=>'control-label col-sm-4', 'for'=>$forms['location']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['location']['key'], 0);?>
                                <?= FORM::checkbox($forms['location']['key'], 1, (bool) $forms['location']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['location']['key'], 
                                'data-original-title'=> __("Displays location select"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the Select Location in the Ad form."),
                                ))?>
                                <?= FORM::label($forms['location']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['location']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['price']['key'], __('Price'), array('class'=>'control-label col-sm-4', 'for'=>$forms['price']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['price']['key'], 0);?>
                                <?= FORM::checkbox($forms['price']['key'], 1, (bool) $forms['price']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['price']['key'], 
                                'data-original-title'=> __("Price field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the field Price in the Ad form."),
                                ))?>
                                <?= FORM::label($forms['price']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['price']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['upload_file']['key'], __('Upload file'), array('class'=>'control-label col-sm-4', 'for'=>$forms['upload_file']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['upload_file']['key'], 0);?>
                                <?= FORM::checkbox($forms['upload_file']['key'], 1, (bool) $forms['upload_file']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['upload_file']['key'], 
                                ))?>
                                <?= FORM::label($forms['upload_file']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['upload_file']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['num_images']['key'], __('Number of images'), array('class'=>'control-label col-sm-4', 'for'=>$forms['num_images']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['num_images']['key'], $forms['num_images']['value'], array(
                            'placeholder' => "4", 
                            'class' => 'tips form-control', 
                            'id' => $forms['num_images']['key'], 
                            'data-original-title'=> __("Number of images"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Number of images displayed"),
                            'data-rule-required'=>'true',
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
            
        <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/how-to-configure-advertisement-display-options/'>".__("Advertisement Display Options")."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['contact']['key'], __('Contact form'), array('class'=>'control-label col-sm-4', 'for'=>$forms['contact']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['contact']['key'], 0);?>
                                <?= FORM::checkbox($forms['contact']['key'], 1, (bool) $forms['contact']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['contact']['key'], 
                                'data-original-title'=> __("Enables Contact Form"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Contact form appears in the ad."),
                                ))?>
                                <?= FORM::label($forms['contact']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['contact']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['login_to_contact']['key'], __('Require login to contact'), array('class'=>'control-label col-sm-4', 'for'=>$forms['login_to_contact']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['login_to_contact']['key'], 0);?>
                                <?= FORM::checkbox($forms['login_to_contact']['key'], 1, (bool) $forms['login_to_contact']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['login_to_contact']['key'], 
                                'data-original-title'=> __("Require login to contact"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Require only the logged in users to contact."),
                                ))?>
                                <?= FORM::label($forms['login_to_contact']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['login_to_contact']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['qr_code']['key'], __('Show QR code'), array('class'=>'control-label col-sm-4', 'for'=>$forms['qr_code']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['qr_code']['key'], 0);?>
                                <?= FORM::checkbox($forms['qr_code']['key'], 1, (bool) $forms['qr_code']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['qr_code']['key'], 
                                'data-original-title'=> __("Show QR code"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Show QR code"),
                                ))?>
                                <?= FORM::label($forms['qr_code']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['qr_code']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['map']['key'], __('Google Maps in Ad'), array('class'=>'control-label col-sm-4', 'for'=>$forms['map']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['map']['key'], 0);?>
                                <?= FORM::checkbox($forms['map']['key'], 1, (bool) $forms['map']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['map']['key'], 
                                'data-original-title'=> __("Google Maps"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the google maps in the Ad."),
                                ))?>
                                <?= FORM::label($forms['map']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['map']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['count_visits']['key'], __('Count visits ads'), array('class'=>'control-label col-sm-4', 'for'=>$forms['count_visits']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['count_visits']['key'], 0);?>
                                <?= FORM::checkbox($forms['count_visits']['key'], 1, (bool) $forms['count_visits']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['count_visits']['key'], 
                                'data-original-title'=> __("Count visits"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("You can choose if you wish to display amount of visits at each advertisement."),
                                ))?>
                                <?= FORM::label($forms['count_visits']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['count_visits']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['sharing']['key'], __('Show sharing buttons'), array('class'=>'control-label col-sm-4', 'for'=>$forms['sharing']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['sharing']['key'], 0);?>
                                <?= FORM::checkbox($forms['sharing']['key'], 1, (bool) $forms['sharing']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['sharing']['key'], 
                                'data-original-title'=> __("Show sharing buttons"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("You can choose if you wish to display sharing buttons at each advertisement."),
                                ))?>
                                <?= FORM::label($forms['sharing']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['sharing']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['related']['key'], __('Related ads'), array('class'=>'control-label col-sm-4', 'for'=>$forms['related']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['related']['key'], $forms['related']['value'], array(
                            'placeholder' => $forms['related']['value'], 
                            'class' => 'tips form-control ', 
                            'id' => $forms['related']['key'],
                            'data-content'=> __("You can choose if you wish to display random related ads at each advertisement"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Related ads"), 
                            'data-rule-required'=>'true',
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['fbcomments']['key'], "<a target='_blank' href='http://docs.yclas.com/add-facebook-comments/'>".__('Facebook comments')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['fbcomments']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['fbcomments']['key'], $forms['fbcomments']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['fbcomments']['key'], 
                            'data-original-title'=> __("Facebook Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your Facebook APP ID to enable the service."),
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['disqus']['key'], "<a target='_blank' href='http://docs.yclas.com/how-to-activate-comments-with-disqus/'>".__('Disqus')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['disqus']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['disqus']['key'], 
                            'data-original-title'=> __("Disqus Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your disqus ID to enable the service."),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['logbee']['key'], "<a target='_blank' href='http://www.logbee.com/'>Logbee</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['logbee']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['logbee']['key'], 0);?>
                                <?= FORM::checkbox($forms['logbee']['key'], 1, (bool) $forms['logbee']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['logbee']['key'], 
                                'data-original-title'=> "Logbee",
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=> "Integrates your site with Logbee",
                                ))?>
                                <?= FORM::label($forms['logbee']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['count_visits']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
            
        <div class="panel panel-default">
            <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/how-to-configure-Google-Map-Settings'>".__("Google Maps Settings")."</a>"?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['map_zoom']['key'], __('Google map zoom level'), array('class'=>'control-label col-sm-4', 'for'=>$forms['map_zoom']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['map_zoom']['key'], $forms['map_zoom']['value'], array(
                            'placeholder' => "16", 
                            'class' => 'tips form-control', 
                            'id' => $forms['map_zoom']['key'], 
                            'data-original-title'=> __("Zoom level"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Google map default zoom level "),
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['center_lat']['key'], __('Map latitude coordinates'), array('class'=>'control-label col-sm-4', 'for'=>$forms['center_lat']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['center_lat']['key'], $forms['center_lat']['value'], array(
                            'placeholder' => "40", 
                            'class' => 'tips form-control', 
                            'id' => $forms['center_lat']['key'], 
                            'data-original-title'=> __("Latitude coordinates"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Google map default latitude coordinates"),
                            'data-rule-number' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['center_lon']['key'], __('Map longitude coordinates'), array('class'=>'control-label col-sm-4', 'for'=>$forms['center_lon']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['center_lon']['key'], $forms['center_lon']['value'], array(
                            'placeholder' => "3", 
                            'class' => 'tips form-control', 
                            'id' => $forms['center_lon']['key'], 
                            'data-original-title'=> __("Longitude coordinates"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Google map default longitude coordinates"),
                            'data-rule-number' => 'true',
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><a target='_blank' href='http://docs.yclas.com/review-system-works/'><?=__("Reviews Configuration")?></a></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['reviews']['key'], __("Enable reviews"), array('class'=>'control-label col-sm-4', 'for'=>$forms['reviews']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['reviews']['key'], 0);?>
                                <?= Form::checkbox($forms['reviews']['key'], 1, (bool) $forms['reviews']['value'], array(
                                'placeholder' => "TRUE or FALSE", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['reviews']['key'], 
                                'data-content'=> __("Enables reviews for ads and the users."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Enable reviews"),
                                ))?>
                                <?= FORM::label($forms['reviews']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['reviews']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['reviews_paid']['key'], __("Only for paid transactions"), array('class'=>'control-label col-sm-4', 'for'=>$forms['reviews_paid']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['reviews_paid']['key'], 0);?>
                                <?= Form::checkbox($forms['reviews_paid']['key'], 1, (bool) $forms['reviews_paid']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['reviews_paid']['key'], 
                                'data-content'=> __("You need to enable paypal link to allow only reviews on purchases."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Reviews for paid transactions"),
                                ))?>
                                <?= FORM::label($forms['reviews_paid']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['reviews_paid']['key']))?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-8 col-sm-offset-4">
                    <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
                </div>
            </div>
        </div>
        <?= FORM::close()?>
    </div>
</div>