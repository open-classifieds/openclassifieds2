<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="mt-2">
    <?if (core::config('general.cron') == FALSE):?>
        <a class="delete inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-green-100 bg-green-500 hover:bg-green-400 "
            href="<?=Route::url('oc-panel', array('controller'=>'crontab','action'=>'status','id'=>1))?>"
        >
            <?=__('Activate Cron')?>
        </a>
        <p><?=__('Or')?></p>
        <p><?=__('Set up your cron at your hosting / cPanel, every 5 minutes')?> (*/5 * * * *)</p>
        <input type="text" value="/usr/bin/php -f <?=DOCROOT?>oc/modules/cron/cron.php" />
        <p><?=__('Or')?></p>
        <input type="text" value="wget -O <?=Route::url('default', array('controller'=>'cron','action'=>'run','id'=>'now'))?>" />
    <?else:?>
        <a class="delete inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400 "
            href="<?=Route::url('oc-panel', array('controller'=>'crontab','action'=>'status','id'=>0))?>"
            title="<?=__('Disable')?>">
            <?=__('Disable Cron')?>
        </a>
    <?endif?>
</div>
