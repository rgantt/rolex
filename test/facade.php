<?php
namespace rolex;

require_once dirname(__FILE__).'/../lib/rolex.php';

class facade_test extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        r::reset();
    }
    
    public function test_run_retains_message() {
        $str = "test";
        $this->assertContains( $str, r::run( $str, function() { return false; } )->message );
    }
    
    public function test_run_returns_result() {
        $this->assertTrue( r::run( "test", function() { return true; } ) instanceof result );
    }
    
    public function test_add_result() {
        $duration = microtime( true ) - microtime( true );
        $result = new result( "bogus!", $duration );
        $this->assertEquals( $result, r::save( "bogus!", $duration ) );
    }
    
    private function add_results() {
        r::run( "first", function() { return 1; } );
        r::run( "second", function() { return 2; } );
    }
    
    public function test_clear_results_saves_results() {
        $this->add_results();
        $results = r::clear();
        $this->assertEquals( 2, count( $results ) );
        $this->assertEquals( "first", $results[0]->message );
        $this->assertEquals( "second", $results[1]->message );
    }
    
    public function test_clear_results_clears_results() {
        $this->add_results();
        r::clear();
        $this->assertEquals( array(), r::results() );
    }
    
    public function test_results() {
        $this->add_results();
        $results = r::results();
        $this->assertEquals( 2, count( $results ) );
        $this->assertEquals( "first", $results[0]->message );
        $this->assertEquals( "second", $results[1]->message );
    }
    
    public function test_start_and_stop() {
        r::start( "first", "this is the first test" );
        for( $i = 0; $i < 10000; $i++ ){}
        $result = r::stop( "first" );
        $this->assertEquals( "this is the first test", $result->message );
    }
    
    public function test_nested_start_stop() {
        r::start( "first", "this one's first" );
        r::start( "second", "then this one" );
        $second = r::stop("second");
        $first = r::stop("first");
        $this->assertEquals( "this one's first", $first->message );
        $this->assertEquals( "then this one", $second->message );
    }
    
    public function test_staggered_start_stop() {
        r::start( "first", "firsty!" );
        r::start( "second", "secondy" );
        $first = r::stop("first");
        $second = r::stop("second");
        $this->assertEquals( "firsty!", $first->message );
        $this->assertEquals( "secondy", $second->message );
    }
    
    public function test_unterminated_timer() {
        r::start( "first", "a clear winner" );
        r::start( "second", "will never finish" );
        $first = r::stop("first");
        $this->assertEquals( "a clear winner", $first->message );
        $timers = r::rolex()->reset_timers();
        $this->assertEquals( "will never finish", $timers['second']['message'] );
    }    
}