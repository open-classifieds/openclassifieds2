<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="page-header">
    <h1>
        <?if ($msg_thread->id_ad !== NULL):?>
            <?=$msg_thread->ad->title?>
        <?else:?>
            <?=sprintf(__('Direct message from %s to %s'), $msg_thread->from->name, $msg_thread->to->name);   ?>
        <?endif?>
    </h1>
</div>
<div class="btn-toolbar">
    <div class="btn-group pull-right">
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_ARCHIVED?>"
            class="btn btn-warning"
            data-toggle="confirmation"
            data-text="<?=__('Are you sure you want to archive this message?')?>"
            data-btnOkLabel="<?=__('Yes, definitely!')?>"
            data-btnCancelLabel="<?=__('No way!')?>"
        >
        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> <?=_e('Archive')?>
        </a>
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_SPAM?>"
            class="btn btn-warning"
            data-toggle="confirmation"
            data-text="<?=__('Are you sure you want to mark it as Spam?')?>"
            data-btnOkLabel="<?=__('Yes, definitely!')?>"
            data-btnCancelLabel="<?=__('No way!')?>"
        >
            <span class="glyphicon glyphicon-fire" aria-hidden="true"></span> <?=_e('Spam')?>
        </a>
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_DELETED?>"
            class="btn btn-danger"
            data-toggle="confirmation"
            data-text="<?=__('Are you sure you want to delete?')?>"
            data-btnOkLabel="<?=__('Yes, definitely!')?>"
            data-btnCancelLabel="<?=__('No way!')?>"
        >
            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=_e('Delete')?>
        </a>
    </div>
</div>
<br>
<div class="panel">
    <table class="table table-striped">
        <tbody>
            <?foreach ($messages as $message):?>
                <tr>
                    <td class="text-center">
                        <strong><a href="<?=Route::url('profile', array('seoname' => $message->from->seoname))?>"><?=$message->from->name?></a></strong>
                    </td>
                    <td>
                        <em>
                            <?=Date::fuzzy_span(Date::mysql2unix($message->created))?>
                             -
                            <?=$message->created?>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td style="width: 12%;" class="text-center">
                        <img src="<?=$message->from->get_profile_image()?>" class="img-rounded" width="50" height="50" title="<?=HTML::chars($message->from->name)?>">
                    </td>
                    <td>
                        <p class="<?=HTML::chars($message->from->name)?>"><?=Text::bb2html($message->message,TRUE)?></p>
                        <?if ($message->price > 0):?>
                            <p>
                                <strong><?=_e('Price')?></strong>: <?=i18n::money_format($message->price)?>
                            </p>
                        <?endif?>
                    </td>
                </tr>
            <?endforeach?>
            <tr>
                <td style="width: 12%;" class="text-center">
                    <img src="<?=$user->get_profile_image()?>" class="img-rounded" width="50" height="50" title="<?=HTML::chars($user->name)?>">
                </td>
                <td>
                    <form class="form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>Request::current()->param('id')))?>">
                        <?php if (isset($errors)): ?>
                            <div class="alert alert-danger" role="alert">
                                <p><?=_e('Some errors were encountered, please check the details you entered.')?></p>
                                <ul>
                                    <?php foreach ($errors as $message): ?>
                                        <li><?php echo $message ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>
                        <div class="form-group control-group">
                            <div class="col-md-12">
                                <textarea name="message" rows="10" class="form-control input-xxlarge disable-bbcode" data-editor="html" required><?=core::post('message')?></textarea>
                            </div>
                        </div>
                        <?=Form::token('reply_message')?>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>" class="btn btn-default"><?=_e('Cancel')?></a>
                                <button type="submit" class="btn btn-primary"><?=_e('Reply')?></button>
                            </div>
                            <? if (core::config('general.custom_orders') AND $msg_thread->id_ad !== NULL AND $msg_thread->ad->id_user === $user->id_user):?>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#customOrderFormModal"><?=_e('Create custom order')?></button>
                                </div>
                            <? endif ?>
                        </div>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div><!--//panel-->

<div class="modal fade" id="customOrderFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <?= FORM::open(Route::url('oc-panel', ['controller' => 'messages', 'action' => 'custom_order', 'id' => Request::current()->param('id')])) ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?=__('Create custom order')?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="description"><?=__('Description')?></label>
                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            required
                        ><?= Core::post('description') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="amount"><?=__('Amount')?></label>
                        <input
                            name="amount"
                            type="text"
                            class="form-control"
                            id="amount"
                            placeholder="<?= i18n::format_currency(0, core::config('payment.paypal_currency')) ?>"
                            required
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button>
                </div>
            <?= FORM::close() ?>
        </div>
    </div>
</div>
