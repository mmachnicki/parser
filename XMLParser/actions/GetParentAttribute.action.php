<?php
require_once 'Action.abstract.php';

/**
 * Gets specified attribute of a parent: action(GetParentAttribute, "attribute_name")
 */
class GetParentAttribute extends Action{
    
    //returns array of sets(parent_node_name.node_attribute_name=>value)
    public function trigger($node) {
        $parentNode = $node->xpath("parent::*")[0];
        $attribute = (array)$parentNode->attributes()[$this->variable];
        
        if(is_array($attribute) && count($attribute)>0){
            return array($parentNode->getName().".".$this->variable=>$attribute[0]);
        }            
        return null;
    }
}