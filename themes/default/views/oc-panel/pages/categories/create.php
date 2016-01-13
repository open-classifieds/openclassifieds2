<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('New')?> <?=Text::ucfirst(__($name))?></h1>
        <span class="page-description"><?=__('Change the order of your categories. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).')?> <a target="_blank" href="https://docs.yclas.com/how-to-add-categories/"><?=__('Read more')?></a></span>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <?=$form->render()?>
            </div>
        </div>
    </div>
</div>