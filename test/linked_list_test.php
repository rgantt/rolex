<?php 
namespace rolex;

require_once dirname(__FILE__).'/../lib/linked_list.php';

class linked_list_test extends \PHPUnit_Framework_TestCase {
    private $ll = null;
    
    protected function setUp() {
        $this->ll = new linked_list;
    }
    
    protected function tearDown() {
        unset( $this->ll );
    }
    
    public function test_add_single() {
        $this->ll->add("hi");
        $this->assertEquals( 1, $this->ll->length() );
        $this->assertEquals( "hi", $this->ll->head() );
    }
    
    public function test_add_multiple() {
        $this->ll->add("hi");
        $this->ll->add("yo");
        $this->assertEquals( 2, $this->ll->length() );
        $this->assertEquals( "hi", $this->ll->head() );
    }
    
    private function populate_multiple() {
        $this->ll->add("hi");
        $this->ll->add("yo");
        $this->ll->add("bye");
    }
    
    public function test_get_first_item() {
        $this->populate_multiple();
        $this->assertEquals( "hi", $this->ll->index(0) );
    }
    
    public function test_get_middle_item() {
        $this->populate_multiple();
        $this->assertEquals( "yo", $this->ll->index(1) );
    }
    
    public function test_get_last_item() {
        $this->populate_multiple();
        $this->assertEquals( "bye", $this->ll->index(2) );
    }
    
    /**
     * @expectedException rolex\out_of_bounds_exception
     */
    public function test_get_invalid_item() {
        $this->populate_multiple();
        $this->assertNull( $this->ll->index(5) );
    }
    
    /**
     * @expectedException rolex\out_of_bounds_exception
     */
    public function test_remove_first_item() {
        $this->populate_multiple();
        $value = $this->ll->remove(0);
        $this->assertEquals( "hi", $value );
        $this->assertEquals( 2, $this->ll->length() );
        $this->assertEquals( "yo", $this->ll->index(0) );
        $this->assertEquals( "bye", $this->ll->index(1) );
        $bad = $this->ll->index(2);
    }
    
    /**
     * @expectedException rolex\out_of_bounds_exception
     */
    public function test_remove_middle_item() {
        $this->populate_multiple();
        $value = $this->ll->remove(1);
        $this->assertEquals( "yo", $value );
        $this->assertEquals( 2, $this->ll->length() );
        $this->assertEquals( "hi", $this->ll->index(0) );
        $this->assertEquals( "bye", $this->ll->index(1) );
        $bad = $this->ll->index(2);
    }
    
    /**
     * @expectedException rolex\out_of_bounds_exception
     */
    public function test_remove_last_item() {
        $this->populate_multiple();
        $value = $this->ll->remove(2);
        $this->assertEquals( "bye", $value );
        $this->assertEquals( 2, $this->ll->length() );
        $this->assertEquals( "hi", $this->ll->index(0) );
        $this->assertEquals( "yo", $this->ll->index(1) );
        $bad = $this->ll->index(2);
    }
}