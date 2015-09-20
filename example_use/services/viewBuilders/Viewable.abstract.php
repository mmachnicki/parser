<?php
interface Viewable{    
    
    /**
     * Creates an array to be applied directly as a data supply for a view 
     * or raw data source.
     * 
     * @param $constraints - sql constraints to select values     
     * @return array - tree structure put into array
     */
    function getViewData($constraints = NULL);
    
    /**
     * @return string - view name that should describe data the Viewable class is related to.
     */
    static function getName();
    static function getID();
}