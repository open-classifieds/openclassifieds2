<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>

<div class="page-header">
    <h1 class="text-xl font-semibold">
        <?if ($msg_thread->id_ad !== NULL):?>
            <?=$msg_thread->ad->title?>
        <?else:?>
            <?=sprintf(__('Direct message from %s to %s'), $msg_thread->from->name, $msg_thread->to->name);   ?>
        <?endif?>
    </h1>
</div>
<div class="flex flex-wrap justify-start">
    <div class="relative inline-flex align-middle pull-right">
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_ARCHIVED?>"
            class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-yellow-100 bg-yellow-500 hover:bg-yellow-400"
            data-toggle="confirmation"
            data-text="<?=__('Are you sure you want to archive this message?')?>"
            data-btnOkLabel="<?=__('Yes, definitely!')?>"
            data-btnCancelLabel="<?=__('No way!')?>"
        >
        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> <?=_e('Archive')?>
        </a>
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_SPAM?>"
            class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-yellow-100 bg-yellow-500 hover:bg-yellow-400"
            data-toggle="confirmation"
            data-text="<?=__('Are you sure you want to mark it as Spam?')?>"
            data-btnOkLabel="<?=__('Yes, definitely!')?>"
            data-btnCancelLabel="<?=__('No way!')?>"
        >
            <span class="glyphicon glyphicon-fire" aria-hidden="true"></span> <?=_e('Spam')?>
        </a>
        <a
            href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'status','id'=>$msg_thread->id_message))?>?status=<?=Model_Message::STATUS_DELETED?>"
            class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400"
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
    <table class="w-full max-w-full mb-4 bg-transparent table-striped">
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
                            <div class="relative px-3 py-3 mb-4 border rounded text-red-800 border-red-600 bg-red-200" role="alert">
                                <p><?=_e('Some errors were encountered, please check the details you entered.')?></p>
                                <ul class="list-disc pl-4">
                                    <?php foreach ($errors as $message): ?>
                                        <li><?php echo $message ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>
                        <div class="mb-4 control-group">
                            <div class="md:w-full pr-4 pl-4">
                                <textarea name="message" rows="10" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded input-xxlarge disable-bbcode" data-editor="html" required><?=core::post('message')?></textarea>
                            </div>
                        </div>
                        <?=Form::token('reply_message')?>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default"><?=_e('Cancel')?></a>
                        <button type="submit" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=_e('Reply')?></button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div><!--//panel-->
