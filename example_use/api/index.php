<?php
defined("DOCUMENT_ROOT") or define( 'DOCUMENT_ROOT' , '/var/www/' . $_SERVER[ 'HTTP_HOST' ] );

require_once DOCUMENT_ROOT.'/opta/lib/ClassLoader.class.php';

ClassLoader::load("HTTPParamConverter");
ClassLoader::load("OptaOutput");

fix($_GET, $_SERVER['QUERY_STRING']);
echo getOutput($_GET);

function getOutput($params) {
    if (isset($params["service"]) == false) return null;
    
    $httpConverter = new HTTPParamConverter();
    $optaOutput = new OptaOutput();    
    $service = $params["service"];
    unset($params["service"]);
    
    if (isset($params["format"])) {
        $format = $params["format"];
        unset($params["format"]);
    } else {
        $format = "HTMLTable";
    }
    
    $stringParameters = $httpConverter->convertParams($params);  
    
    $outputView = $optaOutput->getView($service, $stringParameters, $format);
    
    if ($format == "XMLString") {
        return htmlspecialchars($outputView);
    } else {
        return $outputView;
    }
}

//to fix incoming HTTP variables names after PHP replaced characters in them
function fix(&$target, $source, $keep = false) {                       
    if (!$source) {                                                            
        return;                                                                
    }                                                                          
    preg_match_all(                                                            
        '/                                                                     
        # Match at start of string or &                                        
        (?:^|(?<=&))                                                           
        # Exclude cases where the period is in brackets, e.g. foo[bar.blarg]
        [^=&\[]*                                                               
        # Affected cases: periods and spaces                                   
        (?:\.|%20)                                                             
        # Keep matching until assignment, next variable, end of string or   
        # start of an array                                                    
        [^=&\[]*                                                               
        /x',                                                                   
        $source,                                                               
        $matches
    );
    foreach (current($matches) as $key) {                                      
        $key    = urldecode($key);                                             
        $badKey = preg_replace('/(\.| )/', '_', $key);                         

        if (isset($target[$badKey])) {                                         
            // Duplicate values may have already unset this                    
            $target[$key] = $target[$badKey];                                  

            if (!$keep) {                                                      
                unset($target[$badKey]);                                       
            }                                                                  
        } 
    }
}
