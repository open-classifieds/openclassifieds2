<?php 
$I = new AcceptanceTester($scenario);
$I->am('a visitor');
$I->amGoingTo('register as a user that already exists');
$I->lookForwardTo('see the error message after trying to register');
$I->amOnPage('/oc-panel/auth/register');
$I->fillField('name','user');
$I->fillField(".register input[name='email']", 'user@gmail.com');
$I->fillField('password1','user');
$I->fillField('password2','user');
$I->click(".register button[type=submit]");
$I->expectTo('see success message');
$I->see('welcome');
$I->amOnPage('/');
$I->click('Logout');


$I->amGoingTo('register as a user that already exists');
$I->lookForwardTo('see the error message after trying to register');
$I->amOnPage('/oc-panel/auth/register');
$I->fillField('name','Jermain Doe');
$I->fillField(".register input[name='email']", 'user@reoc.lo');
$I->fillField('password1','1234');
$I->fillField('password2','1234');
$I->click(".register button[type=submit]");
$I->expectTo('see error message');
$I->see('User already exists');

$I->amGoingTo('register without filling Name field');
$I->fillField('name','');
$I->fillField(".register input[name='email']", 'user11@gmail.com');
$I->fillField('password1','123456');
$I->fillField('password2','123456');
$I->click(".register button[type=submit]");
$I->expectTo('see error message');
$I->see('name must not be empty');

$I->amGoingTo('register without filling Email field');
$I->fillField('name','user');
$I->fillField(".register input[name='email']", '');
$I->fillField('password1','123456');
$I->fillField('password2','123456');
$I->click(".register button[type=submit]");
$I->expectTo('see error message');
$I->see('email must not be empty');

$I->amGoingTo('register without filling Password field');
$I->fillField('name','user');
$I->fillField(".register input[name='email']", 'user11@gmail.com');
$I->fillField('password1','');
$I->fillField('password2','123456');
$I->click(".register button[type=submit]");
$I->expectTo('see error message');
$I->see('password1 must not be empty');

$I->amGoingTo('register without repeating the Password');
$I->fillField('name','user');
$I->fillField(".register input[name='email']", 'user11@gmail.com');
$I->fillField('password1','123456');
$I->fillField('password2','');
$I->click(".register button[type=submit]");
$I->expectTo('see error message');
$I->see('password2 must not be empty');

?>