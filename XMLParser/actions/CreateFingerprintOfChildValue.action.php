<?php
require_once 'Action.abstract.php';

/**
 * Creates MD5 based unique value "fingerprint" from a value of specified node's child: 
 * action(CreateFingerprintOfChildValue, "child_node_tag_name")
 */
class CreateFingerprintOfChildValue extends Action{
    
    //returns array child_name=>value 
    public function trigger($node) {
        $child = $this->variable;
        
        return array("fingerprint"=>md5($node->$child));
    }
}