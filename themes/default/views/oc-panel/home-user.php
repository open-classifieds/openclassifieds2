<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="page-welcome" class="hero-unit">
    <h1 class="text-xl font-semibold">
    <?=__('Welcome')?>
    <?=Auth::instance()->get_user()->name?>
        &nbsp; <small><?=Auth::instance()->get_user()->email?> </small>
    </h1>

    <p><?=__('Thanks for using our website.')?></p>
</div>



<?if (Core::extra_features() == FALSE):?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle" style="display:inline-block;width:250px;height:250px" data-ad-client="ca-pub-9967797131457349" data-ad-slot="2690827714"></ins>
<script>(adsbygoogle=window.adsbygoogle||[]).push({});</script>
<?endif?>

