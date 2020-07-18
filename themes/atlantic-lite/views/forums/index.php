<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <div class="mb-3">
            <h1 class="h2"><?=_e('Forums')?></h1>
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
                <?foreach($forums as $forum):?>
                    <?if($forum['id_forum_parent'] == 0):?>
                        <?=View::factory('forums/_forum', compact('forum'))?>

                        <?foreach($forums as $subForum):?>
                            <?if($subForum['id_forum_parent'] == $forum['id_forum']):?>
                                <?=View::factory('forums/_forum', ['forum' => $subForum, 'isSubForum' => TRUE])?>
                            <?endif?>
                        <?endforeach?>
                    <?endif?>
                <?endforeach?>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
