<?php defined('SYSPATH') or die('No direct script access.');?>

<button data-toggle="modal" data-target="#alternative_pay_modal" class="btn btn-warning pay-btn full-w">
    <?=$content->title?>
</button>

<div class="modal fade" id="alternative_pay_modal" tabindex="-1" role="dialog" aria-labelledby="alternative_pay_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?=$content->title?></h4>
            </div>
            <div class="modal-body">
                <div class="text-description"><?=Text::bb2html($content->description,TRUE,FALSE)?></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=_e('OK')?></button>
            </div>
        </div>
    </div>
</div>
