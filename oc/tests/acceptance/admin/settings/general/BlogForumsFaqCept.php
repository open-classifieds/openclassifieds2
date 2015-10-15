<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('crud a blog post');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

///////////////////////////////////////////////////////////////
// activate blog from settings -> general -> all configurations

$I->wantTo('activate blog');
$I->amOnPage('/oc-panel/Config/update/blog');
$I->fillField("#formorm_config_value",'1');
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->seeElement('a', ['href' => 'http://reoc.lo/blog']);

// Create
$I->amOnPage('/oc-panel/Blog/create');
$I->fillField('#formorm_title','Blog Post Title');
$I->fillField('#formorm_description','This is my test post on my Blog.');
$I->checkOption('formorm[status]');
$I->click("submit"); //click create

// Read
$I->amOnPage('/blog');
$I->see('Blog Post Title');
$I->click("a[href='http://reoc.lo/blog/blog-post-title.html']"); //click create
$I->see('Blog Post Title','h1');
$I->see('This is my test post on my Blog');

// Update
$I->amOnPage('/blog');
$I->click('Edit','a');
$I->fillField('#formorm_description','This is the updated description of my test post.');
$I->click("submit"); //click create
$I->amOnPage('/blog/blog-post-title.html');
$I->see('Blog Post Title','h1');
$I->see('This is the updated description of my test post.');

// Delete
$I->amOnPage('/oc-panel/Blog/');
$I->see('Blog Post Title');
$I->click('.btn.btn-danger.index-delete');
$I->amOnPage('/oc-panel/Blog/');
$I->dontSee('Blog Post Title');
$I->amOnPage('/blog');
$I->dontSee('Blog Post Title');

$I->amOnPage('/oc-panel/Config/update/blog');
$I->fillField("#formorm_config_value",'0');
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontseeElement('a', ['href' => 'http://reoc.lo/blog']);

/////////////////////////////////////////////////////////////////
// activate forums from settings -> general -> all configurations

$I->wantTo('activate forums');
$I->amOnPage('/oc-panel/Config/update/forums');
$I->fillField("#formorm_config_value",'1');
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->seeElement('.glyphicon.glyphicon-tag');

// Create
$I->amOnPage('/oc-panel/forum/create');
$I->fillField('#name','My Forum');
$I->fillField('#description','Description for My Forum');
$I->click("submit"); //click create
$I->amOnPage('/forum/my-forum');
$I->click('New Topic','a');
$I->fillField('#title','My New Topic');
$I->fillField('#description','This is my New Topic');
$I->click("submit"); //click Publish new topic
$I->amOnPage('/forum/my-forum/my-new-topic.html');
$I->click('a[href="#reply_form"]');
$I->fillField('textarea[name="description"]','This is my reply on the New Topic!');
$I->click("submit"); //click Reply
$I->see('Reply added, thanks!');

// Read
$I->amOnPage('/forum');
$I->see('My Forum');
$I->amOnPage('/forum/my-forum');
$I->click('a[href="http://reoc.lo/forum/my-forum/my-new-topic.html"]');
$I->see('My New Topic','h1');
$I->see('This is my New Topic','p');
$I->see('This is my reply on the New Topic!','p');

// Update
$I->amOnPage('/forum/my-forum');
$I->click('a[class="label label-warning"]');
$I->fillField('#title','My Updated Topic');
$I->fillField('#description','This is my New Updated Topic');
$I->click("submit"); //click Update
$I->see('Topic is updated.');

$I->amOnPage('/forum/my-forum/my-new-topic.html');
$I->see('My Updated Topic','h1');
$I->see('This is my New Updated Topic','p');
$I->see('This is my reply on the New Topic!','p');

// Delete
$I->amOnPage('/oc-panel/topic');
$I->click(".btn.btn-danger.index-delete"); //Delete Reply

$I->amOnPage('/oc-panel/forum');
$I->click(".drag-action.index-delete"); //Delete Forum

$I->amOnPage('/forum');
$I->dontsee('My Forum');

$I->amOnPage('/oc-panel/Config/update/forums');
$I->fillField("#formorm_config_value",'0'); // Deactivates forums
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSeeElement('.glyphicon.glyphicon-tag');

/////////////////////////////////////////////////////////////////
// activate faq from settings -> general -> all configurations

$I->wantTo('activate faq');
$I->amOnPage('/oc-panel/Config/update/faq');
$I->fillField("#formorm_config_value",'1');
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->seeElement('a', ['href' => 'http://reoc.lo/faq']);

// Create
$I->amOnPage('/oc-panel/content/create?type=help');
$I->fillField('#title','My faq name');
$I->fillField('#description','Description for My faq');
$I->checkOption('status');
$I->click("submit"); //click create

// Read
$I->amOnPage('/faq');
$I->see('My faq');
$I->click('a[href="http://reoc.lo/faq/my-faq-name.html"]');
$I->see('My faq','h1');
$I->see('Description for My faq');

// Update
$I->amOnPage('/oc-panel/content/help');
$I->click('.drag-action.ajax-load');
$I->fillField('#title','My updated faq name');
$I->fillField('#description','Updated description for My faq');
$I->click("submit"); //click edit
$I->see('help is edited');

$I->amOnPage('/faq/my-faq-name.html');
$I->see('My updated faq name','h1');
$I->see('Updated description for My faq');

// Delete
$I->amOnPage('/oc-panel/content/help');
$I->click('.drag-action.index-delete');
$I->amOnPage('/faq');
$I->see('We do not have any FAQ-s');

$I->amOnPage('/oc-panel/Config/update/faq'); // Deactivate FAQs
$I->fillField("#formorm_config_value",'0');
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSeeElement('a', ['href' => 'http://reoc.lo/faq']);
