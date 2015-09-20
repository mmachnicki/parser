<?php
require_once DOCUMENT_ROOT.'/xmlParser/database/Table.abstract.php';

class RU6Table extends Table{
    protected $RU6_officialsTable, $RU6_matchDescriptionTable, $RU6_matchTable, $RU6_eventsTable, $RU6_subsTable, $RU6_team_playerTable;
    protected $RU1_teamTable, $RU1_fixturesTable;
    
    protected function initialise() { 
        $this->RU6_officialsTable = "RU6_officials";
        $this->RU6_matchDescriptionTable = "RU6_match_description";
        $this->RU6_matchTable = "RU6_match";
        $this->RU6_eventsTable = "RU6_events";
        $this->RU6_subsTable = "RU6_subs";
        $this->RU6_team_playerTable = "RU6_team_player";
        
        $this->RU1_teamTable = "RU1_team";
        $this->RU1_fixturesTable = "RU1_fixtures";
        
        //////officials///////
        $this->table($this->RU6_officialsTable, array(
            "match.game-id"=>"gameID INT NOT NULL",
            "id"=>"id INT",
            "official_name"=>"name VARCHAR(128)",
            "role"=>"role VARCHAR(64)"
        ));
        $this->addedStatments($this->RU6_officialsTable, array(
            "PRIMARY KEY(gameID, id, role)"
        ));
        
        //////match description///////
        $this->table($this->RU6_matchDescriptionTable, array(
            "fingerprint"=>"fingerprint VARCHAR(32) PRIMARY KEY",
            "match.game-id"=>"gameID INT",
            "format"=>"format VARCHAR(64)",
            "length"=>"length INT",
            "text"=>"description BLOB"
        ));
        
        //////match///////
        $this->table($this->RU6_matchTable, array(
            "game-id"=>"id INT PRIMARY KEY",
            "attendance"=>"attendance INT",
            "grp-id"=>"groupID INT",
            "period_minute"=>"periodMinute INT",
            "period_second"=>"periodSecond INT",
            "rnd-id"=>"roundID INT",
            "status"=>"status VARCHAR(64)"
        ));
        
        //////events///////
        $this->table($this->RU6_eventsTable, array(            
            "match.game-id"=>"gameID INT",
            "team-id"=>"teamID INT DEFAULT 0",
            "player-code"=>"playerID INT DEFAULT 0",
            "player-name"=>"playerName VARCHAR(128) DEFAULT ''",
            "period"=>"period VARCHAR(64)",
            "secs"=>"secs INT",
            "time"=>"time INT",
            "type"=>"type VARCHAR(64)",
            "away-team.team-id"=>"awayTeamID INT",
            "home-team.team-id"=>"homeTeamID INT",
            "away-team.score"=>"awayTeamScore INT",
            "home-team.score"=>"homeTeamScore INT",
            "broadcasted"=>"broadcasted INT DEFAULT 0"
        ));
        $this->addedStatments($this->RU6_eventsTable, array(
            "PRIMARY KEY(gameID, teamID, period, time, secs, type)"
        ));        
        $this->primaryCompositionKey($this->RU6_eventsTable, array("gameID", "teamID", "period", "time", "secs", "type"));
        $this->relations($this->RU6_eventsTable, array(
            "$this->RU6_eventsTable.teamID"=>"$this->RU1_teamTable.id",
            "$this->RU6_eventsTable.gameID"=>"$this->RU1_fixturesTable.id"            
        ));
        
        ///////substitutions///////
        $this->table($this->RU6_subsTable, array(
            "match.game-id"=>"gameID INT",
            "period"=>"period VARCHAR(64)",
            "secs"=>"secs INT",
            "time"=>"time INT",
            "player-code"=>"playerID INT",
            "team-id"=>"teamID INT",
            "event_name"=>"type VARCHAR(64)",
            "away-team.team-id"=>"awayTeamID INT",
            "home-team.team-id"=>"homeTeamID INT",
            "away-team.score"=>"awayTeamScore INT",
            "home-team.score"=>"homeTeamScore INT",
            "broadcasted"=>"broadcasted INT DEFAULT 0"
        ));
        $this->addedStatments($this->RU6_subsTable, array(
            "PRIMARY KEY(gameID, period, secs, time, playerID, teamID)"
        ));
        $this->primaryCompositionKey($this->RU6_subsTable, array("gameID", "period", "secs", "time", "playerID", "teamID"));
        
        $this->relations($this->RU6_subsTable, array(
            "$this->RU6_subsTable.teamID"=>"$this->RU1_teamTable.id",
            "$this->RU6_subsTable.gameID"=>array(
                "$this->RU1_fixturesTable.id", 
                "$this->RU6_team_playerTable.gameID"
            ),
            "$this->RU6_subsTable.playerID"=>"$this->RU6_team_playerTable.playerID"            
        ));
        
        ///////teams, players, squad/////////////
        $this->table($this->RU6_team_playerTable, array(
            "match.game-id"=>"gameID INT",
            "teams.team_id"=>"teamID INT",
            "teams.home_or_away"=>"homeOrAway VARCHAR(32)",
            "id"=>"playerID INT",
            "first"=>"playerFirstName VARCHAR(64)",
            "known_name"=>"playerKnownName VARCHAR(128)",
            "last"=>"playerLastName VARCHAR(64)",
            "position_id"=>"positionID INT",
            "shirt_no"=>"shirtNo INT"
        ));
        $this->addedStatments($this->RU6_team_playerTable, array(
            "PRIMARY KEY(gameID, teamID, playerID)"
        ));
    }
}