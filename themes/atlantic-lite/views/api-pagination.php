<?php defined('SYSPATH') or die('No direct script access.');?>
<?if($next_page):?><<?=$page->url($next_page)?>>; rel="next",<?endif?><?if($last_page):?><<?=$page->url($last_page)?>>; rel="last",<?endif?><?if($first_page):?><<?=$page->url($first_page)?>>; rel="first",<?endif?><?if($previous_page):?><<?=$page->url($previous_page)?>>; rel="previous_page",<?endif?>
