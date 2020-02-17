<li class="list-group-item p-0">
    <div class="row no-gutters">
        <div class="col-md-3">
            <a class="d-block" title="<?=HTML::chars($ad->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                <?if($ad->get_first_image() !== NULL):?>
                    <img class="card-img rounded-0" src="<?=Core::imagefly($ad->get_first_image(),150,150)?>" alt="<?=HTML::chars($ad->title)?>" />
                <?elseif(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                    <img class="card-img rounded-0" src="<?=Core::imagefly($icon_src,150,150)?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                <?elseif(( $icon_src = $ad->location->get_icon() )!==FALSE ):?>
                    <img class="card-img rounded-0" src="<?=Core::imagefly($icon_src,150,150)?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                <?else:?>
                    <img class="card-img rounded-0" data-src="holder.js/150x150?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->translate_name(), 'size' => 14, 'auto' => 'yes')))?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>">
                <?endif?>
            </a>
        </div>
        <div class="col-md-9">
            <div class="card-body">
                <h5 class="card-title">
                    <a title="<?=HTML::chars($ad->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                        <?=$ad->title?>
                    </a>

                    <? if($ad->featured >= Date::unix2mysql(time())) : ?>
                        <span class="badge badge-secondary"><?= _e('Featured') ?></span>
                    <? endif ?>
                </h5>

                <? if(core::config('advertisement.description') != FALSE): ?>
                    <p><?=Text::limit_chars(Text::removebbcode($ad->description), 255, NULL, TRUE)?></p>
                <? endif ?>

                <? if ($ad->published != 0) : ?>
                    <ul class="card-text list-inline">
                        <? if($ad->id_location != 1) : ?>
                            <li class="text-muted small list-inline-item">
                                <a href="<?=Route::url('list',array('location'=>$ad->location->seoname))?>" title="<?=HTML::chars($ad->location->translate_name())?>">
                                    <span><?=$ad->location->translate_name()?></span>
                                </a>
                            </li>
                        <? endif ?>
                    </ul>
                <? endif ?>
            </div>
        </div>
    </div>
</li>
