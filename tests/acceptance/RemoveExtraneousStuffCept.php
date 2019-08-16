<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('See that extraneous elements are removed');
$I->loginAsAdmin();
$I->cli('plugin activate classic-editor');
$I->amOnPage("/wp-admin/post-new.php");
$I->dontSeeElement("#parler-add-toggle");
$I->cli('plugin deactivate classic-editor');
$I->amOnPage("/wp-admin/post-new.php");
$I->dontSeeElement("#parler-add-toggle");