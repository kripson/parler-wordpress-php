<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that the post title is centered');

//When:
//$I->amOnUrl("https://home.parler.com/center-test-no-css-added/");
$I->amOnUrl("https://home.parler.com/center-test/");

//Then:
$I->expect('the title to be centered');
$I->seeElement('.parler-center');