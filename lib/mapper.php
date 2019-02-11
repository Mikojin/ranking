<?php 
?>
<?php 
/*****************************************************************************
 * mapper.php
 * Contient les fonctions de mapping Database => Object
 *****************************************************************************/

require_once("./lib/model.php"); 

/***********************************************************************
 * Construit un Player à partir du resultat de la requete getInfoRanking
 */
function mapperPlayerWrapper($row) {
	$o = new PlayerWrapper();

	$o->id 				= $row['id'];
	$o->pseudo 			= $row['pseudo'];
	$o->nom 			= $row['nom'];
	$o->prenom 			= $row['prenom'];
	$o->points 			= $row['points'];
	$o->previous_points = $row['previous_points'];
	$o->new_points		= $row['new_points'];
	$o->character 		= $row['character'];
	$o->id_char 		= $row['id_char'];
	$o->game 			= $row['game'];
	$o->id_game 		= $row['id_game'];
	$o->current_rank 	= $row['current_rank'];
	$o->previous_rank 	= $row['previous_rank'];
	
	return $o;										
}

function doMapperPlayer($o, $row) {
	$o->id			= $row['id'];
	$o->pseudo		= $row['pseudo'];
	$o->nom			= $row['nom'];
	$o->prenom		= $row['prenom'];
	$o->email		= $row['mail'];
	$o->telephone	= $row['telephone'];
	$o->status		= $row['status'];
	
	return $o;
}

function mapperPlayer($row) {
	$o = new Player();
	
	$o = doMapperPlayer($o, $row);
	
	return $o;
}

function mapperParticipant($row) {
	$o = new Participant();
	
	$o = doMapperPlayer($o, $row);
	
	$o->id_player 		= $row['id_player'];
	$o->id_tournament 	= $row['id_tournament'];
	$o->ranking		 	= $row['ranking'];

	if(isset($row['css_class'])) {
		$o->id_game			= $row['id_game'];
		$o->name			= $row['name'];
		$o->css_class		= $row['css_class'];
	}
	$o->score			= 0;
	if(isset($row['score'])) {
		$o->score		= $row['score'];
	}
	
	return $o;
}

function mapperGame($row) {
	$o = new Game();
	$o->id				= $row['id'];
	$o->code			= $row['code'];
	$o->name 			= $row['name'];
	$o->id_char_unknown	= $row['id_char_unknown'];
	
	return $o;
}

function mapperCharacter($row) {
	$o = new Character();
	
	$o->id			=$row['id'];
	$o->id_game		=$row['id_game'];
	$o->name		=$row['name'];
	$o->css_class	=$row['css_class'];
	$o->filename	=$row['filename'];
	
	return $o;
}

function mapperTournament($row) {
	$o = new Tournament();
	
	$o->id				=$row['id'];
	$o->id_game			=$row['id_game'];
	$o->id_type_score	=$row['id_type_score'];
	$o->group_name		=$row['group_name'];
	$o->name			=$row['name'];
	$o->date_start		=$row['date_start'];
	$o->date_end 		=$row['date_end'];
	
	return $o;
}

function mapperScoring($row) {
	$o = new Scoring();
	
	$o->id				=$row['id'];
	$o->id_type_score   =$row['id_type_score'];
	$o->rank_top        =$row['rank_top'];
	$o->rank_bottom     =$row['rank_bottom'];
	$o->score           =$row['score'];

	return $o;
}

function mapperSeason($row) {
	$o = new Season();
	
	$o->id			=$row['id'];
	$o->name   		=$row['name'];
	$o->date_start	=$row['date_start'];
	$o->date_end	=$row['date_end'];

	return $o;
}