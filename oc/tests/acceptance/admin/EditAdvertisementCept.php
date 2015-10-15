<?php 
$I = new AcceptanceTester($scenario);
$I->am("the admin");
$I->wantTo('edit an ad and see the changes');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');



// Update
$I->amOnPage('/oc-panel/myads/update/1');
$I->fillField('title','Updated Ad');
//$I->fillField('description','Updated description');
$I->fillField('phone','00112233');
$I->fillField('address','Malta');
$I->fillField('website','http://google.com');
$I->fillField('price','12');
$I->click('submit_btn');
$I->see('Advertisement is updated');

// Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see('Updated Ad','h1');
$I->see('Price : $12.00','span');
$I->see('http://google.com','a');
$I->see('Malta','p');
$I->see('Phone: 00112233','a');


// back to default
$I->amOnPage('/oc-panel/myads/update/1');
$I->fillField('title','some nice title here');
//$I->fillField('description','Updated description');
$I->fillField('phone','949494949');
$I->fillField('address','optional address');
$I->fillField('website','http://open-classifieds.com');
$I->fillField('price','0');
$I->click('submit_btn');
$I->see('Advertisement is updated');

// Read
$I->amOnPage('/jobs/some-nice-title-here.html');
$I->see('some nice title here','h1');
$I->see('http://open-classifieds.com','a');
$I->see('optional address','p');
$I->see('Phone: 949494949','a');

$I->amOnPage('/');
$I->click('Logout');

