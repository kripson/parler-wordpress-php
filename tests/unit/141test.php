
<?php

class CityObjectTest extends \Codeception\TestCase\WPTestCase{
    
    
    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable(){
        $stubAnchorLink = $this->stubAnchorLink;
         $CityObject = new CRGDaily\CityObject($stubAnchorLink);
    }
    
}