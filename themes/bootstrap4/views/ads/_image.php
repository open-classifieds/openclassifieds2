<? if(isset($value['thumb']) AND isset($value['image'])): ?>
    <div class="col-md-3">
        <a href="<?=$value['image']?>" class="img-thumbnail d-inline-block gallery-item" data-gallery>
            <?=HTML::picture($value['image'], array('w' => 280, 'h' => 280), array('1200px' => array('w' => '125', 'h' => '125'), '992px' => array('w' => '96', 'h' => '96'), '768px' => array('w' => '939', 'h' => '939'), '480px' => array('w' => '727', 'h' => '727'), '320px' => array('w' => '440', 'h' => '440')), array('alt' => HTML::chars($ad->title), 'class' => 'img-responsive img-rounded'))?>
        </a>
    </div>
<? endif ?>
