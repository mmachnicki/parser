<?php
defined("DOCUMENT_ROOT") or define( 'DOCUMENT_ROOT' , '/var/www/' . $_SERVER[ 'HTTP_HOST' ] );

require_once DOCUMENT_ROOT.'/lib/VectorMap.php';

class HTTPParamConverter{
    public function __construct() {
        
    }
    
    public function convertParams($paramsArray){
        $params = array();
        
        foreach($paramsArray as $param=>$value){
            $params[] = $this->checkQueryParams($param, $value);             
        }
        return $params;
    }    

    protected function checkQueryParams($param, $value) {
        if ($value == null) {
            $p = $this->extractParam($param);
            if ($p == null) {
                $p = new stdClass();
                $p->param = $param;
                $p->value = null;
                $p->operator = null;
            }
        } else {
            $p = new stdClass();
            $testParam = $this->extractParam($param);
            $testValue = $this->extractParam($value);

            if ($testParam) {
                $p->param = $testParam->param;
                $p->value = $value;
                $p->operator = $testParam->operator . "=";
            } elseif ($testValue) {
                $p->param = $param;
                $p->value = $testValue->value;
                $p->operator = "=" . $testValue->operator;
            } else {
                $p->param = $param;
                $p->value = $value;
                $p->operator = "=";
            }
        }
        return $p;
    }
    
    protected function extractParam($param) {
        $result = new stdClass();
        $operators = array("<>", "<", ">", "!");

        foreach ($operators as $operator) {
            $arr = explode($operator, $param);
            if (count($arr) > 1) {
                $result->param = $arr[0];
                $result->value = $arr[1];
                $result->operator = $operator;
                
                return $result;
            }
        }        
        return null;
    }
}