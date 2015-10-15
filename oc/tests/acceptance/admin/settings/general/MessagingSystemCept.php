<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('activate and use messaging system between admin and john.');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

// Enable Messaging System
$I->amOnPage('/oc-panel/Config/update/messaging');
$I->fillField('#formorm_config_value','1');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// Send message to John Smith
$I->amOnPage('/jobs/title-for-the-ad.html');
$I->fillField('#message','My message to John Smith');
$I->fillField('#price','12');
$I->click('submit');
$I->see('Your message has been sent');

$I->amOnPage('/');
$I->click('Logout');

// Log in as John Smith to see the message
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->seeElement('.fa.fa-bell');
$I->see('You have 1 unread messages');
$I->see('title for the ad');

$I->amOnPage('/oc-panel/messages');
$I->seeElement('tr', ['class' => 'message']);
$I->see('Unread','span');
$I->see('admin','a');
$I->seeElement('.fa.fa-envelope');

$I->click('//a[@class="btn btn-xs btn-warning"]');
$I->seeElement('img', ['class' => 'img-rounded']);
$I->see('My message to John Smith');
$I->see('12.00');
$I->seeElement('form', ['method' => 'post']);

// Reply
$I->fillField('message','Here is my answer, admin');
$I->click('button[type="submit"]');
$I->see('Reply created.');

$I->amOnPage('/');
$I->click('Logout');

// See the reply and answer
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/messages');
$I->see('Unread','span');

$I->click('//a[@class="btn btn-xs btn-warning"]');
$I->see('Here is my answer, admin');

// Send TWO messages to John Smith
$I->amOnPage('/jobs/title-for-the-ad.html');
$I->fillField('#message','First message to John Smith');
$I->fillField('#price','1');
$I->click('submit');
$I->see('Your message has been sent');

$I->amOnPage('/jobs/title-for-the-ad.html');
$I->fillField('#message','Second message to John Smith');
$I->fillField('#price','2');
$I->click('submit');
$I->see('Your message has been sent');


$I->amOnPage('/');
$I->click('Logout');

// Log in as John Smith to see the messages
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->see('2','span');
$I->amOnPage('/oc-panel/messages');
$I->see('Unread','span');

$I->click('//a[@class="btn btn-xs btn-warning"]');
$I->see('First message to John Smith');
$I->see('Second message to John Smith');
$I->see('1.00');
$I->see('2.00');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

// Delete Message
$I->amOnPage('/oc-panel/messages/message/1');
$I->click('Delete');
$I->see('Done');
$I->see('You don’t have any messages yet.','h3');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

// Answer to deleted message to bring it back
$I->amOnPage('/oc-panel/messages/message/1');
$I->fillField('message','Did you delete my message?');
$I->click('button[type="submit"]');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/messages');
$I->see('Unread','span');

$I->amOnPage('/oc-panel/messages/message/1');
$I->fillField('message','Yes!');
$I->click('button[type="submit"]');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

// Mark message as spam, AS RECIPIENT
$I->amOnPage('/oc-panel/messages');
$I->see('Unread','span');

$I->amOnPage('/oc-panel/messages/message/1');
$I->click('Spam');
$I->see('Done');

$I->amOnPage('/oc-panel/messages?status=5');
$I->see('title for the ad');

// Try to contact again as admin
$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/messages/message/1');
$I->fillField('message','Can you see me..?');
$I->click('button[type="submit"]');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/oc-panel/messages');
$I->dontSee('Unread','span');

$I->amOnPage('/oc-panel/messages?status=5');
$I->see('title for the ad');

$I->amOnPage('/oc-panel/messages/message/1');
$I->fillField('message','Remove from spam!');
$I->click('button[type="submit"]');

$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/messages');
$I->see('Unread','span');


// Back to default
$I->amOnPage('/oc-panel/Config/update/messaging');
$I->fillField('#formorm_config_value','0');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->dontSee('Messages','a');

