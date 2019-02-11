<?php 
?>
<?php 
/*****************************************************************************
 * model.php
 * Contient les classes représentant le model métier de l'application
 *****************************************************************************/
class Session {
	public $init;
	public $user;	// User
	public $dao;	// catalogue Dao

	public $game;	// Game
	public $page;	// current page name
	public $id_player;
	public $id_tournament;

	public $gameMap;
	public $characterMap;
	
	public $characterPath;

	
	function __construct() {
		$init = false;
	}
}

class User {
	public $login;
	public $right;
	
	function __construct($right='') {
		$this->right 	= $right;
	}
}

class Game {
	public $id;
	public $code;
	public $name;
	public $id_char_unknown;
	public $char_unknown;
	public $cssFile;

	function __construct($id=null) {
		$this->id 	= $id;
	}
}
 
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
	
	function __construct($id=null) {
		$this->id 	= $id;
	}
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

	function __construct($id_game=null) {
		$this->id_game 	= $id_game;
	}
}
 
class Character {
	public $id;
	
	public $id_game;
	public $game;
	
	public $name;
	public $css_class;
	public $filename;
	
	function __construct($id=null) {
		$this->id 	= $id;
	}
	
	function __toString() {
		return "id=$this->id ; id_game=$this->id_game ; name=$this->name ; css=$this->css_class ; file=$this->filename";
	}
}

class Player {
	public $id;
	public $pseudo;
	public $nom;
	public $prenom;
	public $email;
	public $telephone;
	public $status;
	
	function __construct($id=null) {
		$this->id 	= $id;
	}
}

class Participant extends Player {
	public $id_player;
	public $id_tournament;
	public $ranking;

	// character info 
	public $id_game;
	
	public $name;
	public $css_class;

	function __construct($id_player=null) {
		$this->id_player 	= $id_player;
	}
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

	function __construct($id=null) {
		$this->id 	= $id;
	}
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
	

	function __construct($id=null) {
		$this->id 	= $id;
	}
}

?>