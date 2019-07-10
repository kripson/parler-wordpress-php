<?php

namespace Parler;

class LogoImage{
    
    public function returnParlerIconImgTag(){
        $URL = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images/parler-logo.png";
        $HTML = "
        
<div id = 'parler-logo' class = 'parler-logo-small' style = 'text-align:center;margin:auto;' >
    <img src = '$URL' />
</div><!--end: #parler-logo -->

        ";
        $HTML = "http://localhost:8888/wp-content/plugins/parler-wordpress-php/admin/images/Parler_Logo.svg";
        return $HTML;
    }
    
    public function returnSvgUrl(){
        $URL = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images/Parler_Logo16x16px.svg";
        return $URL; 
    }
    
    public function returnParlerIconImgTagDiv(){
        $URL = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images/parler-logo.png";
        $HTML = "
        
<div id = 'parler-logo' class = 'parler-logo-small' style = 'text-align:center;margin:auto;' >
    <img src = '$URL' />
</div><!--end: #parler-logo -->
 
        ";
        return $HTML;
    }
    
}