<?php 

namespace ParlerSupport;

class SupportForm{
    
    public function enableForm(){
        if (isset($_POST['parler-support-submit-button'])){
            add_action('init', array($this, "processFormSubmission"));
            add_shortcode('parler-support-form', array($this, "returnSubmittedFormHTML"));
        }else{
            add_shortcode('parler-support-form', array($this, "returnFormHTML"));
        }
    }
    
    public function returnFormHTML(){
        $yourStingsMustBei18n = __('Your Strings must be i18n!', 'parler'); //https://codex.wordpress.org/I18n_for_WordPress_Developers
        
        $output =
<<<output
        
<form method = "post" >
$yourStingsMustBei18n
<input type = "submit" name = "parler-support-submit-button" id = "parler-support-submit-button" />
</form>

output;

return $output;

    }
    
    public function returnSubmittedFormHTML(){

        $thankYou = __('Thank you!', 'parler'); //https://codex.wordpress.org/I18n_for_WordPress_Developers
        $output =
<<<output
        
<form method = "post">
$thankYou
</form>

output;

return $output;

    }
    
    public function processFormSubmission(){
        $body = "";
        foreach ($_POST as $key => $value) {
            $body = $body . "Field ".htmlspecialchars($key)." Input ".htmlspecialchars($value)."<br>";
        }
        $to = 'jkarlinski@parler.com';
        $subject = 'The subject';
        $headers = array('Content-Type: text/html; charset=UTF-8','From: My Site Name &lt;support@example.com');
        
        wp_mail( $to, $subject, $body, $headers );
        
    }

}