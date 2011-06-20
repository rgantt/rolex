<?php
namespace rolex;

require_once dirname(__FILE__).'/result.php';
require_once dirname(__FILE__).'/result_set.php';
require_once dirname(__FILE__).'/exceptions.php';

define( "ROUND_PRECISION", 10 );

class rolex {
    private $results;
    private $timers = array();
    
    public function __construct() {
        $this->results = new result_set;
    }
    
    public function profile_function( $message, $closure, $iterations = 1 ) {
        return $this->multirun_silent( $message, $closure, $iterations );
    }
    
    private function do_run( $message, $closure ) {
        $key = uniqid();
        $this->start_timer( $key, $message );
        $closure( $key );
        return $this->end_timer( $key );
    }
    
    private function multirun_verbose( $message, $closure, $iterations = 1 ) {
        $results = new result_set;
        for( $i = 0; $i < $iterations; $i++ ) {
            $results->add( $this->do_run( $message, $closure ) );
        }
        return $results;
    }
    
    private function multirun_silent( $message, $closure, $iterations = 1 ) {
        ob_start();
        $results = $this->multirun_verbose( $message, $closure, $iterations );
        ob_end_clean();
        return $results;
    }
    
    public function add_result( $message, $duration ) {
        $result = new result( $message, $duration );
        $this->results->add( $result );
        return $result;
    }
    
    public function start_timer( $key, $message = '' ) {
        $start = microtime( true );
        if( isset( $this->timers[ $key ] ) ) {
            throw new timing_exception("timer key must be unique");
        }
        $this->timers[ $key ] = array( 'message' => $message, 'start' => $start );
        return $start;
    }
    
    public function end_timer( $key ) {
        if( !isset( $this->timers[ $key ] ) ) {
            throw new timing_exception("timer \"{$key}\" must be started before it can be ended");
        }
        $this->timers[ $key ]['end'] = microtime( true );
        return $this->add_result( 
            $this->timers[ $key ]['message'], 
            round( 1000000*( $this->timers[ $key ]['end'] - $this->timers[ $key ]['start'] ), ROUND_PRECISION )
        );
    }
    
    public function get_results() {
        return $this->results;
    }
    
    public function clear_results() {
        $results = $this->results;
        $this->results = new result_set;
        return $results;
    }
    
    public function reset_timers() {
        $timers = $this->timers;
        $this->timers = array();
        return $timers;
    }
}