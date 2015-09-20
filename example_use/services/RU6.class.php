<?php
require_once __DIR__.'/ParserVisitable.abstract.php';
require_once __DIR__.'/dbTableLayouts/RU6Table.class.php';

class RU6 extends ParserVisitable{
    
    protected function updateData($xmlString) {
        $xmlParser = new XMLParser();
        $xmlParser->setString($xmlString);
        $dbTable = new RU6Table();        
        
        $this->setParser_match_description($xmlParser);
        $xmlParser->parse();        
        $dbTable->saveBatchData("RU6_match_description", $xmlParser->fetchResults());
        
        $this->setParser_officials($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData("RU6_officials", $xmlParser->fetchResults());
        
        $this->setParser_match($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData("RU6_match", $xmlParser->fetchResults());
        
        $this->setParser_events($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData("RU6_events", $xmlParser->fetchResults());
        
        $this->setParser_subs($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData("RU6_subs", $xmlParser->fetchResults());
        
        $this->setParser_team_player($xmlParser);
        $xmlParser->parse();
        $dbTable->saveBatchData("RU6_team_player", $xmlParser->fetchResults());
        
        return true;
    }
    
    public function getDBTableDefinition($tableName) {
        $dbTable = new RU6Table();
        
        return $dbTable->getTableDefinition($tableName);
    }
    
    private function setParser_match_description($xmlParser){
        $xmlParser->setEvent(NodeNameInParent, array("match"=>"descriptions"));
        $xmlParser->addAction(CreateFingerprintOfChildValue, "text");
        $xmlParser->addAction(GetAncestorsAttributes, array("match"=>"game-id"));
        $xmlParser->addAction(GetAttributes);
        $xmlParser->addAction(GetChildValue, "text");
    }
    
    private function setParser_officials($xmlParser){
        $xmlParser->setEvent(NodeName, "Official");        
        $xmlParser->addAction(GetParentSiblingAttribute, array("match"=>"game-id"));
        $xmlParser->addAction(GetAttributes);        
    }
    
    private function setParser_match($xmlParser){
        $xmlParser->setEvent(NodeName, "match");
        $xmlParser->addAction(GetAttributes);        
    }
    
    private function setParser_events($xmlParser){
        $xmlParser->setEvent(NodeNameInParent, array("events"=>"event"));
        $xmlParser->addAction(GetAncestorsAttributes, array("match"=>"game-id"));
        $xmlParser->addAction(GetAttributes);
        $xmlParser->addAction(GetChildValue, "team-id");
        $xmlParser->addAction(GetChildValue, "player-code");
        $xmlParser->addAction(GetChildValue, "player-name");
        $xmlParser->addAction(GetParentSiblingChildValue, array("home-team"=>"team-id"));
        $xmlParser->addAction(GetParentSiblingChildValue, array("away-team"=>"team-id"));
        $xmlParser->addAction(GetParentSiblingChildValue, array("away-team"=>"score"));
        $xmlParser->addAction(GetParentSiblingChildValue, array("home-team"=>"score"));
    }
    
    private function setParser_subs($xmlParser){
        $xmlParser->setEvent(NodeNameInParent, array("subs"=>"sub"));
        $xmlParser->addAction(GetAncestorsAttributes, array("match"=>"game-id"));
        $xmlParser->addAction(GetAttributes);
        $xmlParser->addAction(GetChildValue, "player-code");
        $xmlParser->addAction(GetChildValue, "team-id");
        $xmlParser->addAction(GetChildAttributes, "type");
        $xmlParser->addAction(GetParentSiblingChildValue, array("home-team"=>"team-id"));
        $xmlParser->addAction(GetParentSiblingChildValue, array("away-team"=>"team-id"));
        $xmlParser->addAction(GetParentSiblingChildValue, array("away-team"=>"score"));
        $xmlParser->addAction(GetParentSiblingChildValue, array("home-team"=>"score"));
    }
    
    private function setParser_team_player($xmlParser){
        $xmlParser->setEvent(NodeNameInParent, array("teams"=>"Player"));
        $xmlParser->addAction(GetAncestorsAttributes, array("match"=>"game-id"));
        $xmlParser->addAction(GetParentAttribute, "team_id");
        $xmlParser->addAction(GetParentAttribute, "home_or_away");
        $xmlParser->addAction(GetAttributes);
    }
}