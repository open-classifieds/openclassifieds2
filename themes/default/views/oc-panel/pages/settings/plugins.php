<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<h1 class="page-header page-title">
    <?=__('Plugins')?>
</h1>
<hr>

<div class="flex flex-wrap">
    <div class="md:w-full pr-4 pl-4 lg:w-full pr-4 pl-4">
        <?=FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'plugins')), array('class'=>'config ajax-load', 'enctype'=>'multipart/form-data'))?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="mb-4">
                        <?=FORM::label($forms['blog']['id'], __("Blog System"), array('class'=>'control-label', 'for'=>$forms['blog']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-create-a-blog/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['blog']['key'], 1, (bool) $forms['blog']['value'], array('id' => $forms['blog']['key'].'1'))?>
                            <?=Form::label($forms['blog']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['blog']['key'], 0, ! (bool) $forms['blog']['value'], array('id' => $forms['blog']['key'].'0'))?>
                            <?=Form::label($forms['blog']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['forums']['id'], __("Forum System"), array('class'=>'control-label', 'for'=>$forms['forums']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/showcase-how-to-build-a-forum-with-oc/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['forums']['key'], 1, (bool) $forms['forums']['value'], array('id' => $forms['forums']['key'].'1'))?>
                            <?=Form::label($forms['forums']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['forums']['key'], 0, ! (bool) $forms['forums']['value'], array('id' => $forms['forums']['key'].'0'))?>
                            <?=Form::label($forms['forums']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['faq']['id'], __("FAQ System"), array('class'=>'control-label', 'for'=>$forms['faq']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/create-frequent-asked-questions-faq/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['faq']['key'], 1, (bool) $forms['faq']['value'], array('id' => $forms['faq']['key'].'1'))?>
                            <?=Form::label($forms['faq']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['faq']['key'], 0, ! (bool) $forms['faq']['value'], array('id' => $forms['faq']['key'].'0'))?>
                            <?=Form::label($forms['faq']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['messaging']['id'], __("Messaging System"), array('class'=>'control-label', 'for'=>$forms['messaging']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-use-messaging-system/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['messaging']['key'], 1, (bool) $forms['messaging']['value'], array('id' => $forms['messaging']['key'].'1'))?>
                            <?=Form::label($forms['messaging']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['messaging']['key'], 0, ! (bool) $forms['messaging']['value'], array('id' => $forms['messaging']['key'].'0'))?>
                            <?=Form::label($forms['messaging']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['black_list']['id'], __("Black List"), array('class'=>'control-label', 'for'=>$forms['black_list']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/activate-blacklist-works/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['black_list']['key'], 1, (bool) $forms['black_list']['value'], array('id' => $forms['black_list']['key'].'1'))?>
                            <?=Form::label($forms['black_list']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['black_list']['key'], 0, ! (bool) $forms['black_list']['value'], array('id' => $forms['black_list']['key'].'0'))?>
                            <?=Form::label($forms['black_list']['key'].'0', __('Disabled'))?>
                        </div>
                        <span class="help-block">
                            <?=__("If advertisement is marked as spam, user is also marked. Can not publish new ads or register until removed from Black List! Also will not allow users from disposable email addresses to register.")?>
                        </span>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['auto_locate']['id'], __("Auto Locate Visitors"), array('class'=>'control-label', 'for'=>$forms['auto_locate']['id']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['auto_locate']['key'], 1, (bool) $forms['auto_locate']['value'], array('id' => $forms['auto_locate']['key'].'1'))?>
                            <?=Form::label($forms['auto_locate']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['auto_locate']['key'], 0, ! (bool) $forms['auto_locate']['value'], array('id' => $forms['auto_locate']['key'].'0'))?>
                            <?=Form::label($forms['auto_locate']['key'].'0', __('Disabled'))?>
                        </div>
                        <span class="help-block">
                            <?=__("Get the geographical position of a user. Requires setting up SSL on your website.")?>
                        </span>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['social_auth']['id'], __('Social Auth'), array('class'=>'control-label', 'for'=>$forms['social_auth']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/how-to-login-using-social-auth-facebook-google-twitter/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['social_auth']['key'], 1, (bool) $forms['social_auth']['value'], array('id' => $forms['social_auth']['key'].'1'))?>
                            <?=Form::label($forms['social_auth']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['social_auth']['key'], 0, ! (bool) $forms['social_auth']['value'], array('id' => $forms['social_auth']['key'].'0'))?>
                            <?=Form::label($forms['social_auth']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['adblock']['id'], __('Adblock Detection'), array('class'=>'control-label', 'for'=>$forms['adblock']['id']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['adblock']['key'], 1, (bool) $forms['adblock']['value'], array('id' => $forms['adblock']['key'].'1'))?>
                            <?=Form::label($forms['adblock']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['adblock']['key'], 0, ! (bool) $forms['adblock']['value'], array('id' => $forms['adblock']['key'].'0'))?>
                            <?=Form::label($forms['adblock']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['subscriptions']['id'], __('Subscriptions / Memberships'), array('class'=>'control-label', 'for'=>$forms['subscriptions']['id']))?>
                        <a target="_blank" href="https://docs.yclas.com/membership-plans/">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['subscriptions']['key'], 1, (bool) $forms['subscriptions']['value'], array('id' => $forms['subscriptions']['key'].'1'))?>
                            <?=Form::label($forms['subscriptions']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['subscriptions']['key'], 0, ! (bool) $forms['subscriptions']['value'], array('id' => $forms['subscriptions']['key'].'0'))?>
                            <?=Form::label($forms['subscriptions']['key'].'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <?=FORM::label($forms['add_to_home_screen']['id'], __('Show the Add to Home Screen dialog'), array('class'=>'control-label', 'for'=>$forms['add_to_home_screen']['id']))?>
                        <div class="radio radio-primary">
                            <?=Form::radio($forms['add_to_home_screen']['key'], 1, (bool) $forms['add_to_home_screen']['value'], array('id' => $forms['add_to_home_screen']['key'].'1'))?>
                            <?=Form::label($forms['add_to_home_screen']['key'].'1', __('Enabled'))?>
                            <?=Form::radio($forms['add_to_home_screen']['key'], 0, ! (bool) $forms['add_to_home_screen']['value'], array('id' => $forms['add_to_home_screen']['key'].'0'))?>
                            <?=Form::label($forms['add_to_home_screen']['key'].'0', __('Disabled'))?>
                        </div>
                        <span class="help-block">
                            <?=__("Show the Add to Home Screen dialog on Android devices with Chrome browser.")?>
                        </span>
                    </div>

                    <hr>
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'general'))))?>
                </div>
            </div>
        </form>
    </div>
</div>
