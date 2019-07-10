<?php

namespace Parler;

class SettingsPage{
    
    public function __construct(){
        if(isset($_POST['parler-settings-page-submit-button'])){
            $this->handleFormSubmission();
        }
    }
    
    public function handleFormSubmission(){
        die("asdfasdfasdfasdfasdasdfasdasdfasdfkjahsgdflkjahsdlf;kjhasdlkjhalskjdalksjdhlkajwhflkjwahelkf");
    }
    
    
    
    public function getHTML_EnableCertainCPTs(){
        
        //Strings the WordPress way for automatic l18n
        $enableParlerFor = __("Enable Parler for post types: ", "parler");
        $rowSelectorHTML = $this->getRowSelectorHTML();
        $saveChanges = __("Save Changes", "parler");
   
$output = <<<output
<from method = "post" />
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
    
    public function getRowSelectorHTML(){
        $CPTs = $this->getArrayOfCPTs();
        $output = "";
        foreach($CPTs as $CPT){
            $output = $output . "<tr><td>$CPT</td><td><input type = 'checkbox' name = '$CPT-checkbox' value = '1' checked /></td></tr>";
        }
        return $output;
    }
    
    public function getArrayOfCPTs(){
        
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
             ){
                array_push ($CPTs, $post_type);
            }
            
        }
        return $CPTs;
    }
   
}