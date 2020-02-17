<iframe
    width="100%"
    height="<?= empty(core::config('advertisement.homepage_map_height')) ? '400px' : core::config('advertisement.homepage_map_height') ?>"
    frameBorder="0"
    src="<?= Route::url('map') ?>"
    class="homepage_map"
    <?= core::config('advertisement.homepage_map_allowfullscreen') == 1 ? 'allowfullscreen' : '' ?>
    >
</iframe>
