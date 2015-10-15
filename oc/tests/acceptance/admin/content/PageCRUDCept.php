<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('crud a page');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

// Create
$I->amOnPage('/oc-panel/content/page');
$I->click('a[href="http://reoc.lo/oc-panel/content/create?type=page"]');
$I->see('Create Page');

$I->fillField('#title','My Page');
$I->fillField('#description','This is a page I created for test!');
$I->fillField('#seotitle','my-page');
$I->checkOption('status');
$I->click('button[type="submit"]');
$I->see('page is created. Please to see the changes delete the cache');

// Read
$I->amOnPage('/my-page.html');
$I->dontSee('Page not found');
$I->see('My Page','h1');
$I->see('This is a page I created for test!');

// Update
$I->amOnPage('/oc-panel/content/page');
$I->click('a[title="Edit"]');
$I->see('Edit Page','h1');
$I->fillField('#title','My Updated Page');
$I->fillField('#description','This is an updated page I created for test!');
$I->click('button[type="submit"]');
$I->see('page is edited');

$I->amOnPage('/my-page.html');
$I->see('My Updated Page','h1');
$I->see('This is an updated page I created for test!');

// Delete
$I->amOnPage('/oc-panel/content/page');
$I->click('a[title="Are you sure you want to delete?"]');
$I->amOnPage('/my-page.html');
$I->see('Page not found','h2');

$I->amOnPage('/');
$I->click('Logout'); 

