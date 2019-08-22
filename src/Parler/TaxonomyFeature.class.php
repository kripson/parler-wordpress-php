<?php

namespace Parler;

class TaxonomyFeature{

	public function modifyAdminArea(){
        global $pagenow;
        if ( $pagenow == 'post-new.php' ) {
            $DefaultCheckboxChecker = new DefaultCheckboxChecker;
            add_filter('wp_terms_checklist_args', array($DefaultCheckboxChecker, 'classicEditorDefaultCheckboxes'), 10, 2);
            $DefaultCheckboxChecker->gutenbergEditorDefaultCheckboxes();
        }
        if(($pagenow == "post.php") or ( $pagenow == 'post-new.php')){
            $this->cleanupEditorHTML();
        }
    }

    public function cleanupEditorHTML(){
        add_action('admin_enqueue_scripts', array($this, 'doInjectJS'));
        add_action('admin_footer', array($this, 'doFooterInjection'));
    }

    public function doInjectJS(){
        $URL = get_site_url() . "/wp-content/plugins/parler-wordpress-php/src/Parler/post-new.js";
         wp_enqueue_script('parler-checker', $URL, array('jquery'));
    }
    
    public function doFooterInjection(){
         $IDs = $this->returnArrayOfParlerTermIDs();

         $publish = $IDs['publish'];
         $comments = $IDs['comments'];
                  
         $output = <<<OUTPUT
<script>
/*global jQuery*/
jQuery( document ).ready(function() {
    jQuery("#in-parler-$comments").click(function(){
        
       if(jQuery("#in-parler-$comments").attr('checked')) { 
        jQuery("#in-parler-$publish").attr('checked', 'checked');
        jQuery("#in-parler-$publish").attr('disabled','disabled');
        } else {
            jQuery("#in-parler-$publish").removeAttr('disabled');
}
    });
});
</script>
  <input type = "hidden" value = "$publish" name = "parlerPublish" id = "parlerPublish" />
OUTPUT;
        echo $output;

    }
    
    public function returnArrayOfParlerTermIDs($numbersOnly = null){
        $term1 =  term_exists( 'publish', 'parler');
        $term1 = intval($term1['term_id']);
        $term2 =  term_exists( 'comments', 'parler');
        $term2 = intval($term2['term_id']);
        $parlerTermIDs = array("publish" => $term1, "comments" => $term2);
        if($numbersOnly == TRUE){

            $result = array();
            foreach($parlerTermIDs as $key => $value){

                array_push($result, intval($parlerTermIDs[$key]));
            }
            $parlerTermIDs = $result;
        }
        return $parlerTermIDs;
    }

    public function removeParlerTagsFromEntireCPT($CPT = null){
	    //die($CPT);
        //die("removeParlerTagsFromEntireCPT");
	    if ($CPT == null){die("something is wrong");}

        //die("removeParlerTagsFromEntireSite");
        $args = array(
            //'post_status'   => 'publish',
            'post_type'     => $CPT,
        );
        
        // Custom query.
        $query = new \WP_Query( $args );

        $termIDsToBeRemoved = $this->returnArrayOfParlerTermIDs(true);

        // Check that we have query results.
        if ( $query->have_posts() ) {
            
            // Start looping over the query results.
            while ( $query->have_posts() ) {
                
                $query->the_post();
                $post_id = get_the_ID();
                foreach ($termIDsToBeRemoved as $term) {
                    wp_remove_object_terms($post_id, $term, 'parler');
                }

            }
        }
        
        // Restore original post data.
        wp_reset_postdata();

    }
    
    public function setTagsForParticularCPT($CPT, $termID){
        $args = array(
            'posts_per_page'   => -1,
            'post_status'   => 'publish',
            'post_type'     => $CPT,
        );
        $query = new \WP_Query( $args );
        if ( $query->have_posts() ) {
            
            // Start looping over the query results.
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id = get_the_ID();
                wp_set_post_terms($post_id, $termID, 'parler', TRUE);
            }
        }
        // Restore original post data.
        wp_reset_postdata();
    }
    
    public function categoryColumns($out, $column_name, $category_id) {
        $category = get_term($category_id, 'category');
        switch ($column_name) {
            case 'header_icon':
                $out = $this->returnSyncNoSyncRowElementHTML($category_id);
                break;
                
            default:
                break;
        }
        return $out;
    }
    
    public function post_tagColumns($out, $column_name, $post_tag_id) {
        $category = get_term($post_tag_id, 'category');
        switch ($column_name) {
            case 'header_icon':
                $out = $this->returnSyncNoSyncRowElementHTML($post_tag_id);
                break;
                
            default:
                break;
        }
        return $out;
    }
    
    public function categoryHeader($category_columns) {
        $LogoImage = new \Parler\LogoImage();
        $ParlerLogoImg = $LogoImage->returnParlerIconImgTagDiv();
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'description' => __('Description'),
            'slug' => __('Slug'),
            'posts' => __('Posts'),
            'header_icon' => $ParlerLogoImg,
        );
        return $new_columns;
    }
    
    public function post_tagHeader($post_tag_columns) {
        $LogoImage = new \Parler\LogoImage();
        $ParlerLogoImg = $LogoImage->returnParlerIconImgTagDiv();
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'description' => __('Description'),
            'slug' => __('Slug'),
            'posts' => __('Posts'),
            'header_icon' => $ParlerLogoImg,
        );
        return $new_columns;
    }
    
    public function stripBadItemsFromTaxoList() {
        
        //WordPress comes with several default taxonomies
        //We only want tags, categories, and any custom taxos
        $x = get_taxonomies(array(), 'names');
        if (($key = array_search("nav_menu", $x)) !== false) {
            unset($x["nav_menu"]);
        }
        if (($key = array_search("link_category", $x)) !== false) {
            unset($x["link_category"]);
        }
        if (($key = array_search("post_format", $x)) !== false) {
        }
            unset($x["post_format"]);
        
        //uncomment this line to see taonomies:
        //var_dump($x);die();
        
        return $x;
    }
 
    public function addParlerTerm($post_type){

        register_taxonomy(
            'parler',
            $post_type,
            array(
                'hierarchical' => TRUE,
                'label' => __('Parler'),
                'public' => TRUE, 'show_ui' => TRUE,
                'query_var' => 'paler',
                'rewrite' => array('slug' => 'parler'),
                'show_in_rest' => TRUE,
            )
            );
        wp_insert_term('Publish to Parler', 'parler',
            array(
                'slug'          => 'publish',
                'description'   => 'Publish a post to the Parler platform.',
            )
            );
        wp_insert_term('Enable Parler Comments', 'parler',
            array(
                'slug'          => 'comments',
                'description'   => 'Enable Parler comments.',
            )
            );
        
    }
    
    public function createParlerTerms( ) {
        if (!taxonomy_exists('parler')) {
            $activeCPTs = get_option('parler-enabled-post-types');
        //die('194');
            if($activeCPTs) {
                $CPTarray = array();
                foreach($activeCPTs as $post_type){
                    array_push($CPTarray, $post_type);

                }
                $this->addParlerTerm($CPTarray);
            }
        }
    }  

    public function returnSyncNoSyncRowElementHTML($objectID) {
        $out = "
<div style = 'text-align:center;margin:auto;'>
    <input type = 'checkbox' name = 'parler-term-selector-checkbox-$objectID' value = '$objectID' checked>
<div />";
        return $out;
    }
    
    public function addTagToAllPosts(){
        wp_set_post_terms( $post_id, $terms, $taxonomy, $append);
    }

}