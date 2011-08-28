<?php
namespace rolex;

require_once dirname(__FILE__).'/linked_list.php';

class result_set extends linked_list {
    public function __toString() {
        $current = $this->head;
		$total = $current->data->duration;
        $buffer = $current->data->message;
        while( $current->next ) {
            $total += $current->next->data->duration;
            $current = $current->next;
        }
        $avg_duration = (float)(($total/1000)/$this->length);
        return "{$buffer}: avg. over {$this->length} iterations = {$avg_duration} ms\n";
    }
}