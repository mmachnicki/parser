<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");

class fetchAllFormats implements Viewable{   
    public static function getID(){
        return "";
    }
    
    public static function getName(){
         return "Show Available Formats";
    }
    
    public function getViewData($constraints=NULL){
        $formats = array();
        $themeClasses = scandir(__DIR__."/../outputFormats/");
                
        foreach($themeClasses as $fileName){
            if($fileName!= "." && $fileName!= ".."){
                $className = substr($fileName, 0, strpos($fileName, "."));
                if(ClassLoader::load($className, "services/outputFormats")){
                    $formats[] = array($className=>$className::getName());       
                }
            }
        }        
        return $formats;
    }
}