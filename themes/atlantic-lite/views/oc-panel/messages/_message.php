<tr
    class="message <?=($message->status_to == Model_Message::STATUS_NOTREAD AND $message->id_user_from != $user->id_user) ? 'tw-font-bold' : NULL?>"
    data-url="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>($message->id_message_parent != NULL) ? $message->id_message_parent : $message->id_message))?>"
>
    <td>
        <p>
            <? if(isset($message->ad->title)): ?>
                <?= $message->ad->title ?>
            <? else: ?>
                <?= _e('Direct Message') ?>
            <? endif ?>
            <? if ($message->status_to == Model_Message::STATUS_NOTREAD AND $message->id_user_from != $user->id_user) : ?>
                <span class="badge badge-warning"><?=_e('Unread')?></span>
            <? endif ?>
            <br>
            <a href="<?=Route::url('profile', array('seoname' => $message->from->seoname))?>"><?=$message->from->name?></a>
        </p>
    </td>
    <td>
        <?=$message->parent->created?>
    </td>
    <td>
        <?= empty($message->parent->read_date) ? _e('None') : $message->created ?>
    </td>
    <td class="text-right">
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>($message->id_message_parent != NULL) ? $message->id_message_parent : $message->id_message))?>"
            class="btn btn-sm <?=($message->status_to == Model_Message::STATUS_NOTREAD AND $message->id_user_from != $user->id_user) ? 'btn-warning' : 'btn-secondary'?>"
        >
            <i class="fa fa-envelope"></i>
        </a>
    </td>
</tr>
