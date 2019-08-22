<?php

namespace Parler;

class DefaultCheckboxChecker{

    public $termParlerPublishId;

    public $termParlerCommentsId;

    public $CPTsEnabled = array();

    public $CPTsWithPublishTag = array();

    public $CPTsWithCommentTag = array();

    public function handleClassicEditor(){

        //gutenbergEditorDefaultCheckboxes
    }

    public function classicEditorDefaultCheckboxes( $args, $post_id ) {
        $postType = $this->returnCurrentPostType();
        $myCustomTaxoName = "parler";
        //die("classicEditorDefaultCheckboxes");
        if($args["taxonomy"] == $myCustomTaxoName){
            //die("classicEditorDefaultCheckboxes");
            $parlerTermIDs = $this->returnArrayOfParlerTermIDs();
            //this term will checkbox "checked":
            $args['selected_cats'] = array();
            $bizRules = get_option('parler-biz-rules', array());

            //If there is a rule for comments, check both fields
            $bizRule = "$postType-default-parler-comment-field-enabled";
            if(in_array($bizRule, $bizRules)) {
                array_push($args['selected_cats'], $parlerTermIDs['comments']);
                array_push($args['selected_cats'], $parlerTermIDs['publish']);
            }

            //check only the publish field
            $bizRule = "$postType-default-parler-publish-enabled";
            if(in_array($bizRule, $bizRules)) {
                $args['selected_cats'] = array();
                array_push($args['selected_cats'], $parlerTermIDs['publish']);
            }
            return $args;
        }
        return $args;
    }

    public function gutenbergEditorDefaultCheckboxes() {


        $currentPostType = $this->returnCurrentPostType();
        add_filter("rest_prepare_$currentPostType", function ($response) {
            $currentPostType = $this->returnCurrentPostType();
            $parlerTermIDs = $this->returnArrayOfParlerTermIDs();
            $data = array();

            $parlerTermIDs['publish'] = (int)$parlerTermIDs['publish'];
            $parlerTermIDs['comments'] = (int)$parlerTermIDs['comments'];
            $bizRules = get_option('parler-biz-rules', array());
            //If there is a rule for comments, check both fields
            $bizRule = "$currentPostType-default-parler-comment-field-enabled";
            if(in_array($bizRule, $bizRules)) {
                array_push($data, $parlerTermIDs['publish']);
                array_push( $data,   $parlerTermIDs['comments']);
            }
            //var_dump($data);die();
            //check only the publish field
            $bizRule = "$currentPostType-default-parler-publish-enabled";
            if(in_array($bizRule, $bizRules)) {
                $data = array();
                array_push($data, $parlerTermIDs['publish']);
            }
            $response->data['parler'] = $data;
            return $response;
        }
        );







    }

    public function returnCurrentPostType(){
        //this only works on the post-new.php page
        $pt = "post";
        if(isset($_GET['post_type'])){
          $pt = $_GET['post_type'];
        }
        return $pt;
    }
    public function isPostTypeEnabled(){}
    public function isPostTypeParlerPublish(){}
    public function isPostTypeParlerComments(){}

    public function returnArrayOfParlerTermIDs(){
        $term1 =  term_exists( 'publish', 'parler');
        $term1 = $term1['term_id'];
        $term1 = (int)$term1;
        $term2 =  term_exists( 'comments', 'parler');
        $term2 = $term2['term_id'];
        $term2 = (int)$term2;
        $parlerTermIDs = array("publish" => $term1, "comments" => $term2);
        return $parlerTermIDs;
    }

}