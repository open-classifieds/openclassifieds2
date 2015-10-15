<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('enable banned words while editing ad');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Config/update/banned_words_replacement');
$I->fillField('#formorm_config_value','replacement');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/oc-panel/Config/update/banned_words');
$I->fillField('#formorm_config_value','banned');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/myads/update/1');
$I->see('some nice title here');
$I->see('Edit Advertisement');
$I->see('Id_User');
$I->see('Profile');
$I->see('Name');
$I->see('Email');
$I->see('Status');
$I->fillField('#description','banned');
$I->click('submit_btn');
$I->see('Advertisement is updated');

$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see('replacement');
$I->dontSee('banned');

$I->amOnPage('/oc-panel/myads/update/1');
$I->fillField('#description','description allows bbcode');
$I->click('submit_btn');
$I->see('Advertisement is updated');