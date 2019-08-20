<?php

namespace ParlerAdmin;

class TicketEmailer{

    public function sendCustomEmail($body){

	    $to = "support@parler.com";
        $subject = "CATCHALL USER COMPLAINT";

        if($_POST['delete-user-name'] != "") {
	        $to = "jkarlinski@parler.com";
            $to = "account.deletion@parler.com";
            $subject = "ACCOUNT DELETION";
        }
        if($_POST['locked-user-name'] != ""){
	        $to = "jkarlinski@parler.com";
            $to = "support@parler.com";
            $subject = "LOCKED ACCOUNT";
        }
        if($_POST['bug-user-name'] != "") {
	        $to = "jkarlinski@parler.com";
            $to = "support@parler.com";
            $subject = "BUG REPORT";
        }
        if($_POST['partner-email'] != ""){
	        $to = "jkarlinski@parler.com";
            $to = "business@parler.com";
            $subject = "PARTNER INTEGRATION";
        }
        if($_POST['media-email'] != ""){
	        $to = "jkarlinski@parler.com";
            $to = "media@parler.com";
            $subject = "MEDIA INQUIRY";
        }
        if($_POST['verification-email'] != ""){
	        $to = "jkarlinski@parler.com";
            $to = "verifications@parler.com";
            $subject = "EMAIL VERIFICATION";
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');

        //wp_mail( $to, $subject, $body, $headers );
        return $subject;
    }
}