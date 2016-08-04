<?php defined('SYSPATH') or die('No direct script access.') ?>
<h3><?php echo $widget->ads_title ?></h3>
<ul>
<?php foreach($widget->ads as $ad): ?>
    <li><a href="<?php echo Route::url('ad',array('seotitle'=>$ad->seotitle,'category'=>$ad->category->seoname)) ?>" title="<?php echo $ad->title ?>">
        <?php echo $ad->title ?></a>
    </li>
<?php endforeach; ?>
</ul>
