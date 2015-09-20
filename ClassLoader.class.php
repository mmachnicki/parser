<?php
class ClassLoader{
    
    static function load($name, $path=NULL, $type=NULL){
        if($type == null) $type = "class";
        
        if($path == null){
            $path = '/var/www/' . $_SERVER['HTTP_HOST']."/xmlParser/lib";
        }else{
            $path = '/var/www/' . $_SERVER['HTTP_HOST']."/xmlParser/".$path;
        }
        $path = rtrim($path, "/")."/";
        $file = $path.$name.".$type.php";
        $exists = file_exists($file);
        
        if($exists){
            require_once $file;
            return $file;
        }
        return null;
    }
}
spl_autoload_register(array("ClassLoader", "load"));
