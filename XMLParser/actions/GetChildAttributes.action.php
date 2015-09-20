<?php
require_once 'Action.abstract.php';

/**
 * Gets all attributes of specified child: action(GetChildAttributes, "child_tag_name")
 */
class GetChildAttributes extends Action{
    
    public function trigger($node){        
        $child = $this->variable;
        $attributes = array();
        $attributesElement = (array)$node->$child->attributes();
        foreach($attributesElement["@attributes"] as $key=>$value){
            $attributes[$key] = $value;
        }        
        return $attributes;
    }
}