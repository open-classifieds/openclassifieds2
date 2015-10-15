<?php 
$I = new AcceptanceTester($scenario);
$I->am("the admin");
$I->wantTo('edit roles and see the changes');


$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');



// USER

// Update
$I->amOnPage('/oc-panel/Role/update/1');
$I->unCheckOption('profile|*');
$I->checkOption('profile|index');
$I->checkOption('profile|changepass');
$I->checkOption('profile|edit');
$I->checkOption('profile|notifications');
$I->checkOption('profile|public');
$I->checkOption('profile|ads');
$I->checkOption('profile|deactivate');
$I->checkOption('profile|activate');
$I->checkOption('profile|update');
$I->checkOption('profile|confirm');
$I->checkOption('profile|stats');
$I->click('submit');
$I->see('Item updated');

$I->amOnPage('/');
$I->click('Logout');


//login as a user
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');

// Read
// Subscribe - Unsubscribe
$I->amOnPage('/');
$I->click('a[href="http://reoc.lo/oc-panel/profile/subscriptions"]');
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Profile subscriptions');
// Image
$I->amOnPage('/oc-panel/profile/edit');
$I->attachFile('input[type="file"]', 'photo.jpg');
$I->click("//form[contains(@action,'http://reoc.lo/oc-panel/profile/image')]/div/div/button[@type='submit']");
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Profile image');
// Favorites
$I->amOnPage('/');
$I->click('a[href="http://reoc.lo/oc-panel/profile/favorites"]');
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Profile favorites');
// Orders
$I->amOnPage('/');
$I->click('a[href="http://reoc.lo/oc-panel/profile/orders"]');
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Profile orders');

// Back to default
$I->amOnPage('/');
$I->click('Logout');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Role/update/1');
$I->checkOption('profile|*');
$I->unCheckOption('profile|index');
$I->unCheckOption('profile|changepass');
$I->unCheckOption('profile|edit');
$I->unCheckOption('profile|notifications');
$I->unCheckOption('profile|public');
$I->unCheckOption('profile|ads');
$I->unCheckOption('profile|deactivate');
$I->unCheckOption('profile|activate');
$I->unCheckOption('profile|update');
$I->unCheckOption('profile|confirm');
$I->unCheckOption('profile|stats');
$I->click('submit');
$I->see('Item updated');

$I->amOnPage('/');
$I->click('Logout');


//login as a user
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','gazzasdasd@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');


// Read
// Subscribe - Unsubscribe
$I->amOnPage('/');
$I->click('a[href="http://reoc.lo/oc-panel/profile/subscriptions"]');
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Profile subscriptions');
$I->seeElement('.alert.alert-info');
$I->see('No Subscriptions');
// Image
$I->amOnPage('/oc-panel/profile/edit');
$I->attachFile('input[type="file"]', 'photo.jpg');
$I->click("//form[contains(@action,'http://reoc.lo/oc-panel/profile/image')]/div/div/button[@type='submit']");
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Profile image');
$I->seeElement('.alert.alert-success');
$I->see('photo.jpg Image is uploaded.');
$I->click('photo_delete');
$I->seeElement('.alert.alert-success');
$I->see('Photo deleted.');
// Favorites
$I->amOnPage('/');
$I->click('a[href="http://reoc.lo/oc-panel/profile/favorites"]');
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Profile favorites');
$I->see('My favorites','h1');
// Orders
$I->amOnPage('/');
$I->click('a[href="http://reoc.lo/oc-panel/profile/orders"]');
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Profile orders');
$I->see('Orders','h1');

$I->amOnPage('/');
$I->click('Logout');




// TRANSLATOR

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Role/update/5');
$I->unCheckOption('translations|*');
$I->unCheckOption('content|*');
$I->click('submit');
$I->see('Item updated');

