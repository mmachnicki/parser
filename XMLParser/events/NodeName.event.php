<?php
require_once 'Event.abstract.php';

/**
 * Searches for a node_name in all nodes of the document:
 * 
 * event(NodeName, "node_name")
 */
class NodeName extends Event{
    
    public function occurence($node){
        if($node->getName() == $this->variable) return true;

        return false;
    }
}