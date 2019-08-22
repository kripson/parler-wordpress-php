<?php 

namespace Parler;

class Constants{
    
    public $parlerCSS = 'https://plugin.parler.com/production/parler-for-wordpress-public.css';
    public $parlerForWordPressPublic = "https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync";
    public $parlerReact = "https://plugin.parler.com/production/react.production.min.js";
    public $parlerDom = "https://plugin.parler.com/production/react-dom.production.min.js";
    public $syncInterval = 70000;
    public $syncApiEndpoint = "/wp-json/parler/webhookReceiver";
    public $parlerServerUrl = "https://home.parler.com";
    
    
    public function __construct(){
        global $parlerIsStaging;
        $parlerIsStaging = TRUE;
        if ($parlerIsStaging == TRUE){
            $this->setStaging();
        }
    }
    
    public function setStaging(){
        $this->syncInterval = 30;
        $this->parlerServerUrl = "http://ec2-3-85-85-70.compute-1.amazonaws.com";
    }
}