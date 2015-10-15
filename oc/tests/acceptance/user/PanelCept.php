<?php 
$I = new AcceptanceTester($scenario);

//login as a user
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->am("a user");
$I->wantTo('visit pages on the user dropdown menu');

$I->wantTo('see the panel'); //green profile button
$I->lookForwardTo('see the title "my advertisements" and url contains "/myads"');
$I->click("a[href='http://reoc.lo/oc-panel']");
$I->see('my advertisements','h1');
$I->dontSee('homepage');
$I->seeInCurrentUrl('/myads');

$I->amOnPage('/'); //back on homepage

$I->wantTo('see the my ads'); //my advertisements on dropdown menu
$I->lookForwardTo('see the title "my advertisements" and url contains "/myads"');
$I->click("a[href='http://reoc.lo/oc-panel/myads']");
$I->see('my advertisements','h1');
$I->dontSee('homepage');
$I->seeInCurrentUrl('/myads');

$I->amOnPage('/'); //back on homepage

$I->wantTo('see the my favorites'); //my favorites on dropdown menu
$I->lookForwardTo('see the title "my favorites" and url contains "/profile/favorites"');
$I->click("a[href='http://reoc.lo/oc-panel/profile/favorites']");
$I->see('my favorites','h1');
$I->dontSee('homepage');
$I->dontSee('my advertisements','h1');
$I->seeInCurrentUrl('/profile/favorites');

$I->amOnPage('/'); //back on homepage

$I->wantTo('see the my payments/orders'); //my payments on dropdown menu
$I->lookForwardTo('see the title "orders" and url contains "/profile/orders"');
$I->click("a[href='http://reoc.lo/oc-panel/profile/orders']");
$I->see('orders','h1');
$I->dontSee('homepage');
$I->dontSee('my advertisements','h1');
$I->seeInCurrentUrl('/profile/orders');

$I->amOnPage('/'); //back on homepage

$I->wantTo('see the edit profile page'); //subscriptions on dropdown menu
$I->lookForwardTo('see the string "My subscriptions" on breadcrumb and url contains "/profile/subscriptions"');
$I->click("a[href='http://reoc.lo/oc-panel/profile/subscriptions']");
$I->see('my subscriptions','li');
$I->dontSee('homepage');
$I->dontSee('my advertisements','h1');
$I->seeInCurrentUrl('/profile/subscriptions');


$I->click('Logout'); 




