<?php
//definition of all existing actions, for the ease of use (hopefully)
//define ("GetAttributes", "GetAttributes");
//define ("GetAttribute", "GetAttribute");
//define("GetParentAttribute", "GetParentAttribute");

abstract class Action{
    protected $variable;
    
    public function __construct($variable=NULL) {
        $this->variable = $variable;
    }
    
    abstract public function trigger($node);
    
    public function getName(){
        return get_class($this);
    }
    
    public function getVariable(){
        return $this->variable;
    }
}