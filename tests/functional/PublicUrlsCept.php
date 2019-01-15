<?php use App\Tests\FunctionalTester;

$I = new FunctionalTester($scenario);
$I->am('Anonymous');
$I->wantTo('Open public urls and see requested page');

$publicUrls = [
    '/en/blog/posts/test-post-to-demonstrate-bug',
    '/en/blog/bug-posts/test-post-to-demonstrate-bug',
    '/',
    '/en/blog/',
    '/en/login',
];

foreach ($publicUrls as $url) {
    $I->amOnPage($url);
    $I->seeResponseCodeIs(200);
    $I->seeCurrentUrlEquals($url);
}
