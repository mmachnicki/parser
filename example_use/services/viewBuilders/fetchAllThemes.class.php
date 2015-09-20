<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");

class fetchAllThemes implements Viewable{  
    public static function getID(){
        return "";
    }
    
    public static function getName(){
         return "Show Available Themes";
    }
    
    public function getViewData($constraints=NULL){
        $themes = array();
        $themeClasses = scandir(__DIR__);
                
        foreach($themeClasses as $fileName){
            if($fileName!= "." && $fileName!= ".."){
                $className = substr($fileName, 0, strpos($fileName, "."));
                if(ClassLoader::load($className, "services/viewBuilders") != NULL){
                    $themes[] = array("themeID"=>$className::getID(), "api_call"=>$className, "description"=>$className::getName());
                }
            }
        }        
        return $themes;
    }
}
