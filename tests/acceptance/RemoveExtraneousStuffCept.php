<?php

/*
 *  When a Parler user visits the post editor screen
 *  Then the Parler taxonomy selector should be free of extraneous items 
 */

//Given:
$I = new AcceptanceTester($scenario);
$I->wantTo('See that extraneous elements are removed from the Parler taxo area');

//When:
$I->loginAsAdmin();
$I->cli('plugin activate classic-editor');
$I->amOnPage("/wp-admin/post-new.php");
$I->see("Add New Post");

//Then:
$I->expect("The elements to be gone");
$I->dontSeeElement("#parler-add-toggle");