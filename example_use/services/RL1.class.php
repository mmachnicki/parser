<?php
require_once __DIR__.'/ParserVisitable.abstract.php';
require_once __DIR__.'/dbTableLayouts/RL1Table.class.php';

/*
 * Controller class
 */
class RL1 extends ParserVisitable{    
    protected function updateData($xmlString){
        $xmlParser = new XMLParser();
        $xmlParser->setString($xmlString);
        $dbTable = new RL1Table();
        
        //fixtures data & table        
        $this->setParser_fixtures($xmlParser);
        $xmlParser->parse();        
        $dbTable->saveBatchData($dbTable->rl1_fixtures, $xmlParser->fetchResults());
        
        //team data & table        
        $this->setParser_team($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData($dbTable->rl1_team, $xmlParser->fetchResults());
        
        //fixtures_team data & table        
        $this->setParser_fixtures_team($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData($dbTable->rl1_fixtures_teams, $xmlParser->fetchResults());
        
        return true;
    }
    
    public function getDBTableDefinition($tableName) {
        $dbTable = new RL1Table();
        
        return $dbTable->getTableDefinition($tableName);
    }
    
    ////////////////////////////////////////////////////////////////parser configuration///////////////////////
    private function setParser_fixtures($xmlParser){
        $xmlParser->setEvent(NodeName, "fixture");
        $xmlParser->addAction(GetAttributes);
    }
    
    private function setParser_fixtures_team($xmlParser){
        $xmlParser->setEvent(NodeNameInParent, array("fixture"=>"team"));
        $xmlParser->addAction(GetParentAttribute, "id");
        $xmlParser->addAction(GetAttributes);
    }    
    
    private function setParser_team($xmlParser){
        $xmlParser->setEvent(NodeNameInParent, array("teams"=>"team"));
        $xmlParser->addAction(GetAttributes);
    }
}