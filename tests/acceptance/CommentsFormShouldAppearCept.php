<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('See the comment form upon registration');
$scenario->skip('your message');