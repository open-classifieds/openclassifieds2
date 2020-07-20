<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card mb-4">
    <div class="card-body">
        <?= FORM::open(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'edit']), ['method' => 'post', 'class' => '', 'enctype'=>'multipart/form-data']) ?>
            <h5 class="card-title"><?=_e('Edit Profile')?></h5>

            <div class="form-group">
                <?= Form::label('edit-profile-name', _e('Name')) ?>
                <?= Form::input('name', $user->name, [
                    'type' => 'text',
                    'id' => 'edit-profile-name',
                    'class' => 'form-control',
                    'placeholder' => __('Name'),
                    'required',
                ]) ?>
            </div>

            <div class="form-group">
                <?= Form::label('edit-profile-email', _e('Email')) ?>
                <?= Form::input('email', $user->email, [
                    'type' => 'email',
                    'id' => 'edit-profile-email',
                    'class' => 'form-control',
                    'placeholder' => __('Email'),
                    'required'
                ]) ?>
            </div>

            <div class="form-group">
                <?if (core::config('general.sms_auth')==TRUE):?>
                    <?= Form::label('edit-profile-phone', _e('Mobile phone number')) ?>
                    <?= Form::input('phone', $user->phone, [
                        'type' => 'text',
                        'id' => 'edit-profile-phone',
                        'class' => 'form-control',
                        'placeholder' => __('Phone'),
                        'data-country' => core::config('general.country'),
                        'required',
                    ]) ?>
                <?else:?>
                    <?= Form::label('edit-profile-phone', _e('Phone')) ?>
                    <?= Form::input('phone', $user->phone, [
                        'type' => 'text',
                        'id' => 'edit-profile-phone',
                        'class' => 'form-control',
                        'placeholder' => __('Phone'),
                    ]) ?>
                <?endif?>
            </div>

            <?if (core::config('advertisement.location')): ?>
                <div class="form-group">
                    <?= Form::label('edit-profile-location', _e('Location')) ?>
                    <div id="location-chained" <?=($id_location === NULL) ? NULL : 'hidden'?> data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                        <div id="select-location-template" hidden>
                            <select class="disable-select2 select-location" placeholder="<?=__('Pick a location...')?>"></select>
                        </div>
                    </div>
                    <? if($id_location !== NULL): ?>
                        <div id="location-edit">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="<?=$selected_location->name?>" disabled>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><?=_e('Select another')?></button>
                                </span>
                            </div>
                        </div>
                    <? endif ?>
                    <input id="location-selected" name="location" value="<?=$id_location?>" class="form-control invisible" style="height: 0; padding:0; width:1px; border:0;"></input>
                </div>
            <? endif ?>

            <div class="form-group">
                <?= Form::label('edit-profile-address', _e('Address')) ?>
                <? if(core::config('advertisement.map_pub_new')): ?>
                    <?if (Core::is_HTTPS()):?>
                        <div class="input-group">
                            <?= Form::input('address', $user->address, [
                                'type' => 'text',
                                'id' => 'edit-profile-address',
                                'class' => 'form-control',
                                'placeholder' => __('Address'),
                            ]) ?>
                            <span class="input-group-btn">
                                <button class="btn btn-default locateme" type="button"><?=_e('Locate me')?></button>
                            </span>
                        </div>
                    <? else: ?>
                        <?= Form::input('address', $user->address, [
                            'type' => 'text',
                            'id' => 'edit-profile-address',
                            'class' => 'form-control',
                            'placeholder' => __('Address'),
                        ]) ?>
                    <? endif ?>
                <? else: ?>
                    <?= Form::input('address', $user->address, [
                        'type' => 'text',
                        'id' => 'edit-profile-address',
                        'class' => 'form-control',
                        'placeholder' => __('Address'),
                    ]) ?>
                <? endif ?>
            </div>

            <div class="form-group">
                <?= Form::label('edit-profile-description', _e('Description')) ?>
                <?= Form::textarea('description', $user->description, [
                    'rows' => 3,
                    'cols' => 50,
                    'id' => 'edit-profile-description',
                    'class' => 'form-control',
                ]) ?>
            </div>

            <? foreach($custom_fields as $name=>$field): ?>
                <? if($name != 'whatsapp' AND ($name != 'verifiedbadge' OR Auth::instance()->get_user()->is_admin() OR Auth::instance()->get_user()->is_moderator())): ?>
                    <div class="form-group">
                        <? $cf_name = 'cf_'.$name ?>
                        <?
                            if($field['type'] == 'select' OR $field['type'] == 'radio')
                            {
                                $select = ['' => ''];

                                foreach ($field['values'] as $select_name)
                                {
                                    $select[$select_name] = $select_name;
                                }
                            }
                            else
                            {
                                $select = $field['values'];
                            }
                        ?>
                        <?= Form::label('cf-edit-profile-' . $name, $field['label']) ?>
                        <?= Form::cf_form_field('cf_'.$name, [
                            'display' => $field['type'],
                            'label' => $field['label'],
                            'tooltip' => (isset($field['tooltip'])) ? $field['tooltip'] : '',
                            'default' => $user->$cf_name,
                            'options' => (!is_array($field['values'])) ? $field['values'] : $select,
                            'required' => $field['required'],
                        ]) ?>
                    </div>
                <? elseif($name == 'whatsapp'): ?>
                    <div class="form-group">
                        <?= Form::label('edit-profile-whatsapp', _e('Whatsapp Number')) ?>
                        <?= Form::input('cf_whatsapp', $user->cf_whatsapp, [
                            'type' => 'text',
                            'id' => 'edit-profile-whatsapp',
                            'class' => 'form-control',
                            'placeholder' => __('Address'),
                        ]) ?>
                        <div class="col-sm-8">
                            <input id="cf_whatsapp" name="cf_whatsapp" title="" class="form-control cf_string_fields data-custom  " placeholder="whatsapp" data-placeholder="whatsapp" data-original-title="whatsapp" type="text"
                            data-country-code="<?= (core::config('general.country') !== NULL and isset(I18n::country_codes()[core::config('general.country')])) ? I18n::country_codes()[core::config('general.country')] : '' ?>"
                            value="<?=$user->cf_whatsapp?>"
                            >
                        </div>
                    </div>
                <? endif ?>
            <? endforeach ?>

            <div class="form-check">
                <?= Form::checkbox('subscriber', 1, (bool) $user->subscriber, [
                    'id' => 'edit-profile-subscriber',
                    'class' => 'form-check-input',
                ]) ?>
                <?= Form::label('edit-profile-subscriber', _e('Subscribed to emails')) ?>
            </div>

            <div class="mt-3">
                <?= Form::button('profile-submit', _e('Update'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
            </div>

        <?= FORM::close() ?>
    </div>
</div>
