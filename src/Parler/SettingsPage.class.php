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
        $LogoImage = new \Parler\LogoImage();
        $iconDiv = $LogoImage->returnSvgUrl();
        add_menu_page(
            'Parler Settings',
            'Parler',
            'manage_options',
            'parler',
            array($this, 'getHTML'),
            //'dashicons-testimonial',
            $iconDiv,
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
        echo ($this->getJS());
    }

    public function getJS(){
        $CPTs = $this->getArrayOfCPTs();
        $output = "";
        foreach($CPTs as $CPT) {
            $JS = <<<OUTPUT
<script>

jQuery(document).ready(function(){
    if(!(jQuery('#$CPT-enabler-checkbox')[0].checked)){
        jQuery(".$CPT-radio-input").fadeOut();
    }
    jQuery('#$CPT-enabler-checkbox').change(function() {
        if(this.checked) {
            jQuery(".$CPT-radio-input").fadeIn();
            jQuery("#$CPT-comments-radio")[0].checked = true;
        }else{
            jQuery(".$CPT-radio-input").fadeOut();
        }
    });
});
</script>
OUTPUT;
            $output = $output . $JS;
        }
    return $output;


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
	    $tableHeader = $this->getTableHeaderHTML();
        $rowSelectorHTML = $this->getRowSelectorHTML();
        $saveChanges = __("Save Changes", "parler");
   
$output = <<<output
<form method = "post">
	<!-- <p>You can change the settings for an individual post, in the post editor.</p> -->
    <table class="form-table">
		$tableHeader
        $rowSelectorHTML
	</table>
	<input type="submit" name="parler-settings-page-submit-button" id="parler-settings-page-submit-button" class="button button-primary" value="$saveChanges">
</form>
output;
        return $output;
        
    }

	public function getTableHeaderHTML(){
		$output = <<<OUTPUT
<tr valign="top">
	<td>Post Type</td><td>Enable Parler</td>
	<td>Default</td>
	<td>Default</td>
    <td>Default</td>
</tr>
OUTPUT;
		return $output;
	}
    
    public function getRowSelectorHTML() {
        $CPTs = $this->getArrayOfCPTs();
        $activeCPTs = get_option('parler-enabled-post-types', array());
        $bizRules = get_option('parler-biz-rules', array());
        //var_dump($bizRules);
        //var_dump($activeCPTs);
        //var_dump($CPTs);
        $output = "";

        //This loop will output rows for each post type:
        foreach($CPTs as $CPT) {
    $checked = ""; $commentFieldCheck = ""; $publishFieldCheck = ""; $nothingFieldCheck = "";
            if(in_array ( $CPT, $activeCPTs )) {
                $checked = "checked";
            }
            $bizRule = $CPT . "-default-parler-comment-field-enabled";
            if(in_array($bizRule, $bizRules)){
                $commentFieldCheck = "checked";
            }
            $bizRule = $CPT . "-default-parler-publish-enabled";
            if(in_array($bizRule, $bizRules)){
                $publishFieldCheck = "checked";
            }
            $bizRule = $CPT . "-default-parler-no-publish";
            if(in_array($bizRule, $bizRules)){
                $nothingFieldCheck = "checked";
            }
            $output = $output .
                      "<tr>
							<td>$CPT</td>
							<td><input type = 'checkbox' id = '$CPT-enabler-checkbox' name = 'cpts-enabled[]' value = '$CPT' $checked /></td>
							<td><input type = 'radio' id = '$CPT-comments-radio' class = '$CPT-radio-input' name = '$CPT-radio' value = 'default-parler-comment-field-enabled' $commentFieldCheck><span class = '$CPT-radio-input'>Parler Comments</span></td>
							<td><input type = 'radio' class = '$CPT-radio-input' name = '$CPT-radio' value = 'default-parler-publish-enabled' $publishFieldCheck><span class = '$CPT-radio-input'>Native WordPress Comments</span></td>
							<td><input type = 'radio' class = '$CPT-radio-input' name = '$CPT-radio' value = 'default-parler-no-publish' $nothingFieldCheck><span class = '$CPT-radio-input'>Don't Publish to Parler</span></td>
						</tr>";

        }

        return $output;
    }

	public function handleFormSubmission(){

        if(isset($_POST['cpts-enabled'])){
            $CPTs = $_POST['cpts-enabled'];
        }else{
            $CPTs = array();
        }
        $this->removeTagsFromAllCPTs();
        $bizRules = $this->getBizRules();
        update_option('parler-enabled-post-types', $CPTs);
        update_option('parler-biz-rules', $bizRules);

    }

        public function removeTagsFromAllCPTs(){
            $CPTs = $this->getArrayOfCPTs();
            $TaxonomyFeature = new TaxonomyFeature;
            foreach($CPTs as $CPT){
                $TaxonomyFeature->removeParlerTagsFromEntireCPT($CPT);
            }
        }

    //these are the rules that dictate how each CPT should behave for the site admin.
    //it is an array with the CPT in the beggining of the strings
    public function getBizRules(){
        if(isset($_POST['cpts-enabled'])){
            $CPTs = $_POST['cpts-enabled'];
        }else{
            $CPTs = array();
        }
        $TaxonomyFeature = new TaxonomyFeature;
        $termsArray = $TaxonomyFeature->returnArrayOfParlerTermIDs();
        //var_dump($termsArray['publish']);die();

		$bizRules = array();
           foreach($CPTs as $CPT){
           $bizRule = "$CPT-default-parler-comment-field-enabled";
           $radioName = $CPT . "-radio";
           if (isset($_POST[$radioName])) {
               if ($_POST[$radioName] == 'default-parler-comment-field-enabled') {
                   $bizRule = "$CPT-default-parler-comment-field-enabled";
                   $TaxonomyFeature->setTagsForParticularCPT($CPT, $termsArray['comments'] );
                   $TaxonomyFeature->setTagsForParticularCPT($CPT, $termsArray['publish'] );
               }
               if ($_POST[$radioName] == 'default-parler-publish-enabled') {
                   $bizRule = "$CPT-default-parler-publish-enabled";
                   $TaxonomyFeature->setTagsForParticularCPT($CPT, $termsArray['publish'] );
               }
               if ($_POST[$radioName] == 'default-parler-no-publish') {
                    $bizRule = "$CPT-default-parler-no-publish";
               }
           }
               if($bizRule != null){array_push($bizRules, $bizRule );}
           }
        return $bizRules;
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