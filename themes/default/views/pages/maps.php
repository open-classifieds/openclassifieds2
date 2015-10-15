<html>
<head>
    <title><?=$title?></title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/maplace.js/0.1.3/maplace.min.js"></script>
    
    <script type="text/javascript">
      var locations = [
      <?foreach ($ads as $ad):?>
      {       
              lat: <?=$ad->latitude?>,
              lon: <?=$ad->longitude?>,
    
              title: '<?=htmlentities($ad->title,ENT_QUOTES)?>',
              <?if(( $icon_src = $ad->category->get_icon() )!==FALSE AND !is_numeric(core::get('id_ad'))):?>
                <?if(Kohana::$environment === Kohana::DEVELOPMENT):?>
                    icon: '<?=$icon_src?>',
                <?else:?>
                    icon: '<?=Core::is_HTTPS() ? "https://" : "http://"?>i0.wp.com/<?=preg_replace("(^https?://)", "", $icon_src)?><?=(strpos($icon_src, "?"))?"&":"?"?>fit=50,50',
                <?endif?>
              <?endif?>
              animation: google.maps.Animation.DROP,
              html: '<div style="overflow: visible; cursor: default; clear: both; position: relative; background-color: rgb(255, 255, 255); border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; padding: 6px 0; width: 100%; height: auto;"><p style="margin-bottom:10px;margin-top:-5px;"><?=htmlentities($ad->address,ENT_QUOTES)?></p><p style="margin:0;"><?if($ad->get_first_image() !== NULL):?><img src="<?=$ad->get_first_image()?>" style="float:left; width:100px; margin-right:10px; margin-bottom:6px;"><?endif?><a target="_blank" style="text-decoration:none; margin-bottom:15px; color:#4272db;" href="<?=Route::url('ad',  array('category'=>$ad->category,'seotitle'=>$ad->seotitle))?>"><?=htmlentities($ad->title,ENT_QUOTES)?></a><br><br><?=htmlentities(Text::limit_chars(Text::removenl(Text::removebbcode($ad->description)), 255, NULL, TRUE),ENT_QUOTES)?></p></div>',
    
      },
    
      <?endforeach?>
      ];
    
      $(function() {
          new Maplace({
              locations: locations,
              controls_on_map: false,
              pan_on_click: false,
          }).Load();
      });
    </script>
</head>

<body>

    <div id="gmap" style="height:<?=$height?>;width:<?=$width?>;"></div>

    <?=(Kohana::$environment === Kohana::DEVELOPMENT)? View::factory('profiler'):''?>

</body>
</html>