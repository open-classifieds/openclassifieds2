<?php defined('SYSPATH') or die('No direct script access.');?>

<h1 class="page-header page-title">
    <?=__('Social Authentication Settings')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-login-using-social-auth-facebook-google-twitter/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
    <p>
        <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-teal-100 bg-teal-500 hover:bg-teal-400" href="<?=Route::url('oc-panel',array('controller'=>'social','action'=>'oauth2'))?>">
            OAuth 2.0 Config
        </a>
    </p>
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
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'social', 'action'=>'index')), array('class'=>'ajax-load', 'enctype'=>'multipart/form-data'))?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="mb-4">
                        <?=FORM::label('debug_mode', __('Debug Mode'), array('class'=>'control-label', 'for'=>'debug_mode'))?>
                        <div class="radio radio-primary">
                            <?=Form::radio('debug_mode', 1, (bool) $config['debug_mode'], array('id' => 'debug_mode'.'1'))?>
                            <?=Form::label('debug_mode'.'1', __('Enabled'))?>
                            <?=Form::radio('debug_mode', 0, ! (bool) $config['debug_mode'], array('id' => 'debug_mode'.'0'))?>
                            <?=Form::label('debug_mode'.'0', __('Disabled'))?>
                        </div>
                    </div>

                    <hr>
                    <?=FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'index'))))?>
                </div>
            </div>

            <?foreach ($config['providers'] as $api => $options):?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4><?=$api?></h4>
                        <hr>

                        <div class="mb-4">
                            <div class="radio radio-primary">
                                <?=Form::radio($api, 1, (bool) $options['enabled'], array('id' => $api.'1'))?>
                                <?=Form::label($api.'1', __('Enabled'))?>
                                <?=Form::radio($api, 0, ! (bool) $options['enabled'], array('id' => $api.'0'))?>
                                <?=Form::label($api.'0', __('Disabled'))?>
                            </div>
                        </div>

                        <?if(isset($options['keys']['id'])):?>
                            <div class="mb-4">
                                <?=FORM::label($api.'_id_label', __('Id'), array('class'=>'control-label', 'for'=>$api))?>
                                <?=FORM::input($api.'_id', $options['keys']['id']);?>
                            </div>
                        <?endif?>

                        <?if(isset($options['keys']['key'])):?>
                            <div class="mb-4">
                                <?=FORM::label($api.'_key_label', __('Key'), array('class'=>'control-label', 'for'=>$api))?>
                                <?=FORM::input($api.'_key', $options['keys']['key']);?>
                            </div>
                        <?endif?>

                        <?if(isset($options['keys']['secret'])):?>
                            <div class="mb-4">
                                <?=FORM::label($api.'_secret_label', __('secret'), array('class'=>'control-label', 'for'=>$api))?>
                                <?=FORM::input($api.'_secret', $options['keys']['secret']);?>
                            </div>
                        <?endif?>

                        <hr>
                        <?=FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'social', 'action'=>'index'))))?>
                    </div>
                </div>
            <?endforeach?>
        <?FORM::close()?>
    </div>
</div>
