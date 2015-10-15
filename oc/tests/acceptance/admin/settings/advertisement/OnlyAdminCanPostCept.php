<?php 
$I = new AcceptanceTester($scenario);

// Login successfully as the administrator
$I->am('the Administrator');
$I->wantTo('log in with valid account');
$I->lookForwardTo('see the welcome message in the Panel');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->wantTo('switch ON "only administrator can publish new ad"');
$I->amOnPage('/oc-panel/settings/form');
$I->see('Advertisement Configuration','h1');
$I->checkOption("#only_admin_post");
$I->click("submit"); //click save

$I->wantTo('logout and not to see publish new button');
$I->click("//a[@href='http://reoc.lo/oc-panel/auth/logout']");
$I->amOnPage('/');
$I->dontsee('publish new');

// bring it back to default option!
// login
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
// switch off only administrator can publish new ad
$I->amOnPage('/oc-panel/settings/form');
$I->see('Advertisement Configuration','h1');
$I->click("only_admin_post");
$I->click("submit"); //click save

// logout and check if i can see publish new button
$I->click("//a[@href='http://reoc.lo/oc-panel/auth/logout']");
$I->see('login');
$I->amOnPage('/');
$I->see('publish new');

















?>