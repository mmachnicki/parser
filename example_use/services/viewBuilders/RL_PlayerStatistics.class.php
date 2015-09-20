<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");
ClassLoader::load("RL7Table", "services/dbTableLayouts/");

class RL_PlayerStatistics extends RL7Table implements Viewable{
    public static function getID(){
        return "RL";
    }
    
    public static function getName() {
        return "Rugby League Player Statistics (RL7)";
    }
    
    public function getViewData($constraints = NULL){
        $stats = array();
        $tableName = "RL7_playerStats";
        $where = $this->conjureOutMySQLWhere($this->getTableColumnNames($tableName), $constraints);
        
        if($constraints != null && $where != null && isset($where->statement) && isset($where->array) && $where->statement != ""){
            $whereStatement = $where->statement;
            $whereArray = $where->array;
        }else{
            $tempGame = $this->getExampleTeam();
            $gameID = key($tempGame);
            $teamID = $tempGame[$gameID];
            $whereStatement = "gameID = $gameID AND teamID = $teamID ORDER BY gameID, teamID";
            $whereArray = NULL;
        }
        $statement = $this->select($tableName, NULL, $whereStatement);
        $results = $this->executeQuery($statement, $whereArray);
        
        while($r = $results->fetch()){
            $stats[] = $this->extractData($this->getTableColumnNames($tableName), $r);
        }
        return $stats;
    }
    
    private function getExampleTeam(){
        $table = "RL7_playerStats";
        $sql = "SELECT gameID, teamID FROM $table ORDER BY gameID, teamID DESC LIMIT 1";
        
        $result = $this->executeQuery($sql, NULL)->fetch();
        
        return array($result["gameID"]=>$result["teamID"]);
    }
}