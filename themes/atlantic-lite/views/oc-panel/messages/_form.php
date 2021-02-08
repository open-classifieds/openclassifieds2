<form
    method="post"
    action="<?= Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>Request::current()->param('id'))) ?>"
>
    <? if ($errors): ?>
        <div class="alert alert-danger" role="alert">
            <p><?=_e('Some errors were encountered, please check the details you entered.')?></p>
            <ul>
                <? foreach ($errors as $message) : ?>
                    <li><?= $message ?></li>
                <? endforeach ?>
            </ul>
        </div>
    <? endif ?>

    <div class="form-group">
        <textarea
            name="message"
            rows="10"
            class="form-control disable-bbcode"
            data-editor="html"
            required
        ><?= Core::post('message') ?></textarea>
    </div>

    <?=Form::token('reply_message')?>

    <div class="tw-flex tw-justify-between">
        <div>
            <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>" class="btn btn-light"><?=_e('Cancel')?></a>

            <button type="submit" class="btn btn-primary"><?=_e('Reply')?></button>
        </div>

        <? if (core::config('general.custom_orders') AND $msg_thread->id_ad !== NULL AND $msg_thread->ad->id_user === $user->id_user):?>
            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#customOrderFormModal"><?=_e('Create custom order')?></button>
        <? endif ?>
    </div>
</form>
