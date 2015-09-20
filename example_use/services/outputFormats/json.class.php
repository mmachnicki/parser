<?php
require_once "Formatable.abstract.php";

class json implements Formatable{
    public static function getName(){
        return "Json string";
    }
    
    public function convert($array, $dataSetName){
        return json_encode(array($dataSetName=>$array));
    }
}