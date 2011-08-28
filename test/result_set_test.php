<?php 
namespace rolex;

require_once dirname(__FILE__).'/../lib/profiler.php';

class result_set_test extends \PHPUnit_Framework_TestCase {
	private $rolex = null;
    
    protected function setUp() {
		$this->rolex = new rolex;
    }
    
    protected function tearDown() {
		unset( $this->rolex );
    }
    
    public function test_single_run_gives_nonzero_runtime() {
        $this->rolex->profile_function( "test", function(){ return pow( 10, pow( 10, 10 ) ); }, 1 );
        $this->assertEquals( 0, preg_match( "/0 ms$/", $this->rolex->get_results() ) );
    }
	
	public function test_avg_between_lowest_and_highest() {
		$this->rolex->profile_function( "test", function(){
			static $sl = 1;
			sleep( $sl );
			$sl++;
		}, 2);
		$results = $this->rolex->get_results();
		$avg = array();
		preg_match( "/[-+]?([0-9]*\.[0-9]+|[0-9]+) ms$/", (string) $results, $avg );
		$this->assertTrue( $avg[1] > $results->index(0)->duration );
		$this->assertTrue( $avg[1] < $results->index(1)->duration );
	}
}