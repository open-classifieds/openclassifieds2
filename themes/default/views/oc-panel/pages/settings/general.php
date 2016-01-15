<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>
<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('General Configuration')?></h1>
        <span class="page-description"><?=__('General site settings.')?> <a href=""><?=__('Read more')?></a></span>
        <a class="btn btn-primary pull-right ajax-load new-btn" href="<?=Route::url('oc-panel',array('controller'=>'config'))?>" title="<?=__('All configurations')?>"><?=__('All configurations')?></a>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general')), array('class'=>'config', 'enctype'=>'multipart/form-data'))?>
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__('General Configuration')?></h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['maintenance']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-activate-maintenance-mode/'>".__("Maintenance Mode")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['maintenance']['id']))?>
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
                        <?= FORM::label($forms['disallowbots']['id'], "<a target='_blank' href='https://docs.yclas.com/allowdisallow-bots-crawlers/'>".__("Disallows (blocks) Bots and Crawlers on this website")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['disallowbots']['id']))?>
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
                        <?= FORM::label($forms['site_name']['id'], "<a target='_blank' href='https://docs.yclas.com/change-site-name-site-description/'>".__('Site name')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['site_name']['id']))?>
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
                        <?= FORM::label($forms['site_description']['id'], "<a target='_blank' href='https://docs.yclas.com/change-site-name-site-description/'>".__('Site description')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['site_description']['id']))?>
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
                        <?= FORM::label($forms['moderation']['id'], "<a target='_blank' href='https://docs.yclas.com/how-ads-moderation-works/'>".__('Moderation')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['moderation']['id']))?>
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
                        <?= FORM::label($forms['landing_page']['id'], "<a target='_blank' href='https://docs.yclas.com/home-or-listing/'>".__('Landing page')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['landing_page']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['landing_page']['key'], 
                                array('{"controller":"home","action":"index"}'=>'HOME','{"controller":"ad","action":"listing"}'=>'LISTING','{"controller":"user","action":"index"}'=>'USERS'), $forms['landing_page']['value'], array(
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

                    <div class="form-group">
                            <?= FORM::label($forms['api_key']['id'], "<a target='_blank' href='https://docs.yclas.com/api-documentation/'>".__('API Key')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['api_key']['id']))?>
                            <div class="col-sm-8">
                                <?= FORM::input($forms['api_key']['key'], $forms['api_key']['value'], array(
                                'placeholder' => "", 
                                'class' => 'tips form-control input-sm', 
                                'id' => $forms['api_key']['id'],
                                'data-content'=> __("Integrate anything using your site API Key."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Installation API Key"), 
                                ))?> 
                            </div>
                    </div>

                    <?$pages = array(''=>__('Deactivated'))?>
                    <?foreach (Model_Content::get_pages() as $key => $value) {
                        $pages[$value->seotitle] = $value->title;
                    }?>
                    <div class="form-group">
                        <?= FORM::label($forms['alert_terms']['id'], "<a target='_blank' href='https://docs.yclas.com/activate-access-terms-alert/'>".__('Accept Terms Alert')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['alert_terms']['id']))?>
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
                        <?= FORM::label($forms['contact_page']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-add-text-contact-page/'>".__('Contact page content')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['contact_page']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::select($forms['contact_page']['key'], $pages, $forms['contact_page']['value'], array( 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['contact_page']['id'], 
                            'data-content'=> __("Adds content to contact page"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Contact page content"),
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
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Cookie consent"),
                                ))?>
                                <?= FORM::label($forms['cookie_consent']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['cookie_consent']['id']))?>
                                <?= FORM::hidden($forms['cookie_consent']['key'], 0);?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['email_domains']['key'], __('Allowed email domains'), array('class'=>'control-label col-sm-4', 'for'=>$forms['email_domains']['key']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['email_domains']['key'], $forms['email_domains']['value'], array(
                            'placeholder' => __('For email domain push enter.'), 
                            'class' => 'tips form-control', 
                            'id' => $forms['email_domains']['key'], 
                            'data-original-title'=> __("Email domains are separated with coma (,)"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-role'=>'tagsinput',
                            'data-content'=>__("You need to write your email domains to enable the service."),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['analytics']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-add-tracking-codes/'>".__('Analytics Tracking ID')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['analytics']['id']))?>
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
                        <?= FORM::label($forms['gcm_apikey']['id'], "<a target='_blank' href='https://docs.yclas.com/native-apps/#push-notifications'>".__('GCM API Key')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['gcm_apikey']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['gcm_apikey']['key'], $forms['gcm_apikey']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['gcm_apikey']['id'],
                            'data-content'=> __("Push notifications for your native app. Using Google Cloud Messaging, insert your API Key here."),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-original-title'=>__("Push notifications"), 
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['html_head']['id'], "<a target='_blank' href='https://docs.yclas.com/html-in-head-element/'>".__('HTML in HEAD element')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['html_head']['id']))?>
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
                        <?= FORM::label($forms['html_footer']['id'], "<a target='_blank' href='https://docs.yclas.com/html-in-footer/'>".__('HTML in footer')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['html_footer']['id']))?>
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
            <div class="panel-heading"><h4><?=__('Regional Settings')?></h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['number_format']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-currency-format/'>".__('Money format')."</a>", array('class'=>'control-label col-sm-4','for'=>$forms['number_format']['id']))?>
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
                        <?= FORM::label($forms['date_format']['id'], "<a target='_blank' href='https://docs.yclas.com/change-date-format/'>".__('Date format')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['date_format']['id']))?>
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
                        <label class="control-label col-sm-4"><?="<a target='_blank' href='https://docs.yclas.com/how-to-change-time-zone/'>".__("Time Zone")."</a>"?>:</label>                
                        <div class="col-sm-8">
                        <?= FORM::select($forms['timezone']['key'], Date::get_timezones(), core::request('TIMEZONE',date_default_timezone_get()), array(
                                'placeholder' => "Madrid [+1:00]", 
                                'class' => 'tips form-control', 
                                'id' => $forms['timezone']['id'], 
                                ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4"><?=__("Measurement Units")?>:</label>                
                        <div class="col-sm-8">
                            <?= FORM::select($forms['measurement']['key'], array('metric' => __("Metric"), 'imperial' => __("Imperial")), $forms['measurement']['value'], array(
                                'placeholder' => $forms['measurement']['value'], 
                                'class' => 'tips form-control input-sm ', 
                                'id' => $forms['measurement']['id'],
                                'data-content'=> __("Measurement units used by the system."),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Measurement"), 
                            ))?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__("Enable Additional Features")?></h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
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
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Include search by description"),
                                ))?>
                                <?= FORM::label($forms['search_by_description']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['search_by_description']['id']))?>
                                <?= FORM::hidden($forms['search_by_description']['key'], 0);?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?= FORM::label($forms['search_multi_catloc']['id'], __("Multi select category and location search"), array('class'=>'control-label col-sm-4', 'for'=>$forms['search_multi_catloc']['id']))?>
                        <div class="col-sm-8">
                            <div class="onoffswitch">
                                <?= Form::checkbox($forms['search_multi_catloc']['key'], 1, (bool) $forms['search_multi_catloc']['value'], array(
                                'placeholder' => __("TRUE or FALSE"), 
                                'class' => 'onoffswitch-checkbox', 
                                'id' => $forms['search_multi_catloc']['id'], 
                                'data-content'=> __("Once set to TRUE, enables multi select category and location search"),
                                'data-trigger'=>"hover",
                                'data-placement'=>"bottom",
                                'data-toggle'=>"popover",
                                'data-original-title'=>__("Multi select category and location search"),
                                ))?>
                                <?= FORM::label($forms['search_multi_catloc']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['search_multi_catloc']['id']))?>
                                <?= FORM::hidden($forms['search_multi_catloc']['key'], 0);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__("Comments Configuration")?></h4></div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <?= FORM::label($forms['blog_disqus']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-create-a-blog/'>".__('Disqus for blog')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['blog_disqus']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['blog_disqus']['key'], $forms['blog_disqus']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['blog_disqus']['id'], 
                            'data-original-title'=> __("Disqus for Blog Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your disqus ID to enable the service."),
                            ))?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <?= FORM::label($forms['faq_disqus']['id'], "<a target='_blank' href='https://docs.yclas.com/create-frequent-asked-questions-faq/'>".__('Disqus for FAQ')."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['faq_disqus']['id']))?>
                        <div class="col-sm-8">
                            <?= FORM::input($forms['faq_disqus']['key'], $forms['faq_disqus']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control input-sm', 
                            'id' => $forms['faq_disqus']['id'], 
                            'data-original-title'=> __("Disqus for FAQ Comments"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"bottom",
                            'data-toggle'=>"popover",
                            'data-content'=>__("You need to write your disqus ID to enable the service."),
                            ))?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><h4><?=__("reCAPTCHA Configuration")?></h4></div>
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
                                'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
                            'data-placement'=>"bottom",
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
