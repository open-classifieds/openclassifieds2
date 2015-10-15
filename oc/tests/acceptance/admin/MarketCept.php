<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/');
$I->wantTo('see the Market page');
$I->click('a[href="http://reoc.lo/oc-panel/market"]');
$I->see('Market','h1');
$I->see('Selection of nice extras for your installation.','p');

$I->amOnPage('/');
$I->click('Logout');



