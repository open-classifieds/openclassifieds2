<div id="fb-root"></div>

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?=i18n::get_facebook_language(i18n::$locale)?>/all.js#xfbml=1&appId=<?=$fbcomments?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-comments" data-href="<?=$datahref?>" data-numposts="5" data-width="100%" data-colorscheme="light"></div>
