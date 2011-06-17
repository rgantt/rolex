<?php
namespace rolex;

require_once dirname(__FILE__).'/linked_list.php';

class result_set extends linked_list {
    public function __toString() {
        $buffer = '';
        $current = $this->head;
        while( $current->next ) {
            $buffer .= $current->data;
            $current = $current->next;
        }
        return $buffer;
    }
}