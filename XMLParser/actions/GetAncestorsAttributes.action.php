<?php
require_once 'Action.abstract.php';

/**
 * Gets specified attributes of selected ancestors: 
 * 
 *  action(GetAncestorsAttributes, array("ancestor1_name"=>"attribute_name", "ancestor2_name"=>"attribute_name"...))
 */
class GetAncestorsAttributes extends Action{
    
    //returns array of sets(parent_node_name.node_attribute_name=>value)
    public function trigger($node) {
        $results = array();
        $ancestorsAttributes = $this->variable;
        
        foreach($this->variable as $ancestorName=>$attributeName){
            $ancestorAttribute = (array)$node->xpath("ancestor::$ancestorName/@$attributeName")[0];
            $results[$ancestorName.".".$attributeName] = $ancestorAttribute["@attributes"][$attributeName];
        }
        if(count($results)>0){            
            return $results;
        }            
        return null;
    }
}