<?php defined('SYSPATH') or die('No direct script access.');?>


<?=Form::errors()?>
<div id="page-general-configuration" class="page-header">
    <a class="btn btn-default pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'config'))?>" title="<?=__('All configurations')?>"><?=__('All configurations')?></a>
    <h1><?=__('General Configuration')?> <small><?=__('General site settings.')?></small></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general')), array('class'=>'config', 'enctype'=>'multipart/form-data'))?>
        <div class="panel panel-default">
            <div class="panel-heading"><?=__('General Configuration')?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['maintenance']['id'], "<a target='_blank' href='http://open-classifieds.com/2013/10/15/how-to-activate-maintenance-mode/'>".__("Maintenance Mode")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['maintenance']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['maintenance']['key'], 1, (bool) $forms['maintenance']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['maintenance']['id'], 
                                'data-content'=> __("Enables the site to maintenance"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Maintenance Mode"),
                                ))?>
                                <?= FORM::label($forms['maintenance']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['maintenance']['id']))?>
                                <?= FORM::hidden($forms['maintenance']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['disallowbots']['id'], "<a target='_blank' href='http://open-classifieds.com/2015/01/22/allowdisallow-bots-crawlers/'>".__("Disallows (blocks) Bots and Crawlers on this website")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['disallowbots']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['disallowbots']['key'], 1, (bool) $forms['disallowbots']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['disallowbots']['id'], 
                                'data-content'=> __("Disallows Bots and Crawlers on the website"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Disallows (blocks) Bots and Crawlers"),
                                ))?>
                                <?= FORM::label($forms['disallowbots']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['maintenance']['id']))?>
                                <?= FORM::hidden($forms['disallowbots']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['site_name']['id'], "<a target='_blank' href='http://open-classifieds.com/2015/01/21/change-site-name-site-description/'>".__('Site name')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['site_name']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['site_name']['key'], $forms['site_name']['value'], array(
                            'placeholder' => 'Open-classifieds', 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['site_name']['id'],
                            'data-content'=> __("Here you can declare your display name. This is seen by everyone!"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Site Name"), 
                            'data-rule-required'=>'true',
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['site_description']['id'], "<a target='_blank' href='http://open-classifieds.com/2015/01/21/change-site-name-site-description/'>".__('Site description')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['site_description']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::textarea($forms['site_description']['key'], $forms['site_description']['value'], array(
                            'placeholder' => __('Description of your site in no more than 160 characters.'),
                            'rows' => 3, 'cols' => 50, 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['site_description']['id'],
                            'data-content'=> __('Description used for the <meta name="description"> of the home page. Might be used by Google as search result snippet. (max. 160 chars)'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Site Description"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['moderation']['id'], "<a target='_blank' href='http://open-classifieds.com/2013/06/16/how-ads-moderation-works/'>".__('Moderation')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['moderation']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['moderation']['key'], array(0=>__("Post directly"),1=>__("Moderation on"),2=>__("Payment on"),3=>__("Email confirmation on"),4=>__("Email confirmation with Moderation"),5=>__("Payment with Moderation")), $forms['moderation']['value'], array(
                            'placeholder' => $forms['moderation']['value'], 
                            'class' => 'tips form-control input-sm ', 
                            'id' => $forms['moderation']['id'],
                            'data-content'=> __("Moderation is how you control newly created advertisements. You can set it up to fulfill your needs. For example, 'Post directly' will enable new ads to be posted directly, and get published as soon they submit."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Moderation"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['landing_page']['id'], "<a target='_blank' href='http://open-classifieds.com/2013/09/20/home-or-listing/'>".__('Landing page')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['landing_page']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['landing_page']['key'], array('{"controller":"home","action":"index"}'=>'HOME','{"controller":"ad","action":"listing"}'=>'LISTING'), $forms['landing_page']['value'], array(
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['landing_page']['id'], 
                            'data-content'=> __("It changes landing page of website"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Landing page"),
                            ))?> 
                        </div>
                    </div>

                    <?=FORM::hidden($forms['base_url']['key'], $forms['base_url']['value'])?>

                    <?$pages = array(''=>__('Deactivated'))?>
                    <?foreach (Model_Content::get_pages() as $key => $value) {
                        $pages[$value->seotitle] = $value->title;
                    }?>
                    <div class="form-group">
                        <?= FORM::label($forms['alert_terms']['id'], "<a target='_blank' href='http://open-classifieds.com/2013/10/14/activate-access-terms-alert/'>".__('Accept Terms Alert')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['alert_terms']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['alert_terms']['key'], $pages, $forms['alert_terms']['value'], array( 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['alert_terms']['id'], 
                            'data-content'=> __("If you choose to use alert terms, you can select page you want to render. And to edit content, select link 'Content' on your admin panel sidebar. Find page named <name_you_specified> click 'Edit'. In section 'Description' add content that suits you."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Accept Terms Alert"),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['cookie_consent']['id'], __("Cookie consent"), array('class'=>'control-label col-sm-4', 'for'=>$forms['cookie_consent']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['cookie_consent']['key'], 1, (bool) $forms['cookie_consent']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['cookie_consent']['id'], 
                                'data-content'=> __("Enables an alert to accept cookies"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Cookie consent"),
                                ))?>
                                <?= FORM::label($forms['cookie_consent']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['cookie_consent']['id']))?>
                                <?= FORM::hidden($forms['cookie_consent']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['analytics']['id'], "<a target='_blank' href='http://open-classifieds.com/2015/03/06/how-to-add-tracking-codes/'>".__('Analytics Tracking ID')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['analytics']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['analytics']['key'], $forms['analytics']['value'], array(
                            'placeholder' => 'UA-XXXXX-YY', 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['analytics']['id'],
                            'data-content'=> __("Once logged in your Google Analytics, you can find the Tracking ID in the Accounts List or in the Property Settings"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Analytics Tracking ID"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['akismet_key']['id'], "<a target='_blank' href='http://akismet.com/'>".__('Akismet Key')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['akismet_key']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['akismet_key']['key'], $forms['akismet_key']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['akismet_key']['id'],
                            'data-content'=> __("Providing akismet key will activate this feature. This feature deals with spam posts and emails."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Akismet Key"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['html_head']['id'], __('HTML in HEAD element'), array('class'=>'control-label col-sm-4', 'for'=>$forms['html_head']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::textarea($forms['html_head']['key'], $forms['html_head']['value'], array(
                            'placeholder' => '',
                            'rows' => 3, 'cols' => 50, 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['html_head']['id'],
                            'data-content'=> __('To include your custom HTML code (validation metadata, reference to JS/CSS files, etc.) in the HEAD element of the rendered page.'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__('HTML in HEAD element'), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['html_footer']['id'], __('HTML in footer'), array('class'=>'control-label col-sm-4', 'for'=>$forms['html_footer']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::textarea($forms['html_footer']['key'], $forms['html_footer']['value'], array(
                            'placeholder' => '',
                            'rows' => 3, 'cols' => 50, 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['html_footer']['id'],
                            'data-content'=> __('To include your custom HTML code (reference to JS or CSS files, etc.) in the footer of the rendered page.'),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__('HTML in footer'), 
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><?=__('Regional Settings')?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['number_format']['id'], "<a target='_blank' href='http://open-classifieds.com/2013/08/06/how-to-currency-format/'>".__('Money format')."</a>", array('class'=>'control-label col-sm-4','for'=>$forms['number_format']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['number_format']['key'], $forms['number_format']['value'], array(
                            'placeholder' => "20", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['number_format']['id'],
                            'data-content'=> __("Number format is how you want to display numbers related to advertisements. More specific advertisement price. Every country have a specific way of dealing with decimal digits."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Decimal representation"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['date_format']['id'], "<a target='_blank' href='http://open-classifieds.com/2014/03/22/change-date-format/'>".__('Date format')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['date_format']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['date_format']['key'], $forms['date_format']['value'], array(
                            'placeholder' => "d/m/Y", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['date_format']['id'], 
                            'data-content'=> __("Each advertisement has a publish date. By selecting format, you can change how it is shown on your website."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Date format"),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4"><?=__("Time Zone")?>:</label>                
                        <div class="col-sm-8">
                        <?= FORM::select($forms['timezone']['key'], Date::get_timezones(), core::request('TIMEZONE',date_default_timezone_get()), array(
                                'placeholder' => "Madrid [+1:00]", 
                                'class' => 'tips form-control', 
                                'id' => $forms['timezone']['id'], 
                                ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><?=__("Enable Additional Features")?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['blog']['id'], "<a target='_blank' href='http://open-classifieds.com/2014/01/22/how-to-create-a-blog/'>".__("Activates Blog posting")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['blog']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['blog']['key'], 1, (bool) $forms['blog']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['blog']['id'], 
                                'data-content'=> __("Once set to TRUE, enables blog posts"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Activates Blog posting"),
                                ))?>
                                <?= FORM::label($forms['blog']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['blog']['id']))?>
                                <?= FORM::hidden($forms['blog']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['forums']['id'], "<a target='_blank' href='http://open-classifieds.com/2013/06/20/showcase-how-to-build-a-forum-with-oc/'>".__("Activates Forums")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['forums']['id']))?>
                        <div class="col-md-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['forums']['key'], 1, (bool) $forms['forums']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['forums']['id'], 
                                'data-content'=> __("Once set to TRUE, enables forums posts"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Activates Forums"),
                                ))?>
                                <?= FORM::label($forms['forums']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['forums']['id']))?>
                                <?= FORM::hidden($forms['forums']['key'], 0);?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= FORM::label($forms['faq']['id'], "<a target='_blank' href='http://open-classifieds.com/2014/01/21/create-frequent-asked-questions-faq/'>".__("Activates FAQ")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['faq']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['faq']['key'], 1, (bool) $forms['faq']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['faq']['id'], 
                                'data-content'=> __("Once set to TRUE, enables FAQ"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Activates FAQ"),
                                ))?>
                                <?= FORM::label($forms['faq']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['faq']['id']))?>
                                <?= FORM::hidden($forms['faq']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['black_list']['id'], "<a target='_blank' href='http://open-classifieds.com/2014/05/08/activate-blacklist-works/'>".__("Black List")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['black_list']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['black_list']['key'], 1, (bool) $forms['black_list']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['black_list']['id'], 
                                'data-content'=> __("If advertisement is marked as spam, user is also marked. Can not publish new ads or register until removed from Black List! Also will not allow users from disposable email addresses to register."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Black List"),
                                ))?>
                                <?= FORM::label($forms['black_list']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['black_list']['id']))?>
                                <?= FORM::hidden($forms['black_list']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['search_by_description']['id'], __("Include search by description"), array('class'=>'control-label col-sm-4', 'for'=>$forms['search_by_description']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['search_by_description']['key'], 1, (bool) $forms['search_by_description']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['search_by_description']['id'], 
                                'data-content'=> __("Once set to TRUE, enables search to look for key words in description"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Include search by description"),
                                ))?>
                                <?= FORM::label($forms['search_by_description']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['search_by_description']['id']))?>
                                <?= FORM::hidden($forms['search_by_description']['key'], 0);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><?=__("Comments Configuration")?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['blog_disqus']['id'], "<a target='_blank' href='http://open-classifieds.com/2014/01/22/how-to-create-a-blog/'>".__('Disqus for blog')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['blog_disqus']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['blog_disqus']['key'], $forms['blog_disqus']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['blog_disqus']['id'], 
                            'data-original-title'=> __("Disqus for Blog Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your disqus ID to enable the service."),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['faq_disqus']['id'], "<a target='_blank' href='http://open-classifieds.com/2014/01/21/create-frequent-asked-questions-faq/'>".__('Disqus for FAQ')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['faq_disqus']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['faq_disqus']['key'], $forms['faq_disqus']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['faq_disqus']['id'], 
                            'data-original-title'=> __("Disqus for FAQ Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your disqus ID to enable the service."),
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><?=__("reCAPTCHA Configuration")?></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['recaptcha_active']['id'], "<a target='_blank' href='https://www.google.com/recaptcha/intro/index.html'>".__("Enable reCAPTCHA as captcha provider")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['recaptcha_active']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['recaptcha_active']['key'], 1, (bool) $forms['recaptcha_active']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['recaptcha_active']['id'], 
                                'data-content'=> __("If advertisement is marked as spam, user is also marked. Can not publish new ads or register until removed from Black List! Also will not allow users from disposable email addresses to register."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"right",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Black List"),
                                ))?>
                                <?= FORM::label($forms['recaptcha_active']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['recaptcha_active']['id']))?>
                                <?= FORM::hidden($forms['recaptcha_active']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['recaptcha_sitekey']['id'], "<a target='_blank' href='https://www.google.com/recaptcha/admin#list'>".__('reCAPTCHA Site Key')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['recaptcha_sitekey']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['recaptcha_sitekey']['key'], $forms['recaptcha_sitekey']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['recaptcha_sitekey']['id'], 
                            'data-original-title'=> __("reCaptcha Site Key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write reCAPTCHA Site Key to enable the service."),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['recaptcha_secretkey']['id'], "<a target='_blank' href='https://www.google.com/recaptcha/admin#list'>".__('reCAPTCHA Secret Key')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['recaptcha_secretkey']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['recaptcha_secretkey']['key'], $forms['recaptcha_secretkey']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['recaptcha_secretkey']['id'], 
                            'data-original-title'=> __("reCaptcha Secret Key"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your reCAPTCHA Secret Key to enable the service."),
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-sm-8 col-sm-offset-4">
                    <?= FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
