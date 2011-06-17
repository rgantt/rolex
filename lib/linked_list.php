<?php
namespace rolex;

require_once dirname(__FILE__).'/exceptions.php';

class linked_list {
    protected $head = null;
    protected $length = 0;
    
    public function head() {
        return $this->head->data;
    }
    
    public function length() {
        return $this->length;
    }
    
    public function add( $result ) {
        // internal representation
        $node = (object) array( 
            'data' => $result, 
            'next' => null 
        );
        
        if( $this->head === null ) {
            $this->head = $node;
        } else {
            $current = $this->head;
            while( $current->next ) {
                $current = $current->next;
            }
            $current->next = $node;
        }
        $this->length++;
    }
    
    public function index( $index ) {
        if( $index > -1 && $index < $this->length ) {
            $current = $this->head;
            $i = 0;
            while( $i++ < $index ) {
                $current = $current->next;
            }
            return $current->data;
        } else {
            throw new out_of_bounds_exception("list index out of bounds");
        }
    }
    
    public function remove( $index ) {
        if( $index > -1 && $index < $this->length ) {
            $current = $this->head;
            $i = 0;
            if( $index === 0 ) {
                $this->head = $current->next;
            } else {
                while( $i++ < $index ) {
                    $previous = $current;
                    $current = $current->next;
                }
                $previous->next = $current->next;
            }
            $this->length--;
            return $current->data;
        } else {
            throw new out_of_bounds_exception("list index out of bounds");
        }
    }
}