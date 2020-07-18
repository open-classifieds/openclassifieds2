<? if(!isset($_COOKIE['accept_terms']) AND core::config('general.alert_terms') != ''): ?>
    <? $content = Model_Content::get_by_title(core::config('general.alert_terms')) ?>

    <div class="modal fade" id="accept_terms_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $content->title ?></h5>
                    <button type="button" class="close" onclick='location.href="https://www.google.com"' aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= Text::bb2html($content->description, TRUE, FALSE) ?>
                </div>
                <div class="modal-footer">
                    <button name="decline_terms" class="btn btn-default" onclick='location.href="https://www.google.com"' >
                        <?=_e('No')?>
                    </button>
                    <button name="accept_terms" class="btn btn-success" onclick='window.setCookie("accept_terms",1,10000)' data-dismiss="modal" aria-hidden="true">
                        <?=_e('I accept')?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<? endif ?>
