<a id="report_ad" class="btn btn-xs btn-danger" href="<?=Route::url('contact')?>?subject=<?=__('Report Ad')?> - <?=$ad->id_ad?> - <?=$ad->title?>&message=<?=__('Report Ad')?> - <?=$ad->id_ad?> - <?=$ad->title?> - <?=Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
    <span class="fas fa-exclamation" aria-hidden="true"></span> <?=_e('Report this ad')?>
</a>
