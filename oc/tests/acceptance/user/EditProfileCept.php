<?php 
$I = new AcceptanceTester($scenario);

//login as a user
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
//edit profile on dropdown menu
$I->wantTo('1) see the profile edit page, 2) change my name, my email and add a description, 3) change my passwd, 4) upload a profile pic, 5) See those changes on public profile'); 

// 1)
$I->wantTo('1) see the profile edit page');
$I->lookForwardTo('see "Edit Profile" header');
$I->click("a[href='http://reoc.lo/oc-panel/profile/edit']");
$I->see('edit profile','h3');
$I->dontSee('homepage');
$I->dontSee('my advertisements','h1');
$I->seeInCurrentUrl('/profile/edit');

// 2)
$I->wantTo('2) change my name, my email and add a description');
$I->lookForwardTo('see a successful message');
$I->fillField('#name','user');
$I->fillField("#email", 'user@user.com');
$I->fillField("#description", 'Hello!!!');
$I->click("button[type=submit]");
$I->see('You have successfully changed your data');

//repeat 2)
$I->wantTo('2) change my name, my email and remove description');
$I->lookForwardTo('see a successful message');
$I->fillField('#name','Gary Doe');
$I->fillField("#email", 'gazzasdasd@reoc.lo');
$I->fillField("#description", '');
$I->click("button[type=submit]");
$I->see('You have successfully changed your data');

// 3)
$I->wantTo('3) change my passwd');
$I->lookForwardTo('see a successful message');
$I->fillField('password1','user');
$I->fillField("password2", 'user');
$I->click("//form[contains(@action,'http://reoc.lo/oc-panel/profile/changepass')]/div/div/button[@type='submit']");
$I->see('Password is changed');

// repeat 3)
$I->wantTo('3) change my passwd back');
$I->lookForwardTo('see a successful message');
$I->fillField('password1','1234');
$I->fillField("password2",'1234');
$I->click("//form[contains(@action,'http://reoc.lo/oc-panel/profile/changepass')]/div/div/button[@type='submit']");
$I->see('Password is changed');

// 4)
$I->wantTo('4) upload a pic and then delete it');
$I->lookForwardTo('see a successful message');
$I->attachFile('input[type="file"]', 'photo.jpg');
$I->click("//form[contains(@action,'http://reoc.lo/oc-panel/profile/image')]/div/div/button[@type='submit']");
$I->see('Image is uploaded.');

// Delete picture
$I->wantTo('delete profile picture');
$I->lookForwardTo('see a successful message');
$I->click('photo_delete');
$I->see('Photo deleted.');