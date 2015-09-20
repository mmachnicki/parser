<?php
require_once DOCUMENT_ROOT.'/xmlParser/database/Table.abstract.php';

class RL1Table extends Table{
    public $rl1_fixtures, $rl1_fixtures_teams, $rl1_team;
    
    protected function initialise() {
        
        //three tables in here: RL1_fixtures, RL1_fixtures_teams, RL1_team   
        $this->rl1_fixtures = "RL1_fixtures";
        $this->rl1_fixtures_teams = "RL1_fixtures_teams";
        $this->rl1_team = "RL1_team";
        
        /*
         * RL1_fixtures
         */
        $this->table($this->rl1_fixtures, array(
            "id"=>"id INT PRIMARY KEY",
            "comp_id"=>"competitionID INT",
            "comp_name"=>"competitionName VARCHAR(128)",
            "game_date"=>"gameDate VARCHAR(32)",
            "group"=>"groupType VARCHAR(64)",
            "group_name"=>"groupName VARCHAR(64)",
            "leg"=>"leg VARCHAR(16)",
            "public"=>"public VARCHAR(16)",
            "round"=>"round INT",            
            "round_type_id"=>"roundTypeID INT",
            "stage"=>"stage INT",
            "status"=>"status VARCHAR(64)",
            "time"=>"time VARCHAR(32)",
            "venue"=>"venue VARCHAR(128)",
            "venue_id"=>"venueID INT"
            ));
        
        /*
         * RL1_fixtures_teams
         */
        $this->table($this->rl1_fixtures_teams, array(
            "fixture.id"=>"fixtureID INT",
            "eighty_min_score"=>"eightyMinScore INT",
            "home_or_away"=>"homeOrAway VARCHAR(64)",
            "score"=>"score INT",
            "team_id"=>"teamID INT"));
        $this->addedStatments($this->rl1_fixtures_teams, array(
            "PRIMARY KEY(fixtureID, homeOrAway, teamID)",
//            "FOREIGN KEY(teamID) REFERENCES RL1_team (id)"
        ));
        $this->relations($this->rl1_fixtures_teams, array(
            "$this->rl1_fixtures.id"=>"$this->rl1_fixtures_teams.fixtureID",
            "$this->rl1_fixtures_teams.teamID"=>"$this->rl1_team.id"
            ));
                
        /*
         * RL1_team
         */
        $this->table($this->rl1_team, array(
            "id"=>"id INT PRIMARY KEY",
            "name"=>"name VARCHAR(128)"));
        
        $this->addedOnInitial($this->rl1_team, array("INSERT INTO $this->rl1_team  VALUES(0, ' ')"));
    }    
}