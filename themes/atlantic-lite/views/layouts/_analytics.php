<? if (Kohana::$environment === Kohana::PRODUCTION) : ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-55615337-1', 'auto');
        ga('require', 'displayfeatures');
        ga('send', 'pageview');

        <? if (core::config('general.analytics') !== '') : ?>
            ga('create', '<?= Core::config('general.analytics') ?>', 'auto', {'name': 'newTracker'});
            ga('newTracker.require', 'displayfeatures');
            ga('newTracker.send', 'pageview');
        <? endif ?>
    </script>
<? endif ?>
