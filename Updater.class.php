<?php
//require_once 'Visitor.class.php';

class Updater{
    protected $processors;
    
    public function __construct() {
        $this->processors = array(
            "Visitor",  //visits every service model and updates its data tables 
            "SocketBroadcast",  //broadcast all active feeds to the WebSocket
            //"ParserRSSFeeder",    //not yet implemented
        );
    }
    
    /**
     * Triggers all processors on an occurence of a feed
     * 
     * 
     * @param xml string $xmlFile
     * @param ParserPushable $serviceName
     */
    public function pushXml($xmlFile, $serviceName){        
//        file_put_contents(__DIR__."/../backup/".$serviceName.".".time().".xml", $xmlFile);
        foreach($this->processors as $processorName){
            ClassLoader::load($processorName, "processors");
            $processor = new $processorName();
            $result = $processor->process($xmlFile, $serviceName);            
            if($result == false) $processor->resque($xmlFile, $serviceName);
        }
    }
}
