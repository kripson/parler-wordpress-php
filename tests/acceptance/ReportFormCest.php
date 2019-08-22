<?php

class ReportFormCest
{
    //public $Url = "http://ec2-3-85-85-70.compute-1.amazonaws.com/report/";
    public $Url = "https://home.parler.com/report/";

    public function tryToSubmitDeleteForm(\AcceptanceTester $I)
    {
        $I->amOnUrl($this->Url);
        $I->see("Select the question type");
        $I->waitForElementVisible("#delete");
        $I->click("#delete");
        $I->waitForElementVisible("#delete-user-name");
        $I->fillField("#delete-user-name", "TEST Delete Form User Name");
        $I->click("parler-delete-ticket-submit-button");
    }

    public function tryToSubmitLockedForm(\AcceptanceTester $I)
    {
        $I->amOnUrl($this->Url);
        $I->see("Select the question type");
        $I->waitForElementVisible("#locked");
        $I->click("#locked");
        $I->waitForElementVisible("#locked-user-name");
        $I->fillField("#locked-user-name", "TEST LOCKED USER NAME");
        $I->click("parler-locked-ticket-submit-button");
    }

    public function tryToSubmitBugReportForm(\AcceptanceTester $I)
    {
        $I->amOnUrl($this->Url);
        $I->see("Select the question type");
        $I->waitForElementVisible("#bug");
        $I->click("#bug");
        $I->waitForElementVisible("#bug-user-name");
        $I->fillField("#bug-user-name", "TEST BUG USER NAME");
        $I->click("parler-bug-ticket-submit-button");
    }

    public function tryToSubmitPartnerForm(\AcceptanceTester $I)
    {
        $I->amOnUrl($this->Url);
        $I->see("Select the question type");
        $I->waitForElementVisible("#partner");
        $I->click("#partner");
        $I->waitForElementVisible("#partner-company-name");
        $I->fillField("#partner-company-name", "TEST PARTNER COMPANY NAME");
        $I->click("parler-partner-ticket-submit-button");
    }

    public function tryToSubmitMediaForm(\AcceptanceTester $I)
    {
        $I->amOnUrl($this->Url);
        $I->see("Select the question type");
        $I->waitForElementVisible("#media");
        $I->click("#media");
        $I->waitForElementVisible("#media-first-name");
        $I->fillField("#media-first-name", "TEST MEDIA FIRST NAME");
        $I->click("parler-media-ticket-submit-button");
    }

    public function tryToSubmitVerificationForm(\AcceptanceTester $I)
    {
        $I->amOnUrl($this->Url);
        $I->see("Select the question type");
        $I->waitForElementVisible("#verification");
        $I->click("#verification");
        $I->waitForElementVisible("#verification-account-handle");
        $I->fillField("#verification-account-handle", "TEST VERIFICCATION ACCOUNT HANDLE");
        $I->click("parler-public-ticket-submit-button");
    }
}