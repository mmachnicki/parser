<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");
ClassLoader::load("RL7Table", "services/dbTableLayouts/");

class RL_TeamSquads extends RL7Table implements Viewable{
    public static function getID(){
        return "RL";
    }
    
    public static function getName() {
        return "Rugby League Team Squads (RL7)";
    }
    
    public function getViewData($constraints = NULL){
        $squad = array();
        $tableName = "RL7_teamSquads";
        $where = $this->conjureOutMySQLWhere($this->getTableColumnNames($tableName), $constraints);
        
        if($constraints != null && $where != null && isset($where->statement) && isset($where->array) && $where->statement != ""){
            $whereStatement = $where->statement." ORDER BY gameID, teamID, positionID";
            $whereArray = $where->array;
        }else{
            $tempGame = $this->getTeamFromLastGame();
            $gameID = key($tempGame);
            $teamID = $tempGame[$gameID];
            $whereStatement = "gameID = $gameID AND teamID = $teamID ORDER BY gameID, teamID, positionID";
            $whereArray = NULL;
        }
        $statement = $this->select($tableName, NULL, $whereStatement);
        $results = $this->executeQuery($statement, $whereArray);
        
        while($r = $results->fetch()){
            $squad[] = $this->extractData($this->getTableColumnNames($tableName), $r);
        }        
        return $squad;
    }
    
    private function getTeamFromLastGame(){
        $tableName = "RL7_teamSquads";
        
        $sql = "SELECT gameID, teamID FROM $tableName ORDER BY gameID DESC LIMIT 1";
        
        $result = $this->executeQuery($sql, NULL)->fetch();
        
        return array($result["gameID"]=>$result["teamID"]);
    }
}