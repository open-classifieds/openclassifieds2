<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('add content on Contact page');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// Create Page
$I->amOnPage('/oc-panel/content/page');
$I->click('a[href="http://reoc.lo/oc-panel/content/create?type=page"]');
$I->see('Create Page');

$I->fillField('#title','Contact Page');
$I->fillField('#description','This is a test text for contact page. You can contact us using this form!!');
$I->fillField('#seotitle','contact-page');
$I->checkOption('status');
$I->click('button[type="submit"]');
$I->see('page is created. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Config/update/contact_page');
$I->fillField('#formorm_config_value','contact-page');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// Read
$I->amOnPage('/contact.html');
$I->see('This is a test text for contact page. You can contact us using this form!!');

// Delete
$I->amOnPage('/oc-panel/Config/update/contact_page');
$I->fillField('#formorm_config_value','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/content/page');
$I->click('a[title="Are you sure you want to delete?"]');

$I->amOnPage('/contact.html');
$I->dontSee('This is a test text for contact page. You can contact us using this form!!');