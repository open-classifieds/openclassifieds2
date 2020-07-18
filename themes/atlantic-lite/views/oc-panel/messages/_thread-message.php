<tr>
    <td class="text-center">
        <a class="tw-font-bold" href="<?= Route::url('profile', array('seoname' => $message->from->seoname)) ?>">
            <?= $message->from->name ?>
        </a>
    </td>
    <td>
        <em>
            <?= Date::fuzzy_span(Date::mysql2unix($message->created)) ?>
             -
            <?= $message->created ?>
        </em>
    </td>
</tr>
<tr>
    <td style="width: 12%;" class="text-center">
        <img
            src="<?= $message->from->get_profile_image() ?>"
            class="tw-rounded-full tw-h-12 tw-w-12"
            title="<?= HTML::chars($message->from->name) ?>"
        >
    </td>
    <td>
        <p class="<?= HTML::chars($message->from->name)?> ">
            <?= Text::bb2html($message->message, TRUE) ?>
        </p>

        <?if ($message->price > 0):?>
            <p>
                <strong><?=_e('Price')?></strong>: <?=i18n::money_format($message->price)?>
            </p>
        <?endif?>
    </td>
</tr>
