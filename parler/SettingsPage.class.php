<?php

namespace Parler;

class SettingsPage {  
    
    public function __construct() {
        if(isset($_POST['parler-settings-page-submit-button'])) {
            add_action('init', array($this, 'handleFormSubmission'));
        }
    }
    
    public function addNewSettings(){
        if ( ! current_user_can( 'moderate_comments' ) ) {
            return;
        }
        
        remove_menu_page( 'edit-comments.php' );
        //$LogoImage = new \Parler\LogoImage();
        //$iconDiv = $LogoImage->returnSvgUrl();
        add_menu_page(
            'Parler Settings',
            'Parler',
            'manage_options',
            'parler',
            array($this, 'getHTML'),
            'dashicons-testimonial',
            //$iconDiv,
            24
            ); 
    }		
        
    public function renderAdminSettingsPage(){
        add_options_page('Parler Settings', 'Parler', 'manage_options', 'parler', array($this, 'getHTML'));
        $this->addNewSettings();
    }
    
    public function getHTML(){
        echo ($this->getInternalPageHeader());
        echo ($this->getHTML_EnableCertainCPTs());
    }
    
    public function handleFormSubmission() {
        if(isset($_POST['cpts-enabled'])) {
            $CPTs = $_POST['cpts-enabled'];
            update_option('parler-enabled-post-types', $CPTs);
        }else{
            delete_option('parler-enabled-post-types');
        }
    }
    
    public function getInternalPageHeader() {
        $url = get_site_url() . '/wp-content/plugins/parler-wordpress-php/admin/images/Parler_Logo.svg';

$output = <<<output
        <div class="wrap">
            <div style="display: inline-block;">
                <img style="float: left;" width="42" height="42" src="$url" />
                <h1 style="float: left;"> &nbsp; Parler Settings</h1>
            </div>
output;

        //$output = $output . $this->getHTML_EnableCertainCPTs();
        return $output;
    }
    
    public function getHTML_EnableCertainCPTs() {
        
        //Strings the WordPress way for automatic l18n
        $enableParlerFor = __("Enable Parler for post types: ", "parler");
        $rowSelectorHTML = $this->getRowSelectorHTML();
        $saveChanges = __("Save Changes", "parler");
   
$output = <<<output
<form method = "post">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label title="" for="">$enableParlerFor</label>
            </th>
        </tr>
        $rowSelectorHTML
</table>
<input type="submit" name="parler-settings-page-submit-button" id="parler-settings-page-submit-button" class="button button-primary" value="$saveChanges">
</form>
output;

        return $output;
        
    }
    
    public function getRowSelectorHTML() {
        $CPTs = $this->getArrayOfCPTs();
        $activeCPTs = get_option('parler-enabled-post-types', array());
        $output = "";
        foreach($CPTs as $CPT) {
            if(in_array ( $CPT, $activeCPTs )) {
                $checked = "checked";
            }else{
                $checked = "";
            }
            $output = $output . "<tr><td>$CPT</td><td><input type = 'checkbox' name = 'cpts-enabled[]' value = '$CPT' $checked /></td></tr>";
        }
        return $output;
    }
    
    public function getArrayOfCPTs() {
        
        $CPTs = array();
        
        //we need to remove certain default CPTs, which aren't involved with the Parler app
        foreach ( get_post_types( '', 'names' ) as $post_type ) {
            if(
                $post_type != "attachment" and
                $post_type != "revision" and
                $post_type != "nav_menu_item" and
                $post_type != "custom_css" and
                $post_type != "customize_changeset" and
                $post_type != "oembed_cache" and
                $post_type != "user_request" and 
                $post_type != "wp_block"
             ) {
                array_push ($CPTs, $post_type);
            }
            
        }
        return $CPTs;
    }
   
}