<?php

class SyncFeatureTest extends \Codeception\TestCase\WPTestCase{

    public static function setUpBeforeClass() : void{
        //require_once('/var/www/html/wp-content/plugins/parler-wordpress-php/src/Parler/autoloader.php');
}
    /**
     *
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable(){

        $SyncFeature = new \Parler\SyncFeature();
      }

    public function itShouldReturnEmails(){
        $SyncFeature = new \Parler\SyncFeature();
        $userID1 = wp_create_user( "StubUser1", "ABCxzy123$", "stubuser1@nowhere.com" );
        $userID2 = wp_create_user( "StubUser2", "ABCxzy123$", "stubuser2@nowhere.com" );
        $userID3 = wp_create_user( "StubUser3", "ABCxzy123$", "stubuser3@nowhere.com" );

    }

}