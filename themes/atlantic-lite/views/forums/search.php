<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=__('Search')?> <?=HTML::chars(core::get('search'))?></h1>
        </div>

        <div class="mb-4">
            <?if (!Auth::instance()->logged_in()):?>
                <a class="btn btn-success" data-toggle="modal" data-dismiss="modal"
                    href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
                    <?=_e('New Topic')?>
                </a>
            <?else:?>
                <a class="btn btn-success" href="<?=Route::url('forum-new')?>">
                    <?=_e('New Topic')?>
                </a>
            <?endif?>

            <?=View::factory('forums/_search-form')?>
        </div>

        <?if ($topics->count()>0):?>
            <div class="card">
                <div class="list-group list-group-flush">
                    <?foreach($topics as $topic):?>
                        <?
                            if (is_numeric($topic->id_post_parent))
                            {
                                $title      = $topic->parent->title;
                                $seotitle   = $topic->parent->seotitle;
                            }
                            else
                            {
                                $title      = $topic->title;
                                $seotitle   = $topic->seotitle;
                            }
                        ?>
                        <a class="list-group-item list-group-item-action" href="<?=Route::url('forum-topic', array('forum'=>$topic->forum->seoname,'seotitle'=>$seotitle))?>" title="<?=HTML::chars($title)?>">
                            <div class="media">
                                <div class="media-body">
                                    <div>
                                        <h5 class="h6 mb-1"><?= $topic->title ?></h5>
                                        <ul class="list-inline text-small text-muted mb-0">
                                            <li>
                                                <small><?=_e('Created')?>: <?=Date::format($topic->created, core::config('general.date_format'))?></small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?if (Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()):?>
                            <div class="list-group-item">
                                <a href="<?=Route::url('oc-panel', array('controller'=> 'topic', 'action'=>'update','id'=>$topic->id_post)) ?>" class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        <?endif?>
                    <?endforeach?>
                </div>
            </div>

            <?=$pagination?>
        <? else : ?>
            <div class="jumbotron text-center">
                <h2 class="h3"><?=__('Nothing found, sorry!')?></h2>
                <p class="lead"><?=__('You can try a new search or publish a new topic ;)')?></p>
            </div>
        <? endif ?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
