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

    <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>" class="btn btn-light"><?=_e('Cancel')?></a>

    <button type="submit" class="btn btn-primary"><?=_e('Reply')?></button>
</form>
