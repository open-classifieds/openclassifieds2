<?php defined('SYSPATH') or die('No direct script access.');?>

<h1 class="page-header page-title">
    OAuth2 <?=__('Settings')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-login-using-social-auth-facebook-google-twitter/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
<hr>

<?if (Core::extra_features() == FALSE):?>
    <div class="alert alert-info fade in">
        <p>
            <strong><?=__('Heads Up!')?></strong>
            <?=__('Social authentication is only available in the PRO version!').' '.__('Upgrade your Yclas site to activate this feature.')?>
        </p>
        <p>
            <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-teal-100 bg-teal-500 hover:bg-teal-400" href="https://yclas.com/self-hosted.html">
                <?=__('Upgrade to PRO now')?>
            </a>
        </p>
    </div>
<?endif?>

<div class="flex flex-wrap">
    <div class="md:w-full pr-4 pl-4">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'social', 'action'=>'oauth2')), array('class'=>'ajax-load', 'enctype'=>'multipart/form-data'))?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>OAuth2</h4>
                    <hr>

                    <div class="mb-4">
                        <div class="radio radio-primary">
                            <?=Form::radio('oauth2_enabled', 1, (bool) $oauth['oauth2_enabled'], array('id' => 'oauth2'.'1'))?>
                            <?=Form::label('oauth2'.'1', __('Enabled'))?>
                            <?=Form::radio('oauth2_enabled', 0, ! (bool) $oauth['oauth2_enabled'], array('id' => 'oauth2'.'0'))?>
                            <?=Form::label('oauth2'.'0', __('Disabled'))?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <?=FORM::label('oauth2_client_id_label','Client ID', array('class'=>'control-label', 'for'=>'oauth2'))?>
                        <?=FORM::input('oauth2_client_id', $oauth['oauth2_client_id']);?>
                    </div>

                    <div class="mb-4">
                        <?=FORM::label('oauth2_client_secret_label','Client Secret', array('class'=>'control-label', 'for'=>'oauth2'))?>
                        <?=FORM::input('oauth2_client_secret', $oauth['oauth2_client_secret']);?>
                    </div>

                    <div class="mb-4">
                        <?=FORM::label('oauth2_url_authorize','url authorize', array('class'=>'control-label', 'for'=>'oauth2'))?>
                        <?=FORM::input('oauth2_url_authorize', $oauth['oauth2_url_authorize']);?>
                    </div>

                    <div class="mb-4">
                        <?=FORM::label('oauth2_url_access_token','url access token', array('class'=>'control-label', 'for'=>'oauth2'))?>
                        <?=FORM::input('oauth2_url_access_token', $oauth['oauth2_url_access_token']);?>
                    </div>

                    <div class="mb-4">
                        <?=FORM::label('oauth2_url_resource_owner_details','url resource owner details', array('class'=>'control-label', 'for'=>'oauth2'))?>
                        <?=FORM::input('oauth2_url_resource_owner_details', $oauth['oauth2_url_resource_owner_details']);?>
                    </div>

                    <hr>
                    <?=FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'oauth2'))))?>
                </div>
            </div>
        <?FORM::close()?>
    </div>
</div>
