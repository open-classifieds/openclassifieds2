<!-- The modal dialog, which will be used to wrap the lightbox content -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>

    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>

                    <h4 class="modal-title"></h4>
                </div>

                <div class="modal-body next"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="fas fa-angle-left"></i>
                    </button>

                    <button type="button" class="btn btn-primary pull-left next">
                        <i class="fas fa-angle-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
