<article class="row">
    <div class="col-12 col-md-3">
        <a title="<?=HTML::chars($review->user->name)?>" class="thumbnail"><img src="<?=$review->user->get_profile_image()?>" alt="<?=__('Profile image')?>" height="140px"></a>
    </div>

    <div class="col-12 col-md-9">
        <div>
            <ul class="list-inline">
                <li><i class="fa fa-calendar"></i> <span><?=$review->created?></span></li>
                <li><i class="fa fa-time"></i> <span><?=Date::fuzzy_span(Date::mysql2unix($review->created))?></span></li>
                <li><i class="fa fa-user"></i> <span><?=$review->user->name?></span></li>
                <?if ($review->rate!==NULL):?>
                    <div class="rating">
                        <h1 class="rating-num"><?=round($review->rate,2)?>.0</h1>
                        <?for ($i=0; $i < round($review->rate,1); $i++):?>
                            <span class="fa fa-star"></span>
                        <?endfor?>
                    </div>
                <?endif?>
            </ul>
        </div>
        <div>
            <div><?=Text::bb2html($review->description, TRUE)?></div>
        </div>
    </div>
</article>

<hr>
