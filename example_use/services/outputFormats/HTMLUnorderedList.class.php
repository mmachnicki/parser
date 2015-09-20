<?php
require_once "Formatable.abstract.php";

class HTMLUnorderedList implements Formatable{
    public static function getName(){
        return "HTML Unordered List";
    }
    
    public function convert($array, $dataSetName){
        if($array == NULL || count($array) == 0){
            echo "No data";
            return false;
        }
        $tableString = "<ul id='$dataSetName'>";
        $tableString .= $this->getItems($array);
        $tableString .= "</ul>";
        
        return $tableString;
    }
    
    private function getItems($array){
        
        $tableRowString = "<ul>";
        foreach($array as $columnName=>$dataSet){
            if(is_array($dataSet)){
                $tableRowString .= $this->getItems($dataSet);
            }
            else{
                $tableRowString .= "<li>$columnName - $dataSet</li>";
            }
        }
        $tableRowString .= "</ul>";
                
        return $tableRowString;
    }
}