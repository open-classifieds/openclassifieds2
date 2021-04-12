<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (Kohana::$environment === Kohana::PRODUCTION AND core::config('general.analytics_global_site_tag')!==''): ?>
    <?= Core::config('general.analytics_global_site_tag') ?>
<?elseif (Kohana::$environment === Kohana::PRODUCTION AND core::config('general.analytics')!=='' ): ?>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', '<?=Core::config('general.analytics')?>', 'auto');
    ga('send', 'pageview');
    </script>
<?endif?>
