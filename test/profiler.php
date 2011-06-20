<?php
namespace rolex;

require_once dirname(__FILE__).'/../lib/profiler.php';

class profiler_test extends \PHPUnit_Framework_TestCase {
	private $rolex = null;
	
	protected function setUp() {
		$this->rolex = new rolex();
	}
	
	protected function tearDown() {
		unset( $this->rolex );
	}
    
    public function test_profile_function_returns_result_set() {
        $this->assertTrue( $this->rolex->profile_function( "t0", function() { return; } ) instanceof result_set );
        $this->assertTrue( $this->rolex->profile_function( "t1", function() { return; }, 10 ) instanceof result_set );
    }
    
    public function test_get_results_returns_result_set() {
        $this->assertTrue( $this->rolex->get_results() instanceof result_set );
    }
    
    public function test_clear_results_clears_results() {
        $this->rolex->profile_function( "test", function() { return; } );
        $this->rolex->clear_results();
        $this->assertEquals( 0, $this->rolex->get_results()->length() );
    }
    
    /**
     * @expectedException rolex\timing_exception
     */
    public function test_reset_timers_resets_timers() {
        $this->rolex->start_timer("test");
        $this->rolex->start_timer("lols");
        $this->rolex->reset_timers();
        $this->rolex->end_timer("test"); // throws a timing_exception
    }
    
    public function test_add_result_adds_result() {
        $time = ( microtime( true ) - microtime( true ) );
        $r = new result( "test", $time );
        $this->rolex->add_result( $r->message, $r->duration );
        $this->assertEquals( $this->rolex->get_results()->head(), $r );
    }
}