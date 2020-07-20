<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=$forum->name?></h1>

            <p>
                <?=Text::bb2html($forum->description,TRUE)?>
            </p>
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

        <div class="card">
            <div class="list-group list-group-flush">
                <?foreach($topics as $topic):?>
                    <?
                        //amount answers a topic got
                        $replies = ($topic->count_replies > 0) ? $topic->count_replies : 0;
                        $page = '';
                        //lets drive the user to the last page
                        if ($replies > 0)
                        {
                            $last_page = round($replies/Controller_Forum::$items_per_page,0);
                            $page = ($last_page>0) ? '?page=' . $last_page : '';
                        }

                    ?>
                    <a class="list-group-item list-group-item-action" href="<?=Route::url('forum-topic', array('forum'=>$forum->seoname,'seotitle'=>$topic->seotitle))?><?=$page?>" title="<?=HTML::chars($topic->title)?>">
                        <div class="media">
                            <div class="media-body">
                                <div>
                                    <h5 class="h6 mb-1"><?= $topic->title ?></h5>
                                    <ul class="list-inline text-small text-muted mb-0">
                                        <li>
                                            <small><i class="far fa-comment-alt"></i> <?= $replies ?></small>
                                        </li>
                                        <li>
                                            <small><?=_e('Created')?>: <?=Date::format($topic->created, core::config('general.date_format'))?></small>
                                        </li>
                                        <li>
                                            <small><?=_e('Last Message')?>: <?=Date::format($topic->last_message, core::config('general.date_format'))?></small>
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
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
