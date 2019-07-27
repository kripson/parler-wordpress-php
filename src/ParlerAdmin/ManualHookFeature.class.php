<?php

namespace ParlerAdmin;

class ManualHookFeature{
    
    public function enableManualHookFeature(){
        add_shortcode('parler-manual-hook', array($this, 'manualHookShortcode'));
    }
    
    public function manualHookShortcode(){
        $output = <<<output
<form method = "post"  >
Site URL:<br />
<input type = "text" name = "parler-site-url" id = "parler-site-url" /><br />
<br />
Sender Email:<br />
<input type = "text" name = "parler-sender" id = "parler-sender" /><br />
<br />
<input type = "submit" /><br />

output;
        
        return $output;
        
    }
    
}