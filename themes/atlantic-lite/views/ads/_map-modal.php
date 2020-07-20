<? if(core::count($ads)): ?>
    <div class="modal fade" id="listingMap" tabindex="-1" role="dialog" aria-labelledby="listingMap">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <script type="text/javascript">
                        var locations = [
                            <? foreach ($ads as $ad): ?>
                                <?if($ad->latitude AND $ad->longitude):?>
                                    {
                                        lat: <?=$ad->latitude?>,
                                        lon: <?=$ad->longitude?>,

                                        <? if(Core::config('advertisement.map_zoom')) : ?>
                                            zoom: <?=Core::config('advertisement.map_zoom')?>,
                                        <? endif ?>

                                        title: '<?=htmlentities(str_replace('"',' ',json_encode($ad->title)),ENT_QUOTES)?>',

                                        <? if(( $icon_src = $ad->category->get_icon() )!==FALSE AND !is_numeric(core::get('id_ad'))): ?>
                                            icon: '<?=Core::imagefly($icon_src,50,50)?>',
                                        <? endif ?>

                                        <? if (core::get('controls') != 0) : ?>
                                            html: '<div style="overflow: visible; cursor: default; clear: both; position: relative; background-color: rgb(255, 255, 255); border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; padding: 6px 0; width: 100%; height: auto;"><p style="margin-bottom:10px;margin-top:-5px;"><?=htmlentities($ad->address,ENT_QUOTES)?></p><p style="margin:0;"><?if($ad->get_first_image() !== NULL):?><img src="<?=Core::imagefly($ad->get_first_image(),100,100)?>" style="float:left; width:100px; margin-right:10px; margin-bottom:6px;"><?endif?><a target="_blank" style="text-decoration:none; margin-bottom:15px; color:#4272db;" href="<?=Route::url('ad',  array('category'=>$ad->category,'seotitle'=>$ad->seotitle))?>"><?=htmlentities($ad->title,ENT_QUOTES)?></a><br><br><?=htmlentities(Text::limit_chars(Text::removenl(Text::removebbcode($ad->description)), 255, NULL, TRUE),ENT_QUOTES)?></p></div>',
                                        <? else: ?>
                                            html: '<div style="overflow: visible; cursor: default; clear: both; position: relative; background-color: rgb(255, 255, 255); border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; padding: 6px 0; width: 100%; height: auto;"><p style="margin:0;"><a target="_blank" style="text-decoration:none; margin-bottom:15px; color:#4272db;" href="<?=Route::url('ad',  array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?=htmlentities($ad->title,ENT_QUOTES)?></a></p></div>',
                                        <? endif ?>
                                    },
                                <? endif ?>
                            <? endforeach ?>
                        ];
                    </script>

                    <div id="gmap" style="height:400px;width:568px;"></div>
                </div>
            </div>
        </div>
    </div>
<? endif ?>
