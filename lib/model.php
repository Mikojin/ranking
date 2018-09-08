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
	public $new_points;
	
	function Player() {
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

	function Ranking() {
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
	
	function Player() {
	}
}

class Character {
	public $id;
	
	public $id_game;
	public $game;
	
	public $name;
	public $css_class;
	public $filename;
	
	function Character() {
	}
}




?>