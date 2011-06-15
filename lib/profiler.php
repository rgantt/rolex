<?php
namespace rolex;

class rolex {
    private $results = array();
    private $timers = array();
    
    public function profile_function( $message, $closure ) {
        $key = uniqid();
        $start = $this->start_timer( $key, $message ); 
        $closure( $start );
        return $this->end_timer( $key );
    }
    
    public function add_result( $message, $duration ) {
        $result = new result( $message, $duration );
        $this->results[] = $result;
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
            throw new timing_exception("timer must be started before it can be ended");
        }
        $this->timers[ $key ]['end'] = microtime( true );
        $result = $this->add_result( 
            $this->timers[ $key ]['message'], 
            ( $this->timers[ $key ]['end'] - $this->timers[ $key ]['start'] )
        );
        return $result;
    }
    
    public function get_results() {
        return $this->results;
    }
    
    public function clear_results() {
        $results = $this->results;
        $this->results = array();
        return $results;
    }
    
    public function reset_timers() {
        $timers = $this->timers;
        $this->timers = array();
        return $timers;
    }
}

class timing_exception extends \Exception {}

class result {
    public $message;
    public $duration;
    
    public function __construct( $message, $duration ) {
        $this->message = $message;
        $this->duration = $duration;
    }
    
    public function __toString() {
        return "{$this->message} {$this->duration}\n";
    }
}