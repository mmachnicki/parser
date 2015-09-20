<?php
require_once 'Action.abstract.php';

/**
 * Gets specified attribute of a node: action(GetAttribute, "attribute_name")
 */
class GetSiblingAttribute extends Action{
    
    //returns array attribute_name=>value 
    public function trigger($node) {
        $parent = $node->xpath("parent::*")[0];
        $sibling = key($this->variable);
        
        $attributeName = current($this->variable);
        $attribute = (array)$parent->$sibling->attributes()->$attributeName;
        
        return array($sibling.".".$attributeName=>$attribute[0]);
    }
}