<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-userlocation mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <?= FORM::open(Route::url('profiles'), ['method'=>'GET']) ?>
            <div class="form-group">
                <?= Form::label('search', _e('Search'), ['for' => 'search']) ?>
                <?= Form::input('search', '', [
                    'placeholder' => __('Search'),
                    'class' => 'form-control',
                    'id' => 'search',
                ])?>
            </div>

            <?if (Core::extra_features() == TRUE) :?>
                <? if($widget->custom != FALSE): ?>
                    <? foreach($widget->custom_fields as $name=>$field): ?>
                        <? if(isset($field['searchable']) AND $field['searchable']): ?>
                            <div class="form-group">
                                <? $cf_name = 'cf_'.$name ?>
                                <?
                                    if($field['type'] == 'select' OR $field['type'] == 'radio')
                                    {
                                        $select = ['' => $field['label']];

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
                                <?= Form::label('cf_' . $name, $field['label'], ['for' => 'cf_'.$name])?>
                                <?= Form::cf_form_field('cf_'.$name, [
                                    'display' => $field['type'],
                                    'label' => $field['label'],
                                    'tooltip' => isset($field['tooltip']) ? $field['tooltip'] : '',
                                    'default' => $field['values'],
                                    'options' => (!is_array($field['values'])) ? $field['values'] : $select,
                                ], Core::get('cf_' . $name), FALSE, TRUE) ?>
                            </div>
                        <?endif?>
                    <?endforeach?>
                <?endif?>
            <?endif?>

            <?= FORM::button('submit', __('Search'), [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ])?>
        <?= FORM::close()?>
    </div>
</div>
