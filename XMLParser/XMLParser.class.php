<?php
require_once 'events/Event.abstract.php';
require_once 'actions/Action.abstract.php';

autoDefineEventsActions();

/**
 * Event driven parser
 * 
 * events - an occurence of a node of specified parameters (name, value, attributes)
 * actions - actions performed in case of node occurence (get node name, get node value, get parent attributes ...)
 */
class XMLParser{
    protected $simpleXmlElement;
    protected $events;
    protected $result;
    
    public function __construct() {
        $this->events = array();
        $this->result = array();
    }
    
    public function setString($xmlString){
        $this->simpleXmlElement = new SimpleXMLElement($xmlString);        
    }
    
    public function setEvent($nodeEvent, $eventVariables){
        ClassLoader::load("$nodeEvent", "lib/XMLParser/events", "event");
                
        $this->events[] = new $nodeEvent($eventVariables);
    }
    
    public function addAction($action, $actionVariables=NULL){
        end($this->events)->setAction($action, $actionVariables);
    }
    
    public function resetEvents(){
        unset($this->events);
        $this->events = array();
    }
    
    public function getEvents(){
        $events = array();
        foreach($this->events as $event){
            $events[] = $event->getName();
        }
    }
    
    /*
     * Goes through all nodes and tests against defined events
     */
    public function parse($xmlElement=NULL, $result = array()){        
        if($xmlElement == null) $xmlElement = $this->simpleXmlElement;
                
        foreach($xmlElement->children() as $child){
            $r = $this->tryEvent($child);            
            if($r){
                $result[] = $r;                
            }
            if(count($child->children())>0){                
                $r2 = $this->parse($child, $result);
                if($r2) $result[] = $r2;                
            }
        }        
        if(count($result)>0){
            return $result;
        }
        return null;
    }
    
    /**
     * Results read out causes XMLParser to be reset (results, events and actions are deleted)
     * 
     * @return array results
     */
    public function fetchResults(){ 
        $r = $this->result;
        $this->purge();
        return $r;
    }
    
    public function purge(){
        $this->events = array();
        $this->result = array();
    }    
    
    private function tryEvent($xmlSubTree){ 
        $results = array();
        foreach($this->events as $event){
            if($event->occurence($xmlSubTree)){                
                $r = $event->performAction($xmlSubTree);
                if($r) $this->result[] = $r;
            }
        }
        if(count($results)>0){            
            return $results;
        }
        return null;
    }
}

function autoDefineEventsActions(){
    $eventsPath = __DIR__."/events/";
    $actionsPath = __DIR__."/actions/";
    
    defineEach(scandir($eventsPath), "event");
    defineEach(scandir($actionsPath), "action");
}

function defineEach($files, $type){
    foreach($files as $file){
        if($file != ".." && $file != "." && substr($file, strpos($file, ".")) != ".abstract.php"){
            $class = rtrim(rtrim($file, ".php"), "$type");
            $class = rtrim($class, ".");
            
            define($class, $class);
        }
    }
}