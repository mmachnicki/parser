<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");
ClassLoader::load("RL2Table", "services/dbTableLayouts/");

class RL_TableStandings extends RL2Table implements Viewable{
    public static function getID(){
        return "RL";
    }
    
    public static function getName() {
        return "Rugby League Table Standings (RL2)";
    }
    
    public function getViewData($constraints = NULL) {        
        return $this->getTableStandings($constraints);
    }
    
    private function getTableStandings($constraints){
        $tableName = "RL2_tableStandings";
        $standings = array();
        $array = null;
        $teamConstraints = null;
        
        if($constraints == null){
            $constraints = " 1 ORDER BY tableDateTime DESC LIMIT 3";
        }else{
            $where = $this->conjureOutMySQLWhere($this->getTableColumnNames($tableName), $constraints);
            $constraints = $where->statement;
            $array = $where->array;
            $teamConstraints = $where->leftover;
        }        
        $sql = $this->select($tableName, NULL, $constraints);
        $result = $this->executeQuery($sql, $array);
        $index = 0;
        while($r = $result->fetch()){
            $teams = $this->getTableStandings_teams("tableDateTime = ".$r["tableDateTime"]." AND competitionID = ".$r["compID"], $teamConstraints);
            if(count($teams) > 0){
                $standings["fixture-$index"] = $this->extractData($this->getTableColumnNames($tableName), $r);
                $standings["fixture-$index"]["teams"] = $teams;
                $index++;
            }
        }
        return $standings;
    }
    
    private function getTableStandings_teams($constraintsStatement, $whereArray){        
        $tableName = "RL2_tableStandings_teams";
        $tableDefinition = $this->getTableColumnNames($tableName);
        
        $teams = array();
        $statement = "";
        $array = null;
        
        $where = $this->conjureOutMySQLWhere($tableDefinition, $whereArray, "team");
        if($where != null & isset($where->statement) & isset($where->array)){
            $statement = " AND ".$where->statement;
            $array = $where->array;
        }        
        $sql = $this->select($tableName, NULL, $constraintsStatement.$statement);
        $result = $this->executeQuery($sql, $array);        
        $index = 0;
        while($r = $result->fetch()){            
            $teams["team-$index"] = $this->extractData($this->getTableColumnNames($tableName), $r);
            $teams["team-$index"]["iconLink"] = "http://".$_SERVER["HTTP_HOST"]."/opta/resources/RL/".$r["teamID"].".png";
            $index++;
        }
        return $teams;
    }
}
