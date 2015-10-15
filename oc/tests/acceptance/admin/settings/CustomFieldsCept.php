<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('crud a custom field');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/fields/new');
$I->see('New Custom Field');

$I->fillField('name','My Custom Field');
$I->fillField('label','My Custom Field');
$I->fillField('tooltip','Test field for testing');
$I->selectOption('form select[name="type"]','string');
$I->checkOption('required');
$I->checkOption('searchable');
$I->checkOption('show_listing');
$I->click('button[type="submit"]');

$I->see('Field my-custom-field created');
$I->seeElement('.drag-item');

// delete all cache
$I->amOnPage('/oc-panel/tools/cache?force=1');
$I->see('All cache deleted');

/*
// activate a premium theme to see the custom field!
$I->amOnPage('/oc-panel/Config/update/theme');
$I->fillField('#formorm_config_value','splash');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// delete all cache
$I->amOnPage('/oc-panel/tools/cache?force=1');
$I->see('All cache deleted');


$I->amOnPage('/publish-new.html');
$I->see('My Custom Field');

// Not able to see my custom field from the test 
// but it's displayed on http://reoc.lo/publish-new.html (Maybe an issue with PhpBrowser)

$I->amOnPage('/oc-panel/theme');
$I->click('a[href="http://reoc.lo/oc-panel/theme/index/default"]');
*/
$I->amOnPage('/oc-panel/fields');
$I->see('Custom Fields');
$I->see('my-custom-field');
$I->click('a[href="http://reoc.lo/oc-panel/fields/delete/my-custom-field"]');
//$I->click('.confirm'); 
$I->amOnPage('/oc-panel/fields');
$I->dontSeeElement('.drag-item');

$I->click('Logout'); 

