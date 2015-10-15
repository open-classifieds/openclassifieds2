<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('change languages');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// Arabian
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ar"]');
$I->see('ترجمات');

// Bulgarian
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/bg_BG"]');
$I->see('Преводи');

// bn_BD
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/bn_BD"]');
$I->see('অনুবাদ');

// ca_ES
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ca_ES"]');
$I->see('Traduccions');

// cs_CZ
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/cs_CZ"]');
$I->see('Překlady');

// da_DK
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/da_DK"]');
$I->see('Translations');

// de_DE
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/de_DE"]');
$I->see('Übersetzungen');

// el_GR
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/el_GR"]');
$I->see('Μεταφράσεις');

// en_UK
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/en_UK"]');
$I->see('Translations');

// en_US
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/en_US"]');
$I->see('Translations');

// es_ES
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/es_ES"]');
$I->see('Traducciones');

// fr_FR
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/fr_FR"]');
$I->see('Traductions');

// hi_IN
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/hi_IN"]');
$I->see('अनुवाद');

// hu_HU
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/hu_HU"]');
$I->see('Translations');

// in_ID
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/in_ID"]');
$I->see('Keberhasilan');

// it_IT
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/it_IT"]');
$I->see('Traduzioni');

// ja_JP
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ja_JP"]');
$I->see('翻訳');

// ml_IN
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ml_IN"]');
$I->see('ഭാഷാഭേദങ്ങള്‍');

// nl_NL
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/nl_NL"]');
$I->see('Vertalingen');

// no_NO
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/no_NO"]');
$I->see('Oversettelser');

// pl_PL
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/pl_PL"]');
$I->see('Tłumaczenia');

// pt_PT
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/pt_PT"]');
$I->see('Traduções');

// ro_RO
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ro_RO"]');
$I->see('Traduceri');

// ru_RU
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ru_RU"]');
$I->see('Переводы');

// sk_SK
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/sk_SK"]');
$I->see('Preklady');

// sn_ZW
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/sn_ZW"]');
$I->see('Zvaita');

// sq_AL
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/sq_AL"]');
$I->see('Përkthimet');

// sr_RS
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/sr_RS"]');
$I->see('Prevodi');

// sv_SE
$I->amOnPage('/oc-panel/translations');
//$I->click('a[href="http://reoc.lo/oc-panel/translations/index/sv_SE"]');
//$I->see('');

// Commented out due to memory limit issue!

// ta_IN
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ta_IN"]');
$I->see('மொழிபெயர்ப்பு');

// tl_PH
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/tl_PH"]');
$I->see('pagsasalin');

// tr
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/tr"]');
$I->see('Çeviriler');

// ur_PK
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/ur_PK"]');
$I->see('Translations');

// vi_VN
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/vi_VN"]');
$I->see('Dịch');

// zh_CN
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/zh_CN"]');
$I->see('翻译');


// Bring it back to English
$I->amOnPage('/oc-panel/translations');
$I->click('a[href="http://reoc.lo/oc-panel/translations/index/en_UK"]');
$I->see('Translations');

$I->amOnPage('/');
$I->click('Logout');


