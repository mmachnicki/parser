<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");
ClassLoader::load("Table", "database", "abstract");

class fetchTeamByThemeID extends Table implements Viewable{
    public static function getID(){
        return "";
    }
    
    public function initialise() {
        
    }

    public static function getName(){
         return "Show Teams by Theme id";
    }
    
    public function getViewData($constraints=NULL){
        $tablePrefix = NULL;
        if($constraints[0]->param == "themeID" && isset($constraints[0]->value)){
            $tablePrefix = $constraints[0]->value."1";
        }        
        if($tablePrefix == NULL) return NULL;
        
        $tables = $this->getAllTableNames();
        if(in_array($tablePrefix."_team", $tables) == false) return NULL;
        
        $teams = array();
        $sql = "SELECT * FROM ".$tablePrefix."_team";
        
        $result = $this->executeQuery($sql, NULL);
        
        while($r = $result->fetch(PDO::FETCH_ASSOC)){
            $team = array();
            if($r["id"] == 0) continue;
            foreach($r as $key=>$data){
                $team[$key] = $data;
            }
            $teams[] = $team;
        }
        return $teams;
    }
    
    private function getAllTableNames(){
        $tables = array();
        $sql = $sql = "SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = 'xmlParse'";
        
        $result = $this->executeQuery($sql, NULL);
        
        while($r = $result->fetch(PDO::FETCH_ASSOC)){            
            $tables[] = $r["TABLE_NAME"];                        
        }
        return $tables;
    }
}