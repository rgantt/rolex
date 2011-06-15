<?php
namespace rolex;

/**
 * Facade providing access to the profiler
 *
 * @author ganttr
 */
class r {
    static $instance = null;
    
    public static function rolex() {
        if( !( self::$instance instanceof rolex ) )
            self::$instance = new rolex();
        return self::$instance;
    }
    
    public static function run( $message, $closure ) {
        return self::rolex()->profile_function( $message, $closure );
    }
    
    public static function start( $key, $message = '' ) {
        return self::rolex()->start_timer( $key, $message );
    }
    
    public static function stop( $key ) {
        return self::rolex()->end_timer( $key );
    }
    
    public static function save( $message, $duration ) {
        return self::rolex()->add_result( $message, $duration );
    }
    
    public static function clear() {
        return self::rolex()->clear_results();
    }
    
    public static function results() {
        return self::rolex()->get_results();
    }
    
    public static function reset() {
        self::rolex()->clear_results();
        self::rolex()->reset_timers();
    }
}

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