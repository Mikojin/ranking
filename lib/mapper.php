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
	$o->new_points		= $row['new_points'];
	$o->character 		= $row['character'];
	$o->id_char 		= $row['id_char'];
	$o->game 			= $row['game'];
	$o->id_game 		= $row['id_game'];
	$o->current_rank 	= $row['current_rank'];
	$o->previous_rank 	= $row['previous_rank'];
	
	return $o;										
}


function mapperPlayer($row) {
	$o = new Player();
	
	$o->id			= $row['id'];
	$o->pseudo		= $row['pseudo'];
	$o->nom			= $row['nom'];
	$o->prenom		= $row['prenom'];
	$o->email		= $row['mail'];
	$o->telephone	= $row['telephone'];
	$o->status		= $row['status'];
	
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

function mapperScoring($row) {
	$o = new Scoring();
	
	$o->id				=$row['id'];
	$o->id_type_score   =$row['id_type_score'];
	$o->rank_top        =$row['rank_top'];
	$o->rank_bottom     =$row['rank_bottom'];
	$o->score           =$row['score'];

	return $o;
}