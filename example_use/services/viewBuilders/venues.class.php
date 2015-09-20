<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");
ClassLoader::load("Table", "database", "abstract");

class venues extends Table implements Viewable{
    protected function initialise() {
        
    }
    
    public static function getID(){
        return "";
    }
    
    public static function getName() {
        return "Venues";
    }
    
    public function getViewData($constraints = null) { 
        if(is_array($constraints) == false || count($constraints) == 0 || $constraints[0]->param == "types" || $constraints[0]->value == "") return $this->getVenueTypes ();
        if ($constraints[0]->param != "type") return null;        
        
        $venueType = $constraints[0]->value;
        switch ($venueType) {
            case "RL":
            case "Rugby League":
            case "Super League":
                $table = "RL1_fixtures";
                $type = "RL";
                break;
            case "RU":
            case "Rugby Union":
            case "Aviva Premiership":
                $table = "RU1_fixtures";
                $type = "RU";
                break;
            default :
                $table = null;
                $type = "venue";
                break;
        }
        if ($table == null)
            return null;
        $venues = array();

        $sql = "SELECT DISTINCT venueID, venue FROM $table ORDER BY venue";
        $result = $this->executeQuery($sql, NULL);
        
        while ($r = $result->fetch()) { 
            $venue = array();
            $venue["id"] = $type."-".$r["venueID"];
            $venue["name"]  = $r["venue"];            
            $venues[] = $venue;
        }        
        if (count($venues) > 0) return $venues;
        return null;
    }
    
    private function getVenueTypes(){        
        return array(
            array("type"=>"RL", "name"=>"Rugby League"),
            array("type"=>"RU", "name"=>"Rugby Union")
            );
    }
}