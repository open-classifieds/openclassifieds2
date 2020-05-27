<?php defined('SYSPATH') or die('No direct script access.');?>

<?=View::factory('oc-panel/elasticemail')?>

<a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-teal-100 bg-teal-500 hover:bg-teal-400 pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'email'))?>?force=1" title="<?=__('Email Settings')?>">
        <?=__('Email Settings')?>
    </a>
<h1 class="page-header page-title">
    <?=__('Newsletter')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-send-the-newsletter/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
<hr>

<div class="flex flex-wrap">
    <div class="md:w-full pr-4 pl-4">
        <form method="post" action="<?=Route::url('oc-panel',array('controller'=>'newsletter','action'=>'index'))?>"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <?=Form::errors()?> 
                        <div class="mb-4">
                            <label class="control-label"><?=__('To')?>:</label>
                            <div class="checkbox check-success">
                                <input type="checkbox" name="send_all" id="send_all">
                                <label for="send_all"><?=__('All active users.')?> <span class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-teal-800 bg-teal-400"><?=$count_all_users?></span></label>
                            </div>
                            <div class="checkbox check-success">
                                <input type="checkbox" name="send_featured" id="send_featured">
                                <label for="send_featured"><?=__('Users with featured ads.')?> <span class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-teal-800 bg-teal-400"><?=$count_featured?></span></label>
                            </div>
                            <div class="checkbox check-success">
                                <input type="checkbox" name="send_featured_expired" id="send_featured_expired">
                                <label for="send_featured_expired"><?=__('Users with featured ads expired.')?> <span class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-teal-800 bg-teal-400"><?=$count_featured_expired?></span></label>
                            </div>
                            <div class="checkbox check-success">
                                <input type="checkbox" name="send_unpub" id="send_unpub">
                                <label for="send_unpub"><?=__('Users without published ads.')?> <span class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-teal-800 bg-teal-400"><?=$count_unpub?></span></label>
                            </div>
                            <div class="checkbox check-success">
                                <input type="checkbox" name="send_logged" id="send_logged">
                                <label for="send_logged"><?=__('Users not logged last 3 months')?> <span class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-teal-800 bg-teal-400"><?=$count_logged?></span></label>
                            </div>
                            <div class="checkbox check-success">
                                <input type="checkbox" name="send_spam" id="send_spam">
                                <label for="send_spam"><?=__('Users marked a spam')?> <span class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded text-teal-800 bg-teal-400"><?=$count_spam?></span></label>
                            </div>
                        </div>
                       
                        <div class="mb-4">
                            <label class="control-label"><?=__('From')?>:</label>
                            <input type="text" name="from" value="<?=Auth::instance()->get_user()->name?>" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded"  />
                        </div>
                
                        <div class="mb-4">
                            <label class="control-label"><?=__('From Email')?>:</label>
                            <input type="text" name="from_email" value="<?=Auth::instance()->get_user()->email?>" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded"  />
                        </div>
                
                        <div class="mb-4">
                            <label class="control-label"><?=__('Subject')?>:</label>
                            <input  type="text" name="subject" value="" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded"  />
                        </div>
                
                        <div class="mb-4">
                            <label class="control-label"><?=__('Message')?>:</label>
                            <textarea  name="description"  id="formorm_description" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" data-editor="html" rows="15" ></textarea>
                        </div>
                    <hr>
                    <a href="<?=Route::url('oc-panel')?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default"><?=__('Cancel')?></a>
                    <button type="submit" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400"><?=__('Send')?></button>
                </div>
            </div>
        </form>
    </div>
</div>