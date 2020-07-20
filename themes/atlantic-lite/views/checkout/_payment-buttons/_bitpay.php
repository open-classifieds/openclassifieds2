<?php defined('SYSPATH') or die('No direct script access.');?>

<a href="<?= $invoice->getUrl() ?>" class="btn btn-info pay-btn tw-w-full" onclick="openInvoice()">
    <?= _e('Pay with Bitcoin') ?>
</a>
