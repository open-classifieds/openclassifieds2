<?php defined('SYSPATH') or die('No direct script access.');?>


<?=Form::errors()?>
<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Plugins')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href=""><?=__('Read more')?></a></span>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-8">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'plugins')), array('class'=>'config', 'enctype'=>'multipart/form-data'))?>
            <div class="panel panel-default">
                <div class="panel-heading"><h4><?=__('Enable or disable plugins')?></h4></div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <?= FORM::label($forms['blog']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-create-a-blog/'>".__("Blog System")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['blog']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['blog']['key'], 1, (bool) $forms['blog']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['blog']['id'], 
                                    'data-content'=> __("Once set to TRUE, enables blog posts"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Activates Blog posting"),
                                    ))?>
                                    <?= FORM::label($forms['blog']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['blog']['id']))?>
                                    <?= FORM::hidden($forms['blog']['key'], 0);?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['forums']['id'], "<a target='_blank' href='https://docs.yclas.com/showcase-how-to-build-a-forum-with-oc/'>".__("Forum System")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['forums']['id']))?>
                            <div class="col-md-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['forums']['key'], 1, (bool) $forms['forums']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['forums']['id'], 
                                    'data-content'=> __("Once set to TRUE, enables forums posts"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Activates Forums"),
                                    ))?>
                                    <?= FORM::label($forms['forums']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['forums']['id']))?>
                                    <?= FORM::hidden($forms['forums']['key'], 0);?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= FORM::label($forms['faq']['id'], "<a target='_blank' href='https://docs.yclas.com/create-frequent-asked-questions-faq/'>".__("FAQ System")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['faq']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['faq']['key'], 1, (bool) $forms['faq']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['faq']['id'], 
                                    'data-content'=> __("Once set to TRUE, enables FAQ"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Activates FAQ"),
                                    ))?>
                                    <?= FORM::label($forms['faq']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['faq']['id']))?>
                                    <?= FORM::hidden($forms['faq']['key'], 0);?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['messaging']['id'], "<a target='_blank' href='https://docs.yclas.com/how-to-use-messaging-system/'>".__("Messaging System")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['messaging']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['messaging']['key'], 1, (bool) $forms['messaging']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"),
                                    'class' => 'onoffswitch-checkbox',
                                    'id' => $forms['messaging']['id'],
                                    'data-content'=> __("Once set to TRUE, enables Messaging System"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Activates Messaging System"),
                                    ))?>
                                    <?= FORM::label($forms['messaging']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['messaging']['id']))?>
                                    <?= FORM::hidden($forms['messaging']['key'], 0);?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['black_list']['id'], "<a target='_blank' href='https://docs.yclas.com/activate-blacklist-works/'>".__("Black List")."</a>", array('class'=>'control-label col-sm-4', 'for'=>$forms['black_list']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['black_list']['key'], 1, (bool) $forms['black_list']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['black_list']['id'], 
                                    'data-content'=> __("If advertisement is marked as spam, user is also marked. Can not publish new ads or register until removed from Black List! Also will not allow users from disposable email addresses to register."),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Black List"),
                                    ))?>
                                    <?= FORM::label($forms['black_list']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['black_list']['id']))?>
                                    <?= FORM::hidden($forms['black_list']['key'], 0);?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= FORM::label($forms['auto_locate']['id'], __("Auto Locate Visitors"), array('class'=>'control-label col-sm-4', 'for'=>$forms['auto_locate']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['auto_locate']['key'], 1, (bool) $forms['auto_locate']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['auto_locate']['id'], 
                                    'data-content'=> __("Once set to TRUE, enables auto locate visitors"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Include auto locate visitors"),
                                    ))?>
                                    <?= FORM::label($forms['auto_locate']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['auto_locate']['id']))?>
                                    <?= FORM::hidden($forms['auto_locate']['key'], 0);?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['social_auth']['id'], __('Social Auth'), array('class'=>'control-label col-sm-4', 'for'=>$forms['social_auth']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['social_auth']['key'], 1, (bool) $forms['social_auth']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['social_auth']['id'], 
                                    'data-content'=> __("Once set to TRUE, enables social auth"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Social Auth"),
                                    ))?>
                                    <?= FORM::label($forms['social_auth']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['social_auth']['id']))?>
                                    <?= FORM::hidden($forms['social_auth']['key'], 0);?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= FORM::label($forms['adblock']['id'], __('Adblock Detection'), array('class'=>'control-label col-sm-4', 'for'=>$forms['adblock']['id']))?>
                            <div class="col-sm-8">
                                <div class="onoffswitch">
                                    <?= Form::checkbox($forms['adblock']['key'], 1, (bool) $forms['adblock']['value'], array(
                                    'placeholder' => __("TRUE or FALSE"), 
                                    'class' => 'onoffswitch-checkbox', 
                                    'id' => $forms['adblock']['id'], 
                                    'data-content'=> __("Once set to TRUE, enables adblock detection"),
                                    'data-trigger'=>"hover",
                                    'data-placement'=>"bottom",
                                    'data-toggle'=>"popover",
                                    'data-original-title'=>__("Adblock Detection"),
                                    ))?>
                                    <?= FORM::label($forms['adblock']['id'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['adblock']['id']))?>
                                    <?= FORM::hidden($forms['adblock']['key'], 0);?>
                                </div>
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
