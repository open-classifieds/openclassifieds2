<html lang="en">
<head>
    <title><?=$title?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Maps">
    <meta name="author" content="Yclas">
    <script type="text/javascript" src="//maps.google.com/maps/api/js?libraries=geometry&v=3&key=<?=core::config('advertisement.gm_api_key')?>&language=<?=i18n::get_display_language(i18n::$locale)?>"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/maplace-js/0.2.7/maplace.min.js"></script>
    
    <script type="text/javascript">
      var locations = [
      <?foreach ($ads as $ad):?>
      {       
            lat: <?=$ad->latitude?>,
            lon: <?=$ad->longitude?>,
            <?if(core::get('zoom')) :?>
              zoom: <?=core::get('zoom')?>,
            <?endif?>
            title: '<?=htmlentities(str_replace('"','',json_encode($ad->title)),ENT_QUOTES)?>',
            <?if(( $icon_src = $ad->category->get_icon() )!==FALSE AND !is_numeric(core::get('id_ad'))):?>
            icon: '<?=Core::imagefly($icon_src,50,50)?>',
            <?endif?>
            animation: google.maps.Animation.DROP,
            <?if (core::get('controls') != 0) :?>
              html: '<div style="overflow: visible; cursor: default; clear: both; position: relative; background-color: rgb(255, 255, 255); border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; padding: 6px 0; width: 100%; height: auto;"><p style="margin-bottom:10px;margin-top:-5px;"><?=htmlentities($ad->address,ENT_QUOTES)?></p><p style="margin:0;"><?if($ad->get_first_image() !== NULL):?><img src="<?=Core::imagefly($ad->get_first_image(),100,100)?>" style="float:left; width:100px; margin-right:10px; margin-bottom:6px;"><?endif?><a target="_blank" style="text-decoration:none; margin-bottom:15px; color:#4272db;" href="<?=Route::url('ad',  array('category'=>$ad->category,'seotitle'=>$ad->seotitle))?>"><?=htmlentities($ad->title,ENT_QUOTES)?></a><br><br><?=htmlentities(Text::limit_chars(Text::removenl(Text::removebbcode($ad->description)), 255, NULL, TRUE),ENT_QUOTES)?></p></div>',
            <?else:?>
              html: '<div style="overflow: visible; cursor: default; clear: both; position: relative; background-color: rgb(255, 255, 255); border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; padding: 6px 0; width: 100%; height: auto;"><p style="margin:0;"><a target="_blank" style="text-decoration:none; margin-bottom:15px; color:#4272db;" href="<?=Route::url('ad',  array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?=htmlentities($ad->title,ENT_QUOTES)?></a></p></div>',
            <?endif?>    
      },
    
      <?endforeach?>
      ];
    
      $(function() {
          new Maplace({
              locations: locations,
              controls_on_map: false,
              <?if(! core::get('zoom')) :?>
                pan_on_click: false,
              <?endif?>
              <? if(core::config('advertisement.map_style') != '') :?>
                styles: {
                  'Default': <?=core::config('advertisement.map_style')?>
                }
              <?endif?>
          }).Load();
      });
    </script>
    <style type="text/css">
      .close {
        color: #000;
        float: right;
        font-size: 25px;
        line-height: 1;
        opacity: 0.2;
        padding: 0;
        cursor: pointer;
        border: 0 none;
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        position: relative;
        right: -20px;
        top: 0px;
      }
      .close:after {
        content: '✖';
      }
    </style>
</head>

<body style="<?=core::get('controls') != 0 ? 'padding:0 20px 20px;' : 'margin:0;'?>">
    <?if (core::get('controls') != 0) :?>
        <div>
          <button class="close" onclick="window.history.back();">
            <span>&nbsp;</span>
          </button>
        </div>
    <?endif?>
    <div id="gmap" style="height:<?=$height?>;width:<?=$width?>;"></div>

    <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>

</body>
</html>
