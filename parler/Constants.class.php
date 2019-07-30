<?php 

namespace Parler;

class Constants{
    
    public $parlerCSS = 'https://plugin.parler.com/production/parler-for-wordpress-public.css';
    public $parlerForWordPressPublic = "https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync";
    public $parlerReact = "https://plugin.parler.com/production/react.production.min.js";
    public $parlerDom = "https://plugin.parler.com/production/react-dom.production.min.js";
    
    //production:
    //public $parlerServerUrl = "https://par.pw";
    //public $syncInterval = 82800;
    
    //staging:
    public $parlerServerUrl = "https://staging.par.pw";
    public $syncInterval = 30;
    
    
    public $syncApiEndpoint = "/v1/wp-plugin/sync";
    public $singlePostApiEndpoint = "/v1/wp-plugin/post";
    
    public $parlerWPadmin = "";

}

