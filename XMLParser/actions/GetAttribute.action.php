<?php
require_once 'Action.abstract.php';

/**
 * Gets specified attribute of a node: action(GetAttribute, "attribute_name")
 */
class GetAttribute extends Action{
    
    //returns array attribute_name=>value 
    public function trigger($node) {
        $attribute = (array)$node->attributes()[$this->variable];
        return array($this->variable=>$attribute[0]);
    }
}