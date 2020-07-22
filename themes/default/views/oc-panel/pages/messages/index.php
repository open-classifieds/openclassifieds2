<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<div class="page-header">
    <h1 class="text-xl font-semibold"><?=_e('Inbox')?></h1>
</div>
<div class="flex flex-wrap justify-start">
    <div class="relative inline-flex align-middle">
        <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  <?=(!is_numeric(Core::get('status')))?'text-blue-100 bg-blue-500 hover:bg-blue-400':'btn-default'?>">
            <?=_e('All')?>
        </a>
        <a href="?status=<?=Model_Message::STATUS_NOTREAD?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  <?=(Core::get('status',-1)==Model_Message::STATUS_NOTREAD)?'text-blue-100 bg-blue-500 hover:bg-blue-400':'btn-default'?>">
            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> <?=_e('Unread')?>
        </a>
        <a href="?status=<?=Model_Message::STATUS_ARCHIVED?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  <?=(Core::get('status',-1)==Model_Message::STATUS_ARCHIVED)?'text-blue-100 bg-blue-500 hover:bg-blue-400':'btn-default'?>">
            <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> <?=_e('Archieved')?>
        </a>
        <a href="?status=<?=Model_Message::STATUS_SPAM?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  <?=(Core::get('status',-1)==Model_Message::STATUS_SPAM)?'text-blue-100 bg-blue-500 hover:bg-blue-400':'btn-default'?>">
            <span class="glyphicon glyphicon-fire" aria-hidden="true"></span> <?=_e('Spam')?>
        </a>
    </div>
</div>
<br>
<div class="panel">
    <?if (core::count($messages) > 0):?>
        <br>
        <table class="w-full max-w-full mb-4 bg-transparent table-striped">
            <thead>
                 <tr>
                    <th><?=_e('Title')?> / <?=_e('From')?></th>
                    <th><?=_e('Date')?></th>
                    <th><?=_e('Last Answer')?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($messages as $message):?>
                    <tr class="message" 
                        data-url="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>($message->id_message_parent != NULL) ? $message->id_message_parent : $message->id_message))?>"
                        style="<?=($message->status_to == Model_Message::STATUS_NOTREAD AND $message->id_user_from != $user->id_user) ? 'font-weight: bold;' : NULL?>"
                    >
                        <td>
                            <p>
                                <?if(isset($message->ad->title)):?>
                                    <?=$message->ad->title?>
                                <?else:?>
                                    <?=_e('Direct Message')?>
                                <?endif?>
                                <?if ($message->status_to == Model_Message::STATUS_NOTREAD AND $message->id_user_from != $user->id_user) :?>
                                    <span class="label label-warning"><?=_e('Unread')?></span>
                                <?endif?>
                                <br>
                                <a href="<?=Route::url('profile', array('seoname' => $message->from->seoname))?>"><?=$message->from->name?></a>
                            </p>
                        </td>
                        <td><?=$message->parent->created?></td>
                        <td><?=(empty($message->parent->read_date))?_e('None'):$message->created?></td>
                        <td class="text-right">
                            <a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'message','id'=>($message->id_message_parent != NULL) ? $message->id_message_parent : $message->id_message))?>" 
                                class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-xs <?=($message->status_to == Model_Message::STATUS_NOTREAD AND $message->id_user_from != $user->id_user) ? 'text-yellow-100 bg-yellow-500 hover:bg-yellow-400' : 'btn-default'?>"
                            >
                                <i class="fa fa-envelope"></i>
                            </a>
                        </td>
                    </tr>
                <?endforeach?>
            </tbody>
        </table>
    <?else:?>
        <h3><?=_e('You don’t have any messages yet.')?></h3>
    <?endif?>
</div><!--//panel-->

<?if(isset($pagination)):?>
    <div class="text-center">
        <?=$pagination?>
    </div>
<?endif?>