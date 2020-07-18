<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=Kohana::$charset?>">
    <title><?=$title?></title>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: 0;padding: 0;background-color: #DEE0E2;height: 100%;width: 100%;">
<center>
<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;margin: 0;padding: 0;background-color: #DEE0E2;border-collapse: collapse;height: 100%;width: 100%;">
<tr>
<td align="center" valign="top" id="bodyCell" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;margin: 0;padding: 20px;border-top: 4px solid #BBBBBB;height: 100%;width: 100%;">
<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;width: 600px;border: 1px solid #BBBBBB;border-collapse: collapse;">
    <tr>
        <td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templatePreheader" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #F4F4F4;border-bottom: 1px solid #CCCCCC;border-collapse: collapse;">
                <tr>
                    <td valign="top" class="preheaderContent" style="padding-top: 10px;padding-right: 20px;padding-bottom: 10px;padding-left: 20px;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #808080;font-family: Helvetica;font-size: 10px;line-height: 125%;text-align: left;" mc:edit="preheader_content00">
                        <?=Text::limit_chars(strip_tags($title.' '.$content), 75, NULL, TRUE)?>
                    </td>
                    <td valign="top" width="180" class="preheaderContent" style="padding-top: 10px;padding-right: 20px;padding-bottom: 10px;padding-left: 0;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #808080;font-family: Helvetica;font-size: 10px;line-height: 125%;text-align: left;" mc:edit="preheader_content01">
                        <?=date(core::config('general.date_format'))?> - <a href="<?=core::config('general.base_url')?>" target ="_blank" title="<?=HTML::chars(core::config('general.site_name'))?>">
                            <?=core::config('general.site_name')?>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <?if (Theme::get('logo_url')!=''):?>
    <tr>
        <td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #F4F4F4;border-top: 1px solid #FFFFFF;border-bottom: 1px solid #CCCCCC;border-collapse: collapse;">
                <tr>
                    <td valign="top" class="headerContent" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: Helvetica;font-size: 20px;font-weight: bold;line-height: 100%;padding-top: 0;padding-right: 0;padding-bottom: 0;padding-left: 20px;text-align: left;vertical-align: middle;">
                        <img src="<?=strpos(Theme::get('logo_url'), 'http') === FALSE ? core::config('general.base_url') : NULL?><?=Theme::get('logo_url')?>" title="<?=HTML::chars(core::config('general.site_name'))?>" alt="<?=HTML::chars(core::config('general.site_name'))?>" style="max-width: 600px;-ms-interpolation-mode: bicubic;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;" id="headerImage" mc:label="header_image" mc:edit="header_image" >
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?endif?>

    <tr>
        <td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #F4F4F4;border-top: 1px solid #FFFFFF;border-bottom: 1px solid #CCCCCC;border-collapse: collapse;">
                <tr>
                    <td valign="top" class="bodyContent" mc:edit="body_content" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #505050;font-family: Helvetica;font-size: 14px;line-height: 150%;padding-top: 20px;padding-right: 20px;padding-bottom: 20px;padding-left: 20px;text-align: left;">
                        <h1 style="display: block;font-family: Helvetica;font-size: 26px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: normal;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;color: #202020;"><?=$title?></h1>
                        <p><?=$content?></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" valign="top" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateFooter" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;background-color: #F4F4F4;border-top: 1px solid #FFFFFF;border-collapse: collapse;">
                <tr>
                    <td valign="top" class="footerContent" mc:edit="footer_content00" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;mso-table-lspace: 0pt;mso-table-rspace: 0pt;color: #808080;font-family: Helvetica;font-size: 10px;line-height: 150%;padding-top: 20px;padding-right: 20px;padding-bottom: 20px;padding-left: 20px;text-align: left;">
                       <?=date('Y')?> &copy; <a href="<?=core::config('general.base_url')?>" target ="_blank" title="<?=HTML::chars(core::config('general.site_name'))?>">
                            <?=core::config('general.site_name')?>
                        </a>.
                        <a href="<?=$unsubscribe_link?>">
                            <?=__('Unsubscribe')?>
                        <a>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
</center>
</body>
