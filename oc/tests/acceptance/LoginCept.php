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
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

//Logout before trying to login again, also test if logout button works
$I->wantTo('log out and try to login again');
$I->click('Logout'); 

// Login successfully as a normal user
$I->am('a user');
$I->wantTo('log in with valid account');
$I->lookForwardTo('see anything else but the login button');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('my profile');
$I->See('My Advertisements');

$I->wantTo('log out and try to login again');
$I->click('Logout'); //Logout before trying to login again

// Try to login using invalid passwd
$I->wantTo('try to log in with invalid password');
$I->lookForwardTo('see error message');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@reoc.lo');
$I->fillField('password','1111');
$I->click('auth_redirect');
$I->seeElement('.alert');
$I->see('Wrong email or password.');

// Try to login using invalid email
$I->wantTo('try to log in with invalid email');
$I->lookForwardTo('see error message');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','invalid@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->seeElement('.alert');
$I->see('Wrong email or password.');

// Forgot passwd 
// Remove '//' from the last three lines
$I->wantTo('retrieve my password');
$I->lookForwardTo('see successful message of sent email');
$I->amOnPage('/oc-panel/auth/forgot');
$I->fillField("//form[contains(@action,'http://reoc.lo/oc-panel/auth/forgot')]/div/div/input[@type='text']",'user@reoc.lo');
//$I->click('#button-forgot');
//$I->seeElement('.alert-success');
//$I->see('Email to recover password sent');
// It gives error because it takes long to do this action after clicking to send email.


// Login attempts
$I->wantTo('lock an email address temporarly');
$I->lookForwardTo('see error messages');
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','user@reoc.lo');
$I->fillField('password','dontletmein');
$I->click('auth_redirect'); // Try #1 
$I->see('Some errors in the form');


$I->fillField('email','user@reoc.lo');
$I->fillField('password','dontletmein');
$I->click('auth_redirect'); // Try #2 
$I->see('Some errors in the form');


$I->fillField('email','user@reoc.lo');
$I->fillField('password','dontletmein');
$I->click('auth_redirect'); // Try #4
$I->see('Login has been temporarily disabled due to too many unsuccessful login attempts. Please try again in a minute.');


sleep(61);
$I->fillField('email','user@reoc.lo');
$I->fillField('password','dontletmein');
$I->click('auth_redirect'); // Try #4
$I->see('Some errors in the form');


sleep(61);
$I->fillField('email','user@reoc.lo');
$I->fillField('password','dontletmein');
$I->click('auth_redirect'); // Try #4
$I->see('Some errors in the form');


$I->fillField('email','user@reoc.lo');
$I->fillField('password','dontletmein');
$I->click('auth_redirect'); // Try #4
$I->see('Login has been temporarily disabled due to too many unsuccessful login attempts. Please try again in 24 hours.');

?>
