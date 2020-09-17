<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->rss_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->rss_title?></h3>
    </div>
<?endif?>
<?
/*
<div class="panel-body">
    <ul>
        <?foreach ($widget->rss_items as $item):?>
            <li><a target="_blank" href="<?=$item['link']?>" title="<?=HTML::chars($item['title'])?>"><?=$item['title']?></a></li>
        <?endforeach?>
    </ul>
</div>
*/
?>

<div class="panel-body">
    <script language="JavaScript" src="//feedroll.com/rssviewer/feed2js.php?src=<?=urlencode($widget->rss_url)?>&num=<?=$widget->rss_limit?>&desc=0&utf=y"  charset="UTF-8" type="text/javascript"></script>
</div>
