<?php
require_once __DIR__.'/../lib/XMLParser/XMLParser.class.php';

abstract class ParserVisitable{
    protected $root;
    protected $name;
    
    public function __construct() {  
        $this->root = "rootNode";
    }  
    
    /*
     * Function to set parser configuration (attach events to tag names and add actions to the events)
     */
    abstract protected function updateData($xmlString);
    
    /**
     * Returns database table model
     */
    abstract public function getDBTableDefinition($tableName);
        
    /**
     * Visitor structure function to visit every feed representing object and update its state (update data)
     * @param type $xmlFile
     */
    public function visit($xmlFile){        
        return $this->updateData($xmlFile);
    }
}