$I->amOnPage('/oc-panel/User/update/4');
$I->selectOption('formorm[id_role]','5');
$I->click('formorm[submit]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->click('Logout');

//login as translator
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/oc-panel/translations');
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Translations index');
$I->dontSee('Translations','h1');
$I->dontSee('Translations files available in the system.','p');

$I->amOnPage('/oc-panel/content/email');
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Content email');
$I->dontSee('Email','h1');
$I->dontSee('Locale','label');

$I->amOnPage('/oc-panel/content/page');
$I->seeElement('.alert.alert-danger');
$I->see('You do not have permissions to access Content page');
$I->dontSee('Page','h1');
$I->dontSee('Locale','label');

$I->amOnPage('/');
$I->click('Logout');

// Back to default

// login as admin
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Role/update/5');
$I->checkOption('translations|*');
$I->checkOption('content|*');
$I->click('submit');
$I->see('Item updated');

$I->amOnPage('/');
$I->click('Logout');

//login as translator
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/oc-panel/translations');
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Translations index');
$I->see('Translations','h1');
$I->see('Translations files available in the system.','p');

$I->amOnPage('/oc-panel/content/email');
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Content email');
$I->see('Email','h1');
$I->see('Locale','label');

$I->amOnPage('/oc-panel/content/page');
$I->dontSeeElement('.alert.alert-danger');
$I->dontSee('You do not have permissions to access Content page');
$I->see('Page','h1');
$I->see('Locale','label');

$I->amOnPage('/');
$I->click('Logout');

// login as admin
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');




// MODERATOR

$I->amOnPage('/oc-panel/User/update/4');
$I->selectOption('formorm[id_role]','7');
$I->click('formorm[submit]');
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/oc-panel/Role/update/7');
$I->unCheckOption('ad|*');
$I->unCheckOption('category|*');
$I->unCheckOption('translations|*');
$I->unCheckOption('content|*');
$I->unCheckOption('location|*');
$I->unCheckOption('menu|*');
$I->click('submit');
$I->see('Item updated');

$I->amOnPage('/');
$I->click('Logout');

//login as moderator
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/oc-panel/ad');
//$I->dontSee('Advertisements','h1'); there is "My Advertisements" h1 on redirect page. not an issue.
$I->dontSee('ALL ADS','a');
$I->dontSee('SPAM','a');
$I->dontSee('UNAVAILABLE','a');
$I->dontSee('UNCONFIRMED','a');
$I->dontSeeCurrentUrlEquals('/oc-panel/ad');

$I->amOnPage('/oc-panel/category');
$I->seeElement('.alert.alert-danger');
$I->dontSee('Categories','h1');
$I->dontSee('NEW CATEGORY','a');
$I->dontSee('Change the order of your categories. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).','p');
$I->dontSee('Add names for multiple categories, for each one push enter.');
$I->dontSee('Please use the correct CSV format');
$I->dontSeeElement('.drag-icon');
$I->dontSeeCurrentUrlEquals('/oc-panel/category');

$I->amOnPage('/oc-panel/location');
$I->seeElement('.alert.alert-danger');
$I->dontSee('Locations','h1');
$I->dontSee('NEW LOCATION','a');
$I->dontSee('Change the order of your locations. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).','p');
$I->dontSee('Add names for multiple locations, for each one push enter.');
$I->dontSee('Please use the correct CSV format');
$I->dontSee('IMPORT CONTINENTS','button');
$I->dontSeeElement('.drag-icon');
$I->dontSeeCurrentUrlEquals('/oc-panel/location');

$I->amOnPage('/oc-panel/translations');
$I->seeElement('.alert.alert-danger');
$I->dontSee('Translations','h1');
$I->dontSee('SCAN','a');
$I->dontSee('Translations files available in the system.','p');
$I->dontSee('ACTIVATE','a');
$I->dontSee('hi_IN','td');
$I->dontSee('hu_HU','td');
$I->dontSee('IMPORT CONTINENTS','button');
$I->dontSeeElement('.drag-icon');
$I->dontSeeCurrentUrlEquals('/oc-panel/translations');

$I->amOnPage('/oc-panel/content/page');
$I->seeElement('.alert.alert-danger');
$I->dontSee('Page','h1');
$I->dontSeeCurrentUrlEquals('/oc-panel/page');

$I->amOnPage('/oc-panel/content/email');
$I->seeElement('.alert.alert-danger');
$I->dontSee('Email','h1');
$I->dontSeeCurrentUrlEquals('/oc-panel/email');

$I->amOnPage('/oc-panel/menu');
$I->seeElement('.alert.alert-danger');
$I->dontSee('Custom menu','h1');
$I->dontSee('Create Menu Item','h3');
$I->dontSee('Menu type','button');
$I->dontSeeCurrentUrlEquals('/oc-panel/menu');

$I->amOnPage('/');
$I->click('Logout');


// Back to default

// login as admin
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/Role/update/7');
$I->checkOption('ad|*');
$I->checkOption('category|*');
$I->checkOption('content|*');
$I->checkOption('translations|*');
$I->checkOption('location|*');
$I->checkOption('menu|*');
$I->click('submit');
$I->see('Item updated');

$I->amOnPage('/');
$I->click('Logout');

//login as moderator
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','john@gmail.com');
$I->fillField('password','1234');
$I->click('auth_redirect');

$I->amOnPage('/oc-panel/ad');
$I->dontSeeElement('.alert.alert-danger');
$I->see('Advertisements','h1');
$I->see('ALL ADS','a');
$I->see('SPAM','a');
$I->see('UNAVAILABLE','a');
$I->see('UNCONFIRMED','a');
$I->seeCurrentUrlEquals('/oc-panel/ad');

$I->amOnPage('/oc-panel/category');
//$I->dontSeeElement('.alert.alert-danger');
$I->see('Categories','h1');
$I->see('NEW CATEGORY','a');
$I->see('Change the order of your categories. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).','p');
$I->see('Add names for multiple categories, for each one push enter.');
$I->see('Please use the correct CSV format');
$I->seeElement('.drag-icon');
$I->seeCurrentUrlEquals('/oc-panel/category');

$I->amOnPage('/oc-panel/location');
//$I->dontSeeElement('.alert.alert-danger');
$I->see('Locations','h1');
$I->see('NEW LOCATION','a');
$I->see('Change the order of your locations. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).','p');
$I->see('Add names for multiple locations, for each one push enter.');
$I->see('Please use the correct CSV format');
$I->seeElement('button', ['id' => 'auto_locations_import']);
$I->seeElement('.drag-icon');
$I->seeCurrentUrlEquals('/oc-panel/location');

$I->amOnPage('/oc-panel/translations');
$I->dontSeeElement('.alert.alert-danger');
$I->see('Translations','h1');
$I->see('SCAN','a');
$I->see('Translations files available in the system.','p');
$I->see('ACTIVATE','a');
$I->see('hi_IN','td');
$I->see('hu_HU','td');
$I->seeCurrentUrlEquals('/oc-panel/translations');

$I->amOnPage('/oc-panel/content/page');
$I->dontSeeElement('.alert.alert-danger');
$I->see('Page','h1');
$I->seeCurrentUrlEquals('/oc-panel/content/page');

$I->amOnPage('/oc-panel/content/email');
$I->dontSeeElement('.alert.alert-danger');
$I->see('Email','h1');
$I->seeCurrentUrlEquals('/oc-panel/content/email');

$I->amOnPage('/oc-panel/menu');
$I->dontSeeElement('.alert.alert-danger');
$I->see('Custom menu','h1');
$I->see('Create Menu Item','h3');
$I->see('Menu type','button');
$I->seeCurrentUrlEquals('/oc-panel/menu');

$I->amOnPage('/');
$I->click('Logout');

// login as admin
$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

$I->amOnPage('/oc-panel/User/update/4');
$I->selectOption('formorm[id_role]','1');
$I->click('formorm[submit]');
$I->see('Item updated. Please to see the changes delete the cache');