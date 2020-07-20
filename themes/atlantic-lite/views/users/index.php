<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=_e('Users')?></h1>
        </div>

        <?=Form::errors()?>

        <?= FORM::open(Route::url('profiles'), array('class'=>'form-row mb-4', 'method'=>'GET', 'action'=>''))?>
            <div class="form-group col-md-4">
                <?= FORM::label('user', _e('Name'), array('class'=>'', 'for'=>'user'))?>
                <input type="text" id="search" name="search" class="form-control" value="<?=core::request('search')?>" placeholder="<?=__('Search')?>">
            </div>

            <?if (Core::extra_features() == TRUE):?>
                <?foreach(Model_UserField::get_all() as $name=>$field):?>
                    <?if(isset($field['searchable']) AND $field['searchable']):?>
                        <div class="form-group col-md-4">
                            <?$cf_name = 'cf_'.$name?>
                            <?if($field['type'] == 'select' OR $field['type'] == 'radio') {
                                $select = array('' => $field['label']);
                                foreach ($field['values'] as $select_name) {
                                    $select[$select_name] = $select_name;
                                }
                            } else $select = $field['values']?>
                            <?= FORM::label('cfuser_'.$name, $field['label'], array('for'=>'cfuser_'.$name))?>
                            <div <?=($field['type']=='checkbox')?'class="text-center"':''?>>
                                <?=Form::cf_form_field('cf_'.$name, array(
                                'display'   => $field['type'],
                                'label'     => $field['label'],
                                'tooltip'   => (isset($field['tooltip']))? $field['tooltip'] : "",
                                'default'   => $field['values'],
                                'options'   => (!is_array($field['values']))? $field['values'] : $select,
                                ),core::request('cf_'.$name), FALSE, TRUE)?>
                            </div>
                        </div>
                    <?endif?>
                <?endforeach?>
            <?endif?>

            <div class="form-group col-md-2 tw-flex tw-items-end">
                <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary pull-right', 'action'=>Route::url('profiles')))?>
            </div>
        <?= FORM::close()?>

        <?if(core::count($users)):?>
            <div class="btn-toolbar justify-content-between mb-3" role="toolbar">
                <div class="btn-group">
                    <button type="button" id="sort" data-sort="<?=HTML::chars(core::request('sort'))?>" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-list"></i> <?=_e('Sort')?> <span class="caret"></span>
                    </button>

                    <div class="dropdown-menu" role="menu" id="sort-list">
                        <?if (Core::config('advertisement.reviews')==1):?>
                            <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'rating'] + Request::current()->query())?>"><?=_e('Rating')?></a>
                        <?endif?>
                        <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'name-asc'] + Request::current()->query())?>"><?=_e('Name (A-Z)')?></a>
                        <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'name-desc'] + Request::current()->query())?>"><?=_e('Name (Z-A)')?></a>
                        <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'created-desc'] + Request::current()->query())?>"><?=_e('Newest')?></a>
                        <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'created-asc'] + Request::current()->query())?>"><?=_e('Oldest')?></a>
                        <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'ads-desc'] + Request::current()->query())?>"><?=_e('More Ads')?></a>
                        <a class="dropdown-item" href="?<?=http_build_query(['sort' => 'ads-asc'] + Request::current()->query())?>"><?=_e('Less Ads')?></a>
                    </div>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3" id="users">
                <?$i = 1; foreach($users as $user ):?>
                    <div class="col mb-4">
                        <div class="card">
                            <img src="<?=Core::imagefly($user->get_profile_image(),250,250)?>" class="card-img-top" alt="<?=__('Profile Picture')?>">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a
                                        title="<?=HTML::chars($user->name)?>"
                                        href="<?=Route::url('profile',  array('seoname'=>$user->seoname))?>"
                                    ><?=$user->name?></a>
                                    <?=$user->is_verified_user()?>
                                    <small><span class="badge badge-secondary"><?=$user->ads_count?> <?=_e('Ads')?></span></small>
                                </h5>
                                <p class="card-text">
                                    <?for ($j=0; $j < round($user->rate,1); $j++):?>
                                        <i class="fa fa-star"></i>
                                    <?endfor?>
                                </p>
                                <p class="card-text"><?= Text::limit_chars(Text::removebbcode($user->description), 255, NULL, TRUE) ?></p>
                                <p class="card-text">
                                    <a title="<?=HTML::chars($user->name)?>" href="<?=Route::url('profile',  array('seoname'=>$user->seoname))?>" class="btn btn-primary btn-block" role="button"><?=_e('See profile')?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?$i++; endforeach?>
            </div>

            <?=$pagination?>
        <?elseif (core::count($users) == 0):?>
            <div class="jumbotron">
                <p class="lead text-center">
                    <?=_e('We do not have any users matching your search')?>.
                </p>
            </div>
        <?endif?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
