<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that the browser can launch');

//When:
$I->amOnUrl("https://generalchicken.net");

//Then:
$I->expect('the General Chicken website to pop up');
$I->see("WordPress");