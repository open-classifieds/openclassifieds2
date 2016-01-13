<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Advertisement Configuration')?></h1>
        <span class="page-description"><?=__('List of optional fields. To activate/deactivate select "ON/OFF" in desired field.')?> <a href=""><?=__('Read more')?></a></span>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')), array('class'=>'form-horizontal config', 'enctype'=>'multipart/form-data'))?>
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__('Listing Options')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-change-settings-for-ads/"><?=__('Read more')?></a></span></div>
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
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
                        <?= FORM::label($forms['ads_in_home']['key'], "<a target='_blank' href='https://docs.yclas.com/manage-ads-slider/'>".__('Advertisements in home')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['ads_in_home']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['ads_in_home']['key'], $ads_in_home
                            , $forms['ads_in_home']['value'], array(
                            'placeholder' => $forms['ads_in_home']['value'], 
                            'class' => 'tips form-control ', 
                            'id' => $forms['ads_in_home']['key'],
                            'data-content'=> __("You can choose what ads you want to display in home."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Advertisements in home"), 
                            ))?> 
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__('Publish Options')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-change-settings-for-ads/"><?=__('Read more')?></a></span></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['login_to_post']['key'], "<a target='_blank' href='https://docs.yclas.com/force-registration-posting-new-ad/'>".__('Require login to post')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['login_to_post']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['login_to_post']['key'], 0);?>
                                <?= FORM::checkbox($forms['login_to_post']['key'], 1, (bool) $forms['login_to_post']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['login_to_post']['key'], 
                                'data-original-title'=> __("Require login to post"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                        <?= FORM::label($forms['tos']['key'], "<a target='_blank' href='https://docs.yclas.com/how_to_add_pages/'>".__('Terms of Service')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['tos']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['tos']['key'], $pages, $forms['tos']['value'], array(
                            'placeholder' => "http://foo.com/", 
                            'class' => 'tips form-control', 
                            'id' => $forms['tos']['key'], 
                            'data-content'=> __("If you choose to use terms of service, you can select activate. And to edit content, select link 'Content' on your admin panel sidebar. Find page named 'Terms of service' click 'Edit'. In section 'Description' add content that suits you."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-role'=>'tagsinput',
                            'data-content'=>__("You need to write your banned words to enable the service."),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['validate_banned_words']['key'], __('Validate banned words'), array('class'=>'control-label col-sm-4', 'for'=>$forms['validate_banned_words']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['validate_banned_words']['key'], 0);?>
                                <?= FORM::checkbox($forms['validate_banned_words']['key'], 1, (bool) $forms['validate_banned_words']['value'], array(
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['validate_banned_words']['key'], 
                                'data-original-title'=> __("Enables banned words validation"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Enables banned words validation"),
                                ))?>
                                <?= FORM::label($forms['validate_banned_words']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['validate_banned_words']['key']))?>
                            </div>
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
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Banned word replacement replaces selected array with the string you provided."),
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__('Advertisement Fields')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-manage-advertisement-fields/"><?=__('Read more')?></a></span></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['description']['key'], __('Description'), array('class'=>'control-label col-sm-4', 'for'=>$forms['description']['key']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= FORM::hidden($forms['description']['key'], 0);?>
                                <?= FORM::checkbox($forms['description']['key'], 1, (bool) $forms['description']['value'], array(
                                'placeholder' => "", 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['description']['key'], 
                                'data-original-title'=> __("Description field"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-content'=>__("Displays the field Description in the Ad form."),
                                ))?>
                                <?= FORM::label($forms['description']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['description']['key']))?>
                            </div>
                        </div>
                    </div>

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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
            <div class="panel-heading"><h4><?=__('Advertisement Display Options')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-configure-advertisement-display-option/"><?=__('Read more')?></a></span></div>
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Related ads"), 
                            'data-rule-required'=>'true',
                            'data-rule-digits' => 'true',
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['fbcomments']['key'], "<a target='_blank' href='https://docs.yclas.com/add-facebook-comments/'>".__('Facebook comments')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['fbcomments']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['fbcomments']['key'], $forms['fbcomments']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['fbcomments']['key'], 
                            'data-original-title'=> __("Facebook Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your Facebook APP ID to enable the service."),
                            ))?> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['disqus']['key'], "<a target='_blank' href='https://docs.yclas.com/how-to-activate-comments-with-disqus/'>".__('Disqus')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['disqus']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control', 
                            'id' => $forms['disqus']['key'], 
                            'data-original-title'=> __("Disqus Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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
            <div class="panel-heading"><h4><?=__('Google Maps Settings')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-configure-Google-Map-Settings"><?=__('Read more')?></a></span></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <?  $map_styles = array(
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
                        <?= FORM::label($forms['reviews_paid']['key'], __("Google map style"), array('class'=>'control-label col-sm-4', 'for'=>$forms['reviews_paid']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['map_style']['key'], array_flip($map_styles), $forms['map_style']['value'], array(
                            'placeholder' => "http://foo.com/", 
                            'class' => 'tips form-control', 
                            'id' => $forms['map_style']['key'], 
                            'data-content'=> __("Custom Google Maps styling"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Google map style"),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['map_zoom']['key'], __('Google map zoom level'), array('class'=>'control-label col-sm-4', 'for'=>$forms['map_zoom']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['map_zoom']['key'], $forms['map_zoom']['value'], array(
                            'placeholder' => "16", 
                            'class' => 'tips form-control', 
                            'id' => $forms['map_zoom']['key'], 
                            'data-original-title'=> __("Zoom level"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("Google map default longitude coordinates"),
                            'data-rule-number' => 'true',
                            ))?> 
                        </div>
                    </div>

                    <?if (Core::config('general.auto_locate') == 1) :?>
                        <div class="form-group">
                            <?= FORM::label($forms['auto_locate_distance']['key'], __('Auto locate distance'), array('class'=>'control-label col-sm-4', 'for'=>$forms['auto_locate_distance']['key']))?>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <?= FORM::input($forms['auto_locate_distance']['key'], $forms['auto_locate_distance']['value'], array(
                                    'placeholder' => "100", 
                                    'class' => 'tips form-control', 
                                    'id' => $forms['auto_locate_distance']['key'], 
                                    'data-original-title'=> __("Auto locate distance"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-content'=>__("Sets maximum distance of closest suggested locations to the visitor."),
                                    'data-rule-number' => 'true',
                                    ))?>
                                    <div class="input-group-addon"><?=Core::config('general.measurement') == 'metric' ? 'Kilometers' : 'Miles'?></div>
                                </div>
                            </div>
                        </div>
                    <?else :?>
                        <?= FORM::hidden($forms['auto_locate_distance']['key'], $forms['auto_locate_distance']['value']);?>
                    <?endif?>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__('Reviews Configuration')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/review-system-works/"><?=__('Read more')?></a></span></div>
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
                                'data-placement'=>"bottom",
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
                                'data-placement'=>"bottom",
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