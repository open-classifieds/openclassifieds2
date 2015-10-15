<?php 
$I = new AcceptanceTester($scenario);
$I->am('the administrator');
$I->wantTo('enable disqus for Blog and FAQ');

$I->amOnPage('/oc-panel/auth/login');
$I->fillField('email','admin@reoc.lo');
$I->fillField('password','1234');
$I->click('auth_redirect');
$I->amOnPage('/oc-panel/');
$I->see('welcome admin');

// Disqus for Blog

$I->amOnPage('/oc-panel/Config/update/blog_disqus');
$I->fillField('#formorm_config_value','testoc');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

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
$I->see("comments powered by Disqus");

// Delete BLOG
$I->amOnPage('/oc-panel/Blog/');
$I->see('Blog Post Title');
$I->click('.btn.btn-danger.index-delete');
sleep(1);
$I->amOnPage('/oc-panel/Blog/');
$I->dontSee('Blog Post Title');
$I->amOnPage('/blog');
$I->dontSee('Blog Post Title');

$I->amOnPage('/oc-panel/Config/update/blog');
$I->fillField("#formorm_config_value",'0');
$I->click("//button[@type='submit']"); //click save
$I->see('Item updated. Please to see the changes delete the cache');

$I->amOnPage('/');
$I->dontSeeElement('a', ['href' => 'http://reoc.lo/blog']);

$I->amOnPage('/oc-panel/Config/update/blog_disqus');
$I->fillField('#formorm_config_value','');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

// Disqus for FAQ

$I->amOnPage('/oc-panel/Config/update/faq_disqus');
$I->fillField('#formorm_config_value','testoc');
$I->click('button[type="submit"]');
$I->see('Item updated. Please to see the changes delete the cache');

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
$I->see("comments powered by Disqus");



// Delete FAQ
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

