<?php 
?>
<?php 
/*****************************************************************************
 * model.php
 * Contient les classes représentant le model métier de l'application
 *****************************************************************************/

 
class PlayerWrapper {
	public $id;
	public $rank_classe;
	public $rank;
	public $rank_display;
	public $current_rank;
	public $previous_rank;
	public $pseudo;
	public $prenom;
	public $nom;
	public $classe;
	public $character;
	public $id_char;
	public $game;
	public $id_game;
	public $points;
	public $previous_points;
	public $new_points;
	
}
 

class Ranking {
	public $id_game;
	public $game;
	
	public $id_player;
	public $player;

	public $points;
	public $new_points;

	public $id_char;
	public $character;
	
	public $current_rank;
	public $previous_rank;
	
	public $update_date;
	

	public $rank_classe;
	public $rank;
	public $rank_display;

}
 
class Character {
	public $id;
	
	public $id_game;
	public $game;
	
	public $name;
	public $css_class;
	public $filename;
	
}

class Player {
	public $id;
	public $pseudo;
	public $nom;
	public $prenom;
	public $email;
	public $telephone;
	public $status;
	
}

class Participant extends Player {
	public $id_player;
	public $id_tournament;
	public $ranking;
}

class Tournament {
	public $id;
	
	public $id_game;
	public $game;

	public $id_type_score;
	public $type_score;
	
	public $group_name;
	public $name;
	public $date_start;
	public $date_end;
}

class Scoring {
	public $id;
	public $id_type_score;
	public $rank_top;
	public $rank_bottom;
	public $score;
	
	function __construct($id_type_score=null, $rank_top=0, $rank_bottom=0, $score=0) {
		$this->id_type_score 	= $id_type_score;
		$this->rank_top			= $rank_top;
		$this->rank_bottom		= $rank_bottom;
		$this->score			= $score;
	}
}

class Season {
	public $id;
	public $name;
	public $date_start;
	public $date_end;
	
}

?>