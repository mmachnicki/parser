<?php
require_once 'Event.abstract.php';

/**
 * Searches for a node_name within a specified parent:
 * 
 * event(NodeNameInParent, array("parent_node"=>"search_node")
 */
class NodeNameInParent extends Event{
    
    public function occurence($node){   
        reset($this->variable);
        if($node->xpath("parent::*")[0]->getName() == key($this->variable) && $node->getName() == current($this->variable)){
            
            return true;
        }
        return false;
    }
}