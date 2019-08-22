<?php

namespace Parler;

class LogoImage {
    
    public function returnParlerIconImgTag() {

        $HTML = "
        
<div id = 'parler-logo' class = 'parler-logo-small' style = 'text-align:center;margin:auto;' >
    <img src = '$URL' />
</div><!--end: #parler-logo -->

        ";

        $HTML = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images/Parler_Logo.svg";

        return $HTML;
    }
    
    public function returnImagesDirUrl(){
        $url = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images";
        
        return ($url);
        
    }
    
    public function returnSvgUrl() {
        $URL = $this->returnImagesDirUrl();
        $URL = $URL . "/Parler_Logo16x16px.svg";
        //die($URL);
        return $URL; 
    }
    
    public function returnParlerIconImgTagDiv() {
        $URL = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images/parler-logo.png";
        $HTML = "
        
<div id = 'parler-logo' class = 'parler-logo-small' style = 'text-align:center;margin:auto;' >
    <img src = '$URL' />
</div><!--end: #parler-logo -->
 
        ";
        return $HTML;
    }
    
}