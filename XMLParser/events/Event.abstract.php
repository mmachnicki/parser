<?php
//define("NodeName", "NodeName");
//define("NodeNameInParent", "NodeNameInParent");

abstract class Event{
    protected $actions;
    protected $variable;
    
    public function __construct($nodeVariables) {                
        $this->actions = array();
        $this->variable = $nodeVariables;
    }
    
    //abstract protected function initialise();
    abstract public function occurence($node);    
    
    public function getName(){
        return get_class($this);
    }
    
    public function setAction($parserAction, $actionVariables=NULL){
        ClassLoader::load("$parserAction", "lib/XMLParser/actions", "action");
        $this->actions[] = new $parserAction($actionVariables);
    }
    
    public function performAction($node){
        $result = array();
        foreach($this->actions as $action){            
//            $result[$this->getName($action)] = $action->trigger($node);            
            $r = $action->trigger($node);
            if(is_array($r)){
                foreach($r as $key=>$value){
                    $result[$key] = $value;
                }
            }else{
                $result[] = $r;
            } 
        }        
        if(count($result) > 0) return $result;
            
        return null;
    }
}