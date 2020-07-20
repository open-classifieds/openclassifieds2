<a title="<?=HTML::chars($forum['name'])?>" href="<?= Route::url('forum-list', array('forum'=>$forum['seoname']))?>" class="list-group-item list-group-item-action <?= (isset($isSubForum) AND $isSubForum) ? 'pl-5' : '' ?>">
    <div class="media">
       <div class="media-body">
            <div>
                <h5 class="h6 mb-1"><?= $forum['name'] ?></h5>
                <ul class="list-inline text-muted mb-0">
                    <li class="list-inline-item">
                        <small><i class="far fa-comment-alt"></i> <?=number_format($forum['count'])?></small>
                    </li>
                    <li class="list-inline-item">
                        <small><?=_e('Last Message')?>: <?=(isset($forum['last_message'])?Date::format($forum['last_message'], core::config('general.date_format')):'')?></small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</a>
