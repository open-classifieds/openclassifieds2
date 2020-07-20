<div class="card mb-3 <?= $ad->featured >= Date::unix2mysql(time()) ? 'border-secondary' : NULL ?>">
    <div class="row no-gutters">
        <div class="col-md-4">
            <a title="<?=HTML::chars($ad->title)?>" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
                <?if($ad->get_first_image() !== NULL):?>
                    <img class="card-img rounded-0" src="<?=Core::imagefly($ad->get_first_image(),150,150)?>" alt="<?=HTML::chars($ad->title)?>" />
                <?elseif(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                    <img class="card-img rounded-0" src="<?=Core::imagefly($icon_src,150,150)?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>" />
                <?else:?>
                    <img class="card-img rounded-0" data-src="holder.js/150x150?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->translate_name(), 'size' => 14, 'auto' => 'yes')))?>" class="img-responsive" alt="<?=HTML::chars($ad->title)?>">
                <?endif?>
            </a>
        </div>
        <div class="col-md-8">
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

                <ul class="list-unstyled">
                    <? if (core::request('sort') == 'distance' AND Model_User::get_userlatlng()) : ?>
                      <li><b><?=_e('Distance');?>:</b> <?=i18n::format_measurement($ad->distance)?></li>
                    <? endif ?>

                    <? if ($ad->price!=0) : ?>
                      <li class="price"><?=_e('Price');?>: <b><span class="price-curry"><?=i18n::money_format( $ad->price, $ad->currency() )?></span></b></li>
                    <? endif ?>

                    <? if ($ad->price==0 AND core::config('advertisement.free')==1) : ?>
                      <li class="price"><?=_e('Price');?>: <b><?=_e('Free');?></b></li>
                    <? endif ?>
                </ul>

                <? if ($ad->published != 0) : ?>
                    <ul class="card-text list-inline">
                        <? if($ad->id_location != 1) : ?>
                            <li class="text-muted small list-inline-item">
                                <a href="<?=Route::url('list',array('location'=>$ad->location->seoname))?>" title="<?=HTML::chars($ad->location->translate_name())?>">
                                    <span><?=$ad->location->translate_name()?></span>
                                </a>
                            </li>
                        <? endif ?>

                        <li class="text-muted small list-inline-item">
                            <?= _e('Publish Date') ?>: <?=Date::format($ad->published, core::config('general.date_format'))?>
                        </li>
                    </ul>
                <? endif ?>
            </div>
        </div>
    </div>

    <?if ($user !== NULL AND ($user->is_admin() OR $user->is_moderator())):?>
        <div class="card-footer text-right">
            <div class="btn-group">
                <a class="btn btn-secondary btn-sm" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>">
                    <i class="fas fa-edit"></i> <?=_e("Edit");?>
                </a>

                <a class="btn btn-secondary btn-sm" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>"
                    onclick="return confirm('<?=__('Deactivate?')?>');">
                    <i class="fas fa-power-off"></i> <?=_e("Deactivate");?>
                </a>

                <a class="btn btn-secondary btn-sm" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>"
                    onclick="return confirm('<?=__('Spam?')?>');">
                    <i class="fas fa-fire"></i> <?=_e("Spam");?>
                </a>

                <a class="btn btn-secondary btn-sm" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>"
                    onclick="return confirm('<?=__('Delete?')?>');">
                    <i class="fas fa-minus"></i> <?=_e("Delete");?>
                </a>
            </div>
        </div>
    <?elseif($user !== NULL AND $user->id_user == $ad->id_user):?>
        <div class="card-footer text-muted">
            <div class="btn-group">
                <a class="btn btn-secondary btn-sm" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>">
                    <i class="fas fa-edit"></i> <?=_e("Edit");?>
                </a>

                <a class="btn btn-secondary btn-sm" href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'deactivate','id'=>$ad->id_ad))?>"
                    onclick="return confirm('<?=__('Deactivate?')?>');">
                    <i class="fas fa-power-off"></i> <?=_e("Deactivate");?>
                </a>
            </div>
        </div>
    <?endif?>
</div>
