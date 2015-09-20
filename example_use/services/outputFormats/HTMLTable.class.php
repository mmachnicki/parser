<?php
require_once "Formatable.abstract.php";

class HTMLTable implements Formatable{
    public static function getName(){
        return "HTML table";        
    }
    
    public function convert($array, $dataSetName){
        if($array == NULL || count($array) == 0){
            echo "No data";
            return false;
        }
        $tableString = "<table id='$dataSetName' style='border: 1px solid;'>";
        $tableString .= $this->createTableRow($array);
        $tableString .= "</table>";
        
        return $tableString;
    }
    
    private function createTableRow($row){         
        $header = "";
        $tableRowString = "<tr style='border: 1px solid;'>";
        
        foreach($row as $columnName=>$dataSet){
            if(is_array($dataSet)){
                $tableRowString .= $this->createTableRow($dataSet);
            }
            else{
                $header .= "<th style='border: 1px solid;'>$columnName</th>";                
                $tableRowString .= "<td style='border: 1px solid;'>$dataSet</td>";
                if($columnName == "iconLink"){
                    $tableRowString .= "<td style='border: 1px solid;'><img src=$dataSet style='width: 50px'/></td>";
                    $header .= "<th style='border: 1px solid;'>icon</th>"; 
                }
            }
        }
        $tableRowString .= "</tr>";        
        
        return $header.$tableRowString;
    }
    
    private function createHeader($row){
        $header = "<tr style='border: 1px solid;'>";
        
        foreach($row as $tag=>$value){
            $header .= "<th style='border: 1px solid;'>$tag</th>";
        }
        $header .= "</tr>";
        
        return $header;
    }
}