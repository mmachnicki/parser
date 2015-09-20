<?php
require_once 'Action.abstract.php';

/**
 * Gets all attributes of a node: action(GetAttributes)
 */
class GetAttributes extends Action{
    
    /*
     * Returns an array(node_attribute_name=>value) with all attributes listed
     */
    public function trigger($node){
        $attributes = array();       
        
        foreach((array)$node->attributes() as $key=>$attribute){
            $attributes[$key] = $attribute;            
        }                
        return $attributes["@attributes"];
    }    
}