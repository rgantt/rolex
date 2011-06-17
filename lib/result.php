<?php
namespace rolex;

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