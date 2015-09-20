<?php
require_once "Formatable.abstract.php";

class XMLString implements Formatable{
    public static function getName(){
        return "XML String";
    }
    
    public function convert($array, $dataSetName){
        if($array == NULL || count($array) == 0){
            echo "No data";
            return false;
        }        
        $xmlTree = new DOMDocument('1.0', 'UTF-8');
        $xmlRoot = $xmlTree->createElement($dataSetName);
        
        $xmlTree->appendChild($this->getElements($array, $xmlTree, $xmlRoot));
        
        return $xmlTree->saveXML();
    }
    
    private function getElements($array, $xmlTree, $xmlRoot){        
        $element = null;
        foreach($array as $columnName=>$dataSet){              
            if(is_array($dataSet)){
                $element = $xmlTree->createElement($columnName);
                
                $xmlRoot->appendChild($this->getElements($dataSet, $xmlTree, $element));
            }
            else{
                $attribute = $xmlTree->createAttribute($columnName);
                $attribute->value = $dataSet;
                $xmlRoot->appendChild($attribute);
            }
        }       
        return $xmlRoot;
    }
}