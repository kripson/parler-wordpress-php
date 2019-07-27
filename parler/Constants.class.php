<?php 

namespace Parler;

class Constants{
    
    public $parlerCSS = 'https://plugin.parler.com/staging/parler-for-wordpress-public.css';
    public $parlerForWordPressPublic = "https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync";
    public $parlerReact = "https://plugin.parler.com/production/react.production.min.js";
    public $parlerDom = "https://plugin.parler.com/production/react-dom.production.min.js";
    
    public $parlerServerUrl = "http://test2.base10.io/wp-json/parler/webhookReceiver/";
    //public $parlerServerUrl = "http://localhost:8888/wp-json/parler/webhookReceiver/";
    //$parlerServerUrl = "https://generalchicken.net/";
    //$ParlerUrl = "https://par.pw/";
    //$ParlerUrl = "https://staging.par.pw/";
    
    
    /*
    wp_enqueue_script('parler-for-wordpress-css', 'https://plugin.parler.com/staging/parler-for-wordpress-public.css', array(), "1.3.1", true);
    wp_enqueue_script('parler-for-wordpress-public', "https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync");
    wp_enqueue_script('parler-react', "https://plugin.parler.com/production/react.production.min.js");
    wp_enqueue_script('parler-dom', "https://plugin.parler.com/production/react-dom.production.min.js");
    */
    
    
    public function setStaging(){
    }
}