<?php
namespace rolex;

require_once dirname(__FILE__).'/profiler.php';

/**
 * Facade providing singleton access to the profiler
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
    
    public static function run( $message, $closure, $iterations = 1 ) {
        return self::rolex()->profile_function( $message, $closure, $iterations );
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