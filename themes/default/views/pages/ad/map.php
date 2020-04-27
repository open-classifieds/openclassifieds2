<?if (core::config('advertisement.gm_api_key')):?>
    <p>
        <img class="img-responsive" src="//maps.googleapis.com/maps/api/staticmap?language=<?=i18n::get_gmaps_language(i18n::$locale)?>&amp;zoom=<?=Core::config('advertisement.map_zoom')?>&amp;scale=false&amp;size=600x300&amp;maptype=roadmap&amp;format=png&amp;visual_refresh=true&amp;markers=<?=($ad->category->get_icon() AND Kohana::$environment !== Kohana::DEVELOPMENT) ? 'icon:'.$ad->category->get_icon().'%7C' : 'size:large%7Ccolor:red%7Clabel:·%7C'?><?=$ad->latitude?>,<?=$ad->longitude?>&amp;key=<?=core::config('advertisement.gm_api_key')?>" alt="<?=HTML::chars($ad->title)?> <?=_e('Map')?>" style="width:100%;">
    </p>
    <p>
        <a class="btn btn-default btn-sm" href="<?=Route::url('map')?>?id_ad=<?=$ad->id_ad?>" >
            <span class="glyphicon glyphicon-globe"></span> <?=_e('Map View')?>
        </a>
    </p>
<?elseif (Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()) :?>
    <div class="alert alert-danger" role="alert">
        <a href="<?=Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))?>" class="alert-link">
            <?=__('Please set your Google API key on advertisement configuration.')?>
        </a>
    </div>
<?endif?>
