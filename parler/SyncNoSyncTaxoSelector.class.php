<?php

namespace Parler;

class SyncNoSyncTaxoSelector{
    
    //the taxo selector is used to select which taxos are to be published to Parler
      

        
        //tabled
        //add_filter("manage_category_custom_column", array($this,'categoryColumns'), 10, 3);
        //add_filter("manage_edit-category_columns", array($this, 'categoryHeader')); 
        //add_filter("manage_post_tag_custom_column", array($this,'post_tagColumns'), 10, 3);
        //add_filter("manage_edit-post_tag_columns", array($this, 'post_tagHeader')); 
    
    
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
    
    public function stripBadItemsFromTaxoList(){
        
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
 
    public function createParlerTerms(){
        if (!taxonomy_exists('parler')) {
            //die("95");
            $activeCPTs = get_option('parler-enabled-post-types');
            //$activeCPTs = array("daily");

            if($activeCPTs){
                //die('98');
                //foreach($activeCPTs as $CPT){
                    
                    //var_dump($activeCPTs);die();
                    register_taxonomy(
                        'parler', 
                        $activeCPTs,
                        array(
                            'hierarchical' => TRUE,
                            'label' => __('Parler'),
                            'public' => TRUE, 'show_ui' => TRUE,
                            'query_var' => 'paler',
                            'rewrite' => array('slug' => 'parler'),
                            'show_in_rest' => TRUE,
                        )
                    );
                    
                //}
                
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
        }
    }
        

    public function returnSyncNoSyncRowElementHTML($objectID){
        $out = "
<div style = 'text-align:center;margin:auto;'>
    <input type = 'checkbox' name = 'parler-term-selector-checkbox-$objectID' value = '$objectID' checked>
<div />";
        return $out;
    }
    


}