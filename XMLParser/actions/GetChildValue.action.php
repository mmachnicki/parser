<?php
require_once 'Action.abstract.php';

/**
 * Gets a value of specified child: action(GetChildValue, "child_tag_name")
 */
class GetChildValue extends Action{
    
    //returns array child_name=>value 
    public function trigger($node) {
        $child = $this->variable;
        
        return array($node->$child->getName()=>$node->$child);
    }
}