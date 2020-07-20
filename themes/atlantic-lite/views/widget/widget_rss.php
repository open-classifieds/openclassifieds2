<div class="card card-rss mb-3">
    <?if ($widget->rss_title != ''):?>
        <div class="card-header">
            <span class="h6"><?= $widget->rss_title ?></span>
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

    <div class="card-body">
        <script language="JavaScript" src="//feed2js.org//feed2js.php?src=<?=urlencode($widget->rss_url)?>&num=<?=$widget->rss_limit?>&desc=0&utf=y"  charset="UTF-8" type="text/javascript"></script>
    </div>
</div>
