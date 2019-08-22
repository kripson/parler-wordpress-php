<?php

class ReportFormCest
{
    // test
    public function tryToTest(\AcceptanceTester $I)
    {
        $I->amOnPage("/report");
        $I-see("Please select the question type");
    }

