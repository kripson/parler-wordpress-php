<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that extraneous elements are removed');
$I->loginAsAdmin();
$I->amOnPage("/wp-admin/post-new.php");
$I->see('Add New Post');
$I->dontSeeElement("#parler-add-toggle");