<table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #F4F4F4;border-collapse: collapse;">
    <tbody>
        <? foreach ($ads as $ad) : ?>
            <tr>
                <td valign="top" class="bodyContent" mc:edit="body_content" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: Helvetica;font-size: 14px;line-height: 150%;padding-top: 5px;padding-right: 10px;padding-bottom: 5px;padding-left: 0px;text-align: left; border-top: 2px solid #4B5563;">
                    <p>
                        <?if($ad->get_first_image() !== NULL):?>
                            <img src="<?=Core::imagefly($ad->get_first_image(), 130, 130)?>" alt="<?= HTML::chars($ad->title) ?>" />
                        <?elseif(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                            <img src="<?=Core::imagefly($icon_src, 130, 130)?>" alt="<?=HTML::chars($ad->title)?>" />
                        <?endif?>
                    </p>
                </td>
                <td valign="top" class="bodyContent" mc:edit="body_content" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: Helvetica;font-size: 14px;line-height: 150%;padding-top: 5px;padding-right: 0px;padding-bottom: 5px;padding-left: 10px;text-align: left; width: 100%; border-top: 2px solid #4B5563;">
                    <p>
                        <span style="font-size: 18px;"><b><u><a href="<?=Route::url('ad', ['category' => $ad->category->seoname, 'seotitle' => $ad->seotitle])?>" target="_blank"><font color="#059669"><?= $ad->title ?></font></a></u></b></span>
                        <?if($ad->id_location != 1):?>
                            <br>
                            <font color="#6B7280">Location:</font>
                            <a href="<?=Route::url('list', ['location' => $ad->location->seoname])?>" title="<?= HTML::chars($ad->location->translate_name()) ?>">
                                <font color="#059669"><?=$ad->location->translate_name() ?></font>
                            </a>
                        <?endif?>
                        <br>
                        <font color="#6B7280"><?= __('Publish Date') ?>: <?= Date::format($ad->published, core::config('general.date_format')) ?> / <b><?= $ad->category->name ?></b></font>
                        <br>
                        <?= Text::limit_chars(Text::removebbcode($ad->description), 255, NULL, TRUE) ?>
                        <? foreach ($ad->custom_columns(TRUE) as $name => $value): ?>
                            <?if($value=='checkbox_1'):?>
                                <br><?=$name?></b>: ✓
                            <?elseif($value=='checkbox_0'):?>
                                <br><?=$name?></b>: ✗
                            <?else:?>
                                <?if(is_string($name)):?>
                                    <br><?=$name?></b>: <?=$value?>
                                <?else:?>
                                    <br><?=$value?>
                                <?endif?>
                            <?endif?>
                        <? endforeach ?>
                    </p>
                </td>
            </tr>
        <? endforeach ?>
    </tbody>
</table>
