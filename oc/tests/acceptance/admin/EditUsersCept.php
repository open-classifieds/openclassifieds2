<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('edit a user');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/User/update/4');
$I->fillField('#formorm_name','Michael');
$I->fillField('#formorm_email','michael@gmail.com');
$I->fillField('#formorm_description','Updated user description');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/user/john-smith');
$I->see('Updated user description');
$I->see('Michael');


$I->amOnPage('/oc-panel/User/update/4');
$I->fillField('#formorm_name','John Smith');
$I->fillField('#formorm_email','john@gmail.com');
$I->fillField('#formorm_description','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/user/john-smith');
$I->dontsee('Updated user description');
$I->see('John Smith');
