<?php
ClassLoader::load('Viewable', "services/viewBuilders/", "abstract");
ClassLoader::load("RL1Table", "services/dbTableLayouts/");

class RL_Fixtures extends RL1Table implements Viewable{
    public static function getID(){
        return "RL";
    }
    
    public static function getName() {
        return "Rugby League Fixtures (RL1)";
    }
    
    public function getViewData($constraints = null){        
        $fixtures = $this->getFixtures($constraints);
        $root = array();
        
        $index = 0;
        foreach($fixtures as $fixture){    
            $team = $this->getTeamFixture($fixture["id"], $constraints);
            if($team != null){    
                $fixture["teams"] = $this->getTeamFixture($fixture["id"]);
                $root["fixtures"]["fixture-$index"] = $fixture;
                $index++;
            }
        }
        return $root;
    }
    
    private function getFixtures($constraints){
        $tableName = "RL1_fixtures";
        $fixtures = array();
        $whereStatement = " venue!='TBC' ORDER BY gameDate DESC LIMIT 6";
        $whereArray = null;
        
        $where = $this->conjureOutMySQLWhere($this->getTableColumnNames($tableName), $constraints);
        
        if($constraints != null && ($where == null || isset($where->statement) == false || isset($where->array) == false || $where->statement == "")){
            $whereStatement = $where->statement." venue!='TBC' ORDER BY gameDate DESC";
            $whereArray = $where->array;
        }
        
        if($constraints != null && $where != null && isset($where->statement) && isset($where->array) && $where->statement != ""){
            $whereStatement = $where->statement." ORDER BY gameDate DESC";
            $whereArray = $where->array;
        }
        
        $statement = $this->select($tableName, NULL, $whereStatement);        
        $results = $this->executeQuery($statement, $whereArray);
        $index = 0;
        while($r = $results->fetch()){
            $fixtures["fixture-$index"] = $this->extractData($this->getTableColumnNames($tableName), $r);
            $index++;
        }
        return $fixtures;
    }
    
    private function getTeamFixture($fixtureID, $constraints=NULL){
        $teamFixture = array();
        $tableName = "RL1_fixtures_teams";
        $joinTable = "RL1_team";
        $columns = $this->getTableColumnNames($tableName);
        unset($columns["fixture.id"]);
        $array = null;

        $joinColumns = $this->getTableColumnNames($joinTable);
        unset($joinColumns["id"]);
        
        $statement = "SELECT $tableName.*, $joinTable.* FROM $tableName  JOIN $joinTable ON $joinTable.id = $tableName.teamID WHERE fixtureID = $fixtureID";
        
        $where = $this->conjureOutMySQLWhere(array_merge($columns, $joinColumns), $constraints);
        if($where != null && isset($where->statement) && $where->statement != ""){
            $statement .= " AND ".$where->statement;
            $array = $where->array;
        }
        $results = $this->executeQuery($statement, $array);
        
        $index = 0;
        while($r = $results->fetch()){
            $teamFixture["team-$index"] = $this->extractData(array_merge($columns, $joinColumns), $r);
            $teamFixture["team-$index"]["iconLink"] = "http://".$_SERVER["HTTP_HOST"]."/opta/resources/RL/".$r["teamID"].".png";
            $index++;
        }
        return $teamFixture;
    }
}
