<?php
require_once DOCUMENT_ROOT.'/xmlParser/lib/ClassLoader.class.php';
//ClassLoader::load("RL1Fixtures", "services/viewBuilders");
//ClassLoader::load("RL2TableStandings", "services/viewBuilders");

class OptaOutput{
    
    public function __construct() {
        
    }
    
    public function getView($viewName, $constraints, $format){                
        if($viewName != null){
            if(ClassLoader::load($viewName, "services/viewBuilders/") && ClassLoader::load($format, "services/outputFormats/")){
                $viewDefinition = new $viewName();
                $format = new $format();
                return $format->convert($viewDefinition->getViewData($constraints), $viewName);
            }else{
                echo "<p><b>No view selected</b></p>";
            }
        }
    }
    
    public function getFormatsSelection(){
        return $this->scanClasses("services/outputFormats/");
    }
    
    public function getTablesSelection(){        
        return $this->scanClasses("services/viewBuilders/");
    }
    
    private function scanClasses($dir){
        $directory = scandir(__DIR__."/../$dir");        
        $classes = array();
        
        foreach($directory as $classFile){            
            $className = rtrim($classFile, ".php");            
            $className = rtrim($className, "class");            
            $className = rtrim($className, ".");
            if(ClassLoader::load($className, $dir)){
                $builder = new $className();
                $classes[get_class($builder)] = $builder->getName();
            }
        }
        return $classes;
    }
}