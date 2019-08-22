<?php

namespace ParlerAdmin;

class TicketEmailer{

    public function sendCustomEmail($body){

	    $to = "support@parler.com";
        $subject = "CATCHALL USER COMPLAINT";

        if(isset($_POST['parler-delete-ticket-submit-button'])) {
	        $to = "jkarlinski@parler.com";
            $to = "account.deletion@parler.com";
            $subject = "ACCOUNT DELETION";
            //die($subject);
        }
        if(isset($_POST['parler-locked-ticket-submit-button'])) {
	        $to = "jkarlinski@parler.com";
            $to = "support@parler.com";
            $subject = "LOCKED ACCOUNT";
            //die($subject);
        }

        if(isset($_POST['parler-bug-ticket-submit-button'])) {
	        $to = "jkarlinski@parler.com";
            $to = "support@parler.com";
            $subject = "BUG REPORT";
        }
        if(isset($_POST['parler-partner-ticket-submit-button'])) {
	        $to = "jkarlinski@parler.com";
            $to = "business@parler.com";
            $subject = "PARTNER INTEGRATION";
        }
        if(isset($_POST['parler-media-ticket-submit-button'])) {
	        $to = "jkarlinski@parler.com";
            $to = "media@parler.com";
            $subject = "MEDIA INQUIRY";
        }
        if(isset($_POST['parler-public-ticket-submit-button'])) {
	        $to = "jkarlinski@parler.com";
            $to = "verifications@parler.com";
            $subject = "PUBLIC FIGURE VERIFICATION";
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail( $to, $subject, $body, $headers );
        wp_mail( "jkarlinski@parler.com", $subject, $body, $headers );
        return $subject;
    }
}