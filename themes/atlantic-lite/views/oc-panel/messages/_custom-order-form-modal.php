<div class="modal fade" id="customOrderFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <?=FORM::open(Route::url('oc-panel', ['controller' => 'messages', 'action' => 'custom_order', 'id' => Request::current()->param('id')]))?>
                <div class="modal-header">
                    <h5 class="modal-title"><?=__('Create custom order')?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="description"><?=__('Description')?></label>
                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            required
                        ><?= Core::post('description') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="amount"><?=__('Amount')?></label>
                        <input
                            name="amount"
                            type="text"
                            class="form-control"
                            id="amount"
                            placeholder="<?=i18n::format_currency(0,core::config('payment.paypal_currency'))?>"
                            required
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button>
                </div>
            <?=FORM::close()?>
        </div>
    </div>
</div>
