<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('publish a new ad');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/publish-new.html');
$I->see('Publish new advertisement','h1');
$I->fillField('#title',"Admin ad");
$I->click('.select-category');
$I->fillField('category','18');
$I->fillField('location','57');
$I->fillField('#description','This is a new admin ad');
$I->attachFile('input[id="fileInput0"]', 'photo.jpg');
$I->fillField('#phone','99885522');
$I->fillField('#address','barcelona');
$I->fillField('#price','25');
$I->fillField('#website','https://www.admin.com');
$I->click('submit_btn');

$I->see('Advertisement is posted. Congratulations!');

$I->amOnPage('/apartment/admin-ad.html');
$I->see('Admin ad','h1');
$I->see('25.00','span');
$I->see('Phone: 99885522','a');
$I->see('This is a new admin ad');
$I->see('Barcelona');


$I->click('Logout'); 
