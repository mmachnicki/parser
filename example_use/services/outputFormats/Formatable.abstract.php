<?php
interface Formatable{
    static function getName();
    
    function convert($array, $dataSetName);
}