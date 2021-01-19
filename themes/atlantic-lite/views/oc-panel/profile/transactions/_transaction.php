<?php defined('SYSPATH') or die('No direct script access.');?>

<tr id="tr<?=$transaction->id_transaction?>">

    <td><?= $transaction->id_transaction ?></td>

    <td><?= i18n::money_format($transaction->amount, 'YCL') ?></td>

    <td><?= $transaction->user_from->loaded() ? $transaction->user_from->name : NULL ?></td>
</tr>
