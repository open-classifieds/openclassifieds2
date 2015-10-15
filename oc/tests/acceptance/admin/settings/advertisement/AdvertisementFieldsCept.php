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


// Address
$I->amOnPage('/oc-panel/Config/update/address');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/publish-new.html');
$I->dontSee('Address','label');
$I->dontSeeElement('input', ['name' => 'address']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/address');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/publish-new.html');
$I->see('Address','label');
$I->seeElement('input', ['name' => 'address']);



// Phone
$I->amOnPage('/oc-panel/Config/update/phone');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/publish-new.html');
$I->dontSee('Phone','label');
$I->dontSeeElement('input', ['name' => 'phone']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/phone');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/publish-new.html');
$I->see('Phone','label');
$I->seeElement('input', ['name' => 'phone']);



// upload_file
$I->amOnPage('/oc-panel/Config/update/upload_file');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/jobs/title-for-the-ad.html');
$I->see('File','label');
$I->seeElement('input', ['name' => 'file']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/upload_file');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/jobs/title-for-the-ad.html');
$I->dontSee('File','label');
$I->dontSeeElement('input', ['name' => 'file']);



// location
$I->amOnPage('/oc-panel/Config/update/location');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/publish-new.html');
$I->dontSee('Location','label');
$I->dontSeeElement('input', ['name' => 'location']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/location');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/publish-new.html');
$I->see('Location','label');
$I->seeElement('input', ['name' => 'location']);



// website
$I->amOnPage('/oc-panel/Config/update/website');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/publish-new.html');
$I->dontSee('Website','label');
$I->dontSeeElement('input', ['name' => 'website']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/website');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/publish-new.html');
$I->see('Website','label');
$I->seeElement('input', ['name' => 'website']);



// price
$I->amOnPage('/oc-panel/Config/update/price');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
//Read
$I->amOnPage('/publish-new.html');
$I->dontSee('Price','label');
$I->dontSeeElement('input', ['name' => 'price']);

// Back to default
$I->amOnPage('/oc-panel/Config/update/price');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');
$I->amOnPage('/publish-new.html');
$I->see('Price','label');
$I->seeElement('input', ['name' => 'price']);


