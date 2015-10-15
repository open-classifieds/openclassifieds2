<?php 
$I = new AcceptanceTester($scenario);
$I->am("the admin");
$I->wantTo('change configurations and see changes on frontend');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');


// Description BBCode
$I->amOnPage('/oc-panel/Config/update/description_bbcode');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// Read
$I->amOnPage('/publish-new.html');
$I->seeElement('.form-control.disable-bbcode');

// Back to default
$I->amOnPage('/oc-panel/Config/update/description_bbcode');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/publish-new.html');
$I->dontSeeElement('.form-control.disable-bbcode');



// Captcha
$I->amOnPage('/oc-panel/Config/update/captcha');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// Read
$I->amOnPage('/publish-new.html');
$I->dontSee('captcha','label');
$I->dontSeeElement('input', ['name' => 'captcha']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/captcha');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/publish-new.html');
$I->see('captcha','label');
$I->seeElement('input', ['name' => 'captcha']);



// Captcha
$I->amOnPage('/oc-panel/Config/update/map_pub_new');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// Read
$I->amOnPage('/publish-new.html');
$I->seeElement('.popin-map-container');

// Back to default
$I->amOnPage('/oc-panel/Config/update/map_pub_new');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/publish-new.html');
$I->dontSeeElement('.popin-map-container');



// Terms of Service
// Create new page first
$I->amOnPage('/oc-panel/content/create?type=page');
$I->fillField('#title','Terms of Service');
$I->fillField('#description','Terms of Service');
$I->checkOption('status');
$I->click('submit');
$I->see('page is created. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#tos','Terms of Service');
$I->click('submit');

// Read
$I->amOnPage('/publish-new.html');
$I->see('Terms of Service','a');
$I->seeElement('input', ['name' => 'tos']);

// Back to default
$I->amOnPage('/oc-panel/settings/form');
$I->selectOption('#tos','');
$I->click('submit');

$I->amOnPage('/oc-panel/content/page');
$I->click('.btn.btn-danger.index-delete');

$I->amOnPage('/oc-panel/tools/cache?force=1'); // Delete cache ALL
$I->amOnPage('/publish-new.html');
$I->dontSee('Terms of Service','a');
$I->dontSeeElement('input', ['name' => 'tos']);
