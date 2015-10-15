<?php 
$I = new AcceptanceTester($scenario);
$I->am('a user');
$I->wantTo('publish a new ad');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('my profile');

$I->amOnPage('/publish-new.html');
$I->see('Publish new advertisement','h1');
$I->fillField('#title',"User ad");
$I->click('.select-category');
$I->fillField('category','18');
$I->fillField('location','4');
$I->fillField('#description','This is a new user ad');
$I->attachFile('input[type="file"]', 'photo.jpg');
$I->fillField('#phone','99885522');
$I->fillField('#address','barcelona');
$I->fillField('#price','25');
$I->fillField('#website','https://www.user.com');
$I->click('submit_btn');

$I->see('Advertisement is posted. Congratulations!');

$I->amOnPage('/apartment/user-ad.html');
$I->see('User ad','h1');
$I->see('25.00','span');
$I->see('Phone: 99885522','a');
$I->see('This is a new user ad');
$I->see('Barcelona');
$I->seeElement('a', ['href' => 'http://reoc.lo/user/gary-doe']);
$I->seeElement('a', ['href' => 'https://www.user.com']);

$I->click('Logout'); 