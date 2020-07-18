<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="mb-4">
    <h1 class="h2">
        <?if ($msg_thread->id_ad !== NULL):?>
            <?=$msg_thread->ad->title?>
        <?else:?>
            <?=sprintf(__('Direct message from %s to %s'), $msg_thread->from->name, $msg_thread->to->name);   ?>
        <?endif?>
    </h1>
</div>

<div class="btn-group mb-4">
    <a
        href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_ARCHIVED?>"
        class="btn btn-warning"
        data-toggle="confirmation"
        data-text="<?=__('Are you sure you want to archive this message?')?>"
        data-btnOkLabel="<?=__('Yes, definitely!')?>"
        data-btnCancelLabel="<?=__('No way!')?>"
    >
        <i class="fas fa-archive" aria-hidden="true"></i> <?=_e('Archive')?>
    </a>

    <a
        href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_SPAM?>"
        class="btn btn-warning"
        data-toggle="confirmation"
        data-text="<?=__('Are you sure you want to mark it as Spam?')?>"
        data-btnOkLabel="<?=__('Yes, definitely!')?>"
        data-btnCancelLabel="<?=__('No way!')?>"
    >
        <i class="fas fa-fire" aria-hidden="true"></i> <?=_e('Spam')?>
    </a>

    <a
        href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_DELETED?>"
        class="btn btn-danger"
        data-toggle="confirmation"
        data-text="<?=__('Are you sure you want to delete?')?>"
        data-btnOkLabel="<?=__('Yes, definitely!')?>"
        data-btnCancelLabel="<?=__('No way!')?>"
    >
        <i class="fas fa-minus-circle"></i> <?=_e('Delete')?>
    </a>
</div>

<table class="table table-striped">
    <tbody>
        <?foreach ($messages as $message):?>
            <?= View::factory('oc-panel/messages/_thread-message', compact('message')) ?>
        <?endforeach?>

        <tr>
            <td style="width: 12%;" class="text-center">
                <img
                    src="<?= $user->get_profile_image() ?>"
                    class="tw-rounded-full tw-h-12 tw-w-12"
                    title="<?= HTML::chars($user->name) ?>"
                >
            </td>
            <td>
                <?= View::factory('oc-panel/messages/_form', ['errors' => isset($errors) ? $errors : NULL]) ?>
            </td>
        </tr>
    </tbody>
</table>
