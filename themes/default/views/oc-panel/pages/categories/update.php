<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="page-header" id="crud-<?=$name?>">
    <h1><?=__('Update')?> <?=Text::ucfirst(__($name))?></h1>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Category details')?></h3>
            </div>
            <div class="panel-body">
                  <?=$form->render()?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <? if (Core::config('general.multilingual')) : ?>
            <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?= Route::url('oc-panel', array('controller' => 'category', 'action' => 'update_translations', 'id' => $form->object->id_category)) ?>">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= __('Translations') ?></h3>
                    </div>
                    <? foreach (i18n::get_selectable_languages() as $locale => $language) : ?>
                        <? if (Core::config('i18n.locale') != $locale) : ?>
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" href="#<?= $locale ?>" aria-expanded="false" aria-controls="<?= $locale ?>">
                                        <small class="fa fa-chevron-down pull-right"></small>
                                        <?= $locale ?>
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-body collapse" id="<?= $locale ?>">
                                <div class="form-group">
                                    <?= FORM::label('translations_name_' . $locale, _e('Name'), array('class' => 'col-xs-12 control-label', 'for' => 'translations_name_' . $locale)) ?>
                                    <div class="col-sm-12">
                                        <?= FORM::input('translations[name][' . $locale . ']', $category->translate_name($locale), array(
                                            'placeholder' => '',
                                            'rows' => 3, 'cols' => 50,
                                            'class' => 'form-control',
                                            'id' => 'translations_name_' . $locale,
                                        )) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= FORM::label('translations_description_' . $locale, _e('Description'), array('class' => 'col-xs-12 control-label', 'for' => 'translations_description_' . $locale)) ?>
                                    <div class="col-sm-12">
                                        <?= FORM::textarea('translations[description][' . $locale . ']', $category->translate_description($locale), array(
                                            'placeholder' => '',
                                            'rows' => 3, 'cols' => 50,
                                            'class' => 'form-control',
                                            'id' => 'translations_description_' . $locale,
                                        )) ?>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><?= __('Submit translations') ?></button>
                            </div>
                        <? endif ?>
                    <? endforeach ?>
                </div>
            </form>
        <? endif ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?=__('Upload category icon')?></h3>
            </div>
            <div class="panel-body">
                <?if (( $icon_src = $category->get_icon() )!==FALSE ):?>
                    <div class="row">
                        <div class="col-md-4">
                            <a class="thumbnail">
                                <img src="<?=$icon_src?>" class="img-rounded" alt="<?=__('Category icon')?>" height='200px'>
                            </a>
                        </div>
                    </div>
                <?endif?>
                <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'icon','id'=>$form->object->id_category))?>">
                    <?=Form::errors()?>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <?= FORM::label('category_icon', __('Select from files'), array('for'=>'category_icon'))?>
                            <input type="file" name="category_icon" class="form-control" id="category_icon" />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button>
                    <?if (( $icon_src = $category->get_icon() )!==FALSE ):?>
                        <button type="submit"
                            class="btn btn-danger index-delete index-delete-inline"
                             onclick="return confirm('<?=__('Delete icon?')?>');"
                             type="submit"
                             name="icon_delete"
                             value="1"
                             title="<?=__('Delete icon')?>">
                            <?=__('Delete icon')?>
                        </button>
                    <?endif?>
                </form>
            </div>
        </div>
        <?if (Core::extra_features() == TRUE):?>
                <div class="panel panel-default">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=__('Custom Fields')?></h3>
                        </div>
                        <div class="panel-body">
                            <p class="<?=(core::count($category_fields) > 0) ? NULL : 'hidden'?>" data-added-label><strong><?=__('Added Custom Fields')?></strong></p>
                            <ol class='plholder' id="ol_1" data-id="1">
                                <?foreach($category_fields as $name=>$field):?>
                                    <li class="inactive" data-id="<?=$name?>" id="li_<?=$name?>">
                                        <div class="drag-item">
                                            <div class="drag-name">
                                                <i class="text-success fa fa-check"></i>
                                                <?=$name?>
                                                <span class="label label-info "><?=$field['type']?></span>
                                                <span class="label label-info "><?=($field['searchable'])?__('searchable'):NULL?></span>
                                                <span class="label label-info "><?=($field['required'])?__('required'):NULL?></span>
                                                <span class="label label-info "><?=(isset($field['admin_privilege']) AND $field['admin_privilege'])?__('Only Admin'):NULL?></span>
                                                <span class="label label-info "><?=(isset($field['show_listing']) AND $field['show_listing'])?__('Show listing'):NULL?></span>
                                            </div>
                                            <a
                                                href="<?=Route::url('oc-panel', array('controller'=>'fields', 'action'=>'remove_category','id'=>$name))?>?id_category=<?=$category->id_category?>"
                                                class="drag-action index-delete"
                                                title="<?=__('Are you sure you want to remove it?')?>"
                                                data-id="li_<?=$name?>"
                                                data-placement="left"
                                                data-add-url="<?=Route::url('oc-panel', array('controller'=>'fields', 'action'=>'add_category','id'=>$name))?>?id_category=<?=$category->id_category?>"
                                                data-remove-url="<?=Route::url('oc-panel', array('controller'=>'fields', 'action'=>'remove_category','id'=>$name))?>?id_category=<?=$category->id_category?>"
                                                data-add-title="<?=__('Add custom field')?>"
                                                data-remove-title="<?=__('Are you sure you want to remove it?')?>"
                                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                data-btnCancelLabel="<?=__('No way!')?>">
                                                <i class="fa fa-minus"></i>
                                            </a>
                                        </div>
                                    </li>
                                <?endforeach?>
                            </ol><!--ol_1-->
                            <hr>
                            <p class="<?=(core::count($selectable_fields) > 0) ? NULL : 'hidden'?>" data-add-label><strong><?=__('Add Custom Fields')?></strong></p>
                            <ol class='plholder' id="ol_2" data-id="2">
                                <?foreach($selectable_fields as $name=>$field):?>
                                    <li class="inactive" data-id="<?=$name?>" id="li_<?=$name?>">
                                        <div class="drag-item">
                                            <div class="drag-name">
                                                <?=$name?>
                                                <span class="label label-info "><?=$field['type']?></span>
                                                <span class="label label-info "><?=($field['searchable'])?__('searchable'):NULL?></span>
                                                <span class="label label-info "><?=($field['required'])?__('required'):NULL?></span>
                                                <span class="label label-info "><?=(isset($field['admin_privilege']) AND $field['admin_privilege'])?__('Only Admin'):NULL?></span>
                                                <span class="label label-info "><?=(isset($field['show_listing']) AND $field['show_listing'])?__('Show listing'):NULL?></span>
                                            </div>
                                            <a
                                                href="<?=Route::url('oc-panel', array('controller'=>'fields', 'action'=>'add_category','id'=>$name))?>?id_category=<?=$category->id_category?>"
                                                class="drag-action index-add"
                                                title="<?=__('Add custom field')?>"
                                                data-id="li_<?=$name?>"
                                                data-placement="left"
                                                data-add-url="<?=Route::url('oc-panel', array('controller'=>'fields', 'action'=>'add_category','id'=>$name))?>?id_category=<?=$category->id_category?>"
                                                data-remove-url="<?=Route::url('oc-panel', array('controller'=>'fields', 'action'=>'remove_category','id'=>$name))?>?id_category=<?=$category->id_category?>"
                                                data-add-title="<?=__('Add custom field')?>"
                                                data-remove-title="<?=__('Are you sure you want to remove it?')?>"
                                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                data-btnCancelLabel="<?=__('No way!')?>">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </li>
                                <?endforeach?>
                            </ol><!--ol_2-->
                            <br>
                            <p class="text-right">
                                <a class="btn btn-primary ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'new'))?>?id_category=<?=$category->id_category?>" title="<?=__('New field')?>">
                                    <?=__('New field')?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
        <?endif?>
    </div><!--end col-md-6-->
</div>