<?php
namespace rolex;

require_once dirname(__FILE__).'/linked_list.php';

class result_set extends linked_list {
    public function __toString() {
        $total = 0;
        $current = $this->head;
        $buffer = $current->data->message;
        while( $current->next ) {
            $total += $current->data->duration;
            $current = $current->next;
        }
        $avg_duration = (float)($total/$this->length);
        return "{$buffer}: avg. over {$this->length} iterations = {$avg_duration} ms\n";
    }
}