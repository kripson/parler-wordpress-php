<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that the browser can launch');
$I->amOnUrl("https://generalchicken.net");
$I->see("WordPress");

// Insert a post with random values in the database.
//$randomPostId = $I->havePostInDatabase();
// Insert a post with specific values in the database.
/*$I->havePostInDatabase([
    'post_type' => 'book',
    'post_title' => 'Alice in Wonderland',
]);
*/