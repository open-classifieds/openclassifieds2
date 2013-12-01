<?php defined('SYSPATH') or die('No direct script access.');?>

<? if (count($market)>=1):?>
<div class="row">
<ul class="thumbnails">
<?$i=0;
foreach ($market as $item):?>
    <?if ($i%3==0):?></ul></div><div class="row"><ul class="thumbnails"><?endif?>
    <li class="col-md-4 col-sm-4">
    <div class="thumbnail <?if ( $item['price_offer']>0):?>alert-success<?endif?>" >

        <?if ( ! empty($item['url_screenshot'])):?>
            <img class="thumb_market" src="<?=$item['url_screenshot']?>" alt="<?=HTML::chars($item['title'])?>" title="<?=HTML::chars($item['title'])?>" />
        <?else:?>
             <img class="thumb_market" src="http://www.placehold.it/300x200&text=<?=$item['title']?>" alt="<?=HTML::chars($item['title'])?>" title="<?=HTML::chars($item['title'])?>" />
        <?endif?>   
        
        <div class="caption">
            <h3><?=$item['title']?></h3>
            <p>
                <?if ( $item['price_offer']>0):?>
                    <span class="label label-danger">$<?=$item['price_offer']?></span>
                    <span class="label label-info"><del>$<?=$item['price']?></del></span>
                <?else:?>
                    <span class="label label-info">$<?=$item['price']?></span>
                <?endif?>
                <span class="label label-success"><?=$item['type']?></span>
            </p>
            <p>
                <?=Text::bb2html($item['description'])?>
                <?if (strlen($item['url_more'])>0):?>
                    <a href="<?=$item['url_more']?>"><?=__('More info')?></a>
                <?endif?>
            </p>
            <?if ( $item['price_offer']>0):?>
            <p>
                <a href="<?=$item['url_buy']?>" class="btn btn-block btn-danger"><?=__('Limited Offer!')?> $<?=$item['price_offer']?></a>
                <a href="<?=$item['url_buy']?>" class="btn btn-block btn-info"><i class="glyphicon  glyphicon-time glyphicon"></i> <?=__('Valid Until')?>  <?= Date::format($item['offer_valid'], core::config('general.date_format'))?></a>
            </p>
            <?endif?>
            <p>
                <a class="btn btn-primary" href="<?=$item['url_buy']?>">
                    <i class="glyphicon  glyphicon-shopping-cart"></i>  <?=__('Buy Now')?>
                </a>
                <?if (empty($item['url_demo'])===FALSE):?>
                    <a class="btn btn-default btn-xs" target="_blank" href="<?=$item['url_demo']?>">
                        <i class="glyphicon  glyphicon-eye-open"></i>
                            <?=__('Preview')?>
                    </a>    
                <?endif?>
                
            </p>
        </div>
    </div>
    </li>
    <?$i++;
    endforeach?>
</ul>
</div><!--/row-->
<?endif?>