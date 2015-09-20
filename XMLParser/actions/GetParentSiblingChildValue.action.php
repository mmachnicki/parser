<?php
require_once 'Action.abstract.php';

/**
 * Gets specified attribute of a sibling of node's parent : action(GetParentSiblingValue, array("parent_sibling_tag_name"=>"child_tag_name")
 */
class GetParentSiblingChildValue extends Action{
    
    public function trigger($node){        
        $parent = $node->xpath("parent::*")[0];        
        $grandParent = $parent->xpath("parent::*")[0];
        $siblingName = key($this->variable);
        $attributeName = current($this->variable);
        $sibling = $grandParent->$siblingName;        
        $attribute = (array)$sibling->$attributeName;
        
        return array($siblingName.".".$attributeName=>$attribute[0]);
    }
}