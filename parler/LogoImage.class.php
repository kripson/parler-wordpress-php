<?php

namespace Parler;

class LogoImage {
    
    public function returnParlerIconImgTag() {
        $URL = get_site_url() . "/wp-content/plugins/parler-wordpress-php/admin/images/parler-logo.png";
        $HTML = "
        
<div id = 'parler-logo' class = 'parler-logo-small' style = 'text-align:center;margin:auto;' >
    <img src = '$URL' />
</div><!--end: #parler-logo -->

        ";
        $HTML = "http://localhost:8888/wp-content/plugins/parler-wordpress-php/admin/images/Parler_Logo.svg";
        return $HTML;
    }
    
    public function returnImagesDirUrl(){
        $file = dirname(dirname( __FILE__));

        
        // Get correct URL and path to wp-content
        $content_url = untrailingslashit( dirname( dirname( get_stylesheet_directory_uri() ) ) );
        $content_dir = untrailingslashit( WP_CONTENT_DIR );
        
        // Fix path on Windows
        $file = wp_normalize_path( $file );
        $content_dir = wp_normalize_path( $content_dir );
        
        $url = str_replace( $content_dir, $content_url, $file );
        $url = $url . "/admin/images";
        
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