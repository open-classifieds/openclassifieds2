<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('enable recaptcha');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Config/update/login_to_post');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/publish-new.html');
$I->see('Please, login before posting advertisement!');
$I->seeElement('.alert.alert-info');
$I->seeElement('.well.form-horizontal.auth');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/publish-new.html');
$I->seeElement('.form-horizontal.post_new');

$I->amOnPage('/oc-panel/Config/update/login_to_post');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/publish-new.html');
$I->dontSee('Please, login before posting advertisement!');
$I->seeElement('.form-horizontal.post_new');



