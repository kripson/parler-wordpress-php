<?php
$I = new AcceptanceTester($scenario);
$scenario->skip('duh');
$I->wantTo('Confirm Parler comment form appears on site');


$links = array (
    "http://ec2-3-85-85-70.compute-1.amazonaws.com/stub-1/"
  );

foreach ($links as $link){
    $I->expect("To see the Parler React app on url: $link");
    $I->amOnUrl($link);
    $I->wait(3);
    $I->executeJS("window.scrollTo(0, document.body.scrollHeight)");
    $I->wait(3);
    $I->canSeeElement('.parler_logo');
 }