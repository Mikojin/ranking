<?php 
?>
<?php 
/*****************************************************************************
 * dao.php
 * Fichier comprenant les requetes d'accès à la base de donnée.
 * Select, Update, Insert etc...
 *****************************************************************************/

require_once("./lib/lib_tools.php");
require_once "./lib/lib_dao.php";
require_once("./lib/mapper.php"); 

/*######################################################################
        Character
 #######################################################################*/

class CharacterDao extends AbstractDao {
 
	/***********************************************************************
	 * Renvoie la liste complete des joueurs
	 * */
	function getList($id_game) {		
		$sql = "select * 
		from `character` c
		where c.id_game = $id_game
		order by c.name ";
		
		LibTools::setLog("CharacterDao.getList");
		$arr = $this->fetch_map($sql, 'id', 'mapperCharacter');
		
		return $arr;
	}
}

/*######################################################################
        Histo Ranking
 #######################################################################*/

class GameDao extends AbstractDao {
	/***********************************************************************
	 * Renvoie la liste complete des jeux
	 * */
	function getList() {		
		$sql = "select g.* 
		from `game` g
		order by g.name ";
		
		$arr = $this->fetch_map($sql, 'id', 'mapperGame');
		
		return $arr;
	}
}

/*######################################################################
        Histo Ranking
 #######################################################################*/

class HistoRankingDao extends AbstractDao {

	/***********************************************************************
	 * Ajoute une nouvelle ligne dans l'historique sans ouvrir de connexion
	 * */
	function doInsertHistoRanking($mysqli, $id, $id_game, $id_char, $points, $rank) {
		$sql = "select max(id_update)+1 as id_update from historanking";
		$row = $this->fetch_one($sql);
		$id_update = $row["id_update"];
		
		$sql = " insert into historanking
		(id_update, id_player, id_game, id_char, points, rank)
		values
		( $id_update,$id, $id_game, $id_char, $points, $rank)";
		
		$result = $mysqli->query($sql);
		
		check($result, "Insert historanking OK : $id");
		return true;
	}
}
 

/*######################################################################
        Param
 #######################################################################*/

class ParamDao extends AbstractDao {
	/***********************************************************************
	 * Renvoie la map des parametres pour le groupe $group_name
	 * */
	function loadGroup($group_name) {
		$sql = "
			select p.*
			  from param p
			 where p.group_name = '$group_name' 
		";
		$map = $this->fetch_map($sql, 'variable');
		return $map;
	}

	/***********************************************************************
	 * Renvoie la valeur du parametre pour le groupe et le nom de variable donnée
	 * */
	function load($group_name, $variable) {
		$sql = "
			select p.*
			  from param p
			 where p.group_name = '$group_name' 
			   and p.variable   = '$variable'
		";
		LibTools::setLog("load Param : $group_name ; $variable");
		$map = $this->fetch_one($sql);
		return $map['value'];
	}

	/***********************************************************************
	 * sauvegarde le parametre donnée.
	 * $group_name nom du groupe auquel appartient le parametre
	 * $var nom du parametre
	 * $value valeur du parametre
	 * */
	function save($group_name, $var, $value) {
		$sql = "
			update param
			  set value = '$value'
			where group_name = '$group_name'
			  and variable = '$var'
		";
		
		return $this->exec_query($sql, "save param OK : $group_name, $var=$value");	
	}
}


/*######################################################################
        Participant
 #######################################################################*/

class ParticipantDao extends AbstractDao {
	/***********************************************************************
	 * insert un nouveau participant
	 */
	function insert($id_tournament, $id_player, $ranking=0) {
		
		$sql = " insert into participant
		(id_tournament, id_player, ranking)
		values
		($id_tournament, $id_player, $ranking)";

		$this->exec_query($sql, "Insert Participant OK : $id_player");
	}
	
	/***********************************************************************
	 * sauvegarde le participant
	 */
	function save($id_tournament, $id_player, $ranking=0) {
		
		$sql = " update participant
		  set ranking       = $ranking
		where id_tournament = $id_tournament
		  and id_player     = $id_player";

		$this->exec_query($sql, "Save Participant OK : $id_tournament, $id_player, $ranking");
	}
	
	/***********************************************************************
	 * renvoie la liste des participants pour ce tournoi 
	 */
	function getList($id_tournament) {
		$sql = "select t.id_game, pp.*, p.*, s.score
			 from participant pp
			 join tournament t
			   on t.id = pp.id_tournament
			 join player p
			   on p.id = pp.id_player
			 join type_score ts
			   on ts.id = t.id_type_score
			 left outer join scoring s
			   on s.id_type_score = ts.id
			  and s.rank_top 	<= pp.ranking
			  and s.rank_bottom >= pp.ranking
			where t.id 		 	= $id_tournament
			order by pp.ranking, p.pseudo, p.prenom, p.nom  ";
		
		LibTools::setLog("Participant.getList");
		$participantList = $this->fetch_map($sql, 'id', 'mapperParticipant');
		return $participantList;
	}
	
	/***********************************************************************
	 * renvoie la liste personnage joué par les joueur du tournoi
	 */
	function getPlayerCharacterList($id_tournament) {
		$sql = "select pp.id_player, t.id_game, c.id as id_char, c.name, c.css_class
			 from participant pp
			 join tournament t
			   on t.id = pp.id_tournament
			 join player p
			   on p.id = pp.id_player
			 join player_game pg
			   on pg.id_player		= pp.id_player
			  and pg.id_game		= t.id_game
			 join `character` c
			   on c.id 			= pg.id_character
			  and c.id_game		= pg.id_game 
			where t.id 		 	= $id_tournament
			";
		
		LibTools::setLog("Participant.getPlayerCharacterList");
		$playerCharList = $this->fetch_map($sql, 'id_player');
		return $playerCharList;
	}
	
	/***********************************************************************
	 * supprime un participant
	 */
	function deleteParticipant($id_tournament, $id_player) {
		$sql = " delete from participant
		where id_tournament = $id_tournament
		  and id_player     = $id_player";

		$this->exec_query($sql, "delete Participant OK : $id_tournament, $id_player");
	}
}

/*######################################################################
        Player
 #######################################################################*/

class PlayerDao extends AbstractDao {

	/***********************************************************************
	 * Renvoie la map des parametre pour le groupe $group_name
	 * */
	function insert($pseudo, $prenom, $nom, $mail, $tel ) {
		if(LibTools::isBlank($pseudo)) {
			LibTools::setLog("Insert New Player : Le pseudo est obligatoire");
			return;
		}
		if(LibTools::isBlank($prenom)) {
			LibTools::setLog("Insert New Player : Le prenom est obligatoire");
			return;
		}
		if(LibTools::isBlank($nom)) {
			LibTools::setLog("Insert New Player : Le nom est obligatoire");
			return;
		}
		
		$mysqli = $this->open();
		
		$pseudo = stripslashes($pseudo	);
		$prenom = stripslashes($prenom	);
		$nom 	= stripslashes($nom		);
		$mail 	= stripslashes($mail	);
		$tel 	= stripslashes($tel		);
		$pseudo = $mysqli->real_escape_string($pseudo 	);
		$prenom = $mysqli->real_escape_string($prenom 	);
		$nom 	= $mysqli->real_escape_string($nom 	);
		$mail 	= $mysqli->real_escape_string($mail 	);
		$tel 	= $mysqli->real_escape_string($tel 	);
		
		$sql = "insert into player 
		(pseudo, prenom, nom, mail, telephone)
		values
		('$pseudo', '$prenom', '$nom', '$mail', '$tel')
		";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "insert new player OK : ps=$pseudo, pr=$prenom, n=$nom, m=$mail, t=$tel");
		$this->close($mysqli);
		return $out;
	}

	function save($id, $pseudo, $prenom, $nom, $mail, $tel ) {
		if(LibTools::isBlank($id)){
			LibTools::setLog("Save Player : id obligatoire");
			return;
		}
		if(LibTools::isBlank($pseudo)) {
			LibTools::setLog("Save Player : Le pseudo est obligatoire");
			return;
		}
		if(LibTools::isBlank($prenom)) {
			LibTools::setLog("Save Player : Le prenom est obligatoire");
			return;
		}
		if(LibTools::isBlank($nom)) {
			LibTools::setLog("Save Player : Le nom est obligatoire");
			return;
		}
		
		$mysqli = $this->open();
		
		$pseudo = stripslashes($pseudo	);
		$prenom = stripslashes($prenom	);
		$nom 	= stripslashes($nom		);
		$mail 	= stripslashes($mail	);
		$tel 	= stripslashes($tel		);
		$pseudo = $mysqli->real_escape_string($pseudo 	);
		$prenom = $mysqli->real_escape_string($prenom 	);
		$nom 	= $mysqli->real_escape_string($nom 	);
		$mail 	= $mysqli->real_escape_string($mail 	);
		$tel 	= $mysqli->real_escape_string($tel 	);

		// LibTools::setLog("Save Player TEST : $id, $pseudo, $prenom, $nom, $mail, $tel");
		// return;
		
		$sql = "update player 
		set pseudo='$pseudo', prenom='$prenom', nom='$nom', mail='$mail', telephone='$tel'
		where id=$id
		";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "save player OK : id=$id, ps=$pseudo, pr=$prenom, n=$nom, m=$mail, t=$tel");
		$this->close($mysqli);
		return $out;
	}

	/***********************************************************************
	 * bascule le status du joueur entre masqué et affiché
	 * */
	function deletePlayer($id) {
		$sql = "delete from player 
			where id=$id
		";
		return $this->exec_query($sql, "delete player OK : $id");	
	}
	
	/***********************************************************************
	 * bascule le status du joueur entre masqué et affiché
	 * */
	function toggleStatus($id) {
		$sql = "update player 
		set `status` = CASE `status`
			WHEN 'H' then NULL
			ELSE 'H'
			END
		where id=$id
		";
		return $this->exec_query($sql, "toggle status player OK : $id");	
	}
	
	/***********************************************************************
	 * Renvoie le joueur donné
	 * */
	function get($id_player) {	
		$sql = "select p.* 
		from player p
		where p.id = $id_player";

		$player = $this->fetch_one($sql, 'mapperPlayer');
		
		return $player;
	}
	
	/***********************************************************************
	 * Renvoie la liste de tous les joueurs
	 * */
	function getListAll() {	
		$sql = "select p.* 
		from player p
		order by p.pseudo, p.prenom, p.nom ";

		$playerList = $this->fetch_map($sql, 'id', 'mapperPlayer');
		
		return $playerList;
	}

	/***********************************************************************
	 * Renvoie la liste de tous les joueurs actif
	 * */
	function getList() {	
		$sql = "select p.* 
		from player p
		where p.status is null
		order by p.pseudo, p.prenom, p.nom ";

		$playerList = $this->fetch_map($sql, 'id', 'mapperPlayer');
		
		return $playerList;
	}

	/***********************************************************************
	 * Renvoie la liste des joueurs disponible pour le tournois
	 * */
	function getListTournament($idTournament) {	
		$sql = "select p.* 
		from player p
		left outer join participant pp
		  on pp.id_tournament = $idTournament
		 and pp.id_player = p.id
		where p.status is null
		and pp.id_player is null
		order by p.pseudo, p.prenom, p.nom ";

		$playerList = $this->fetch_map($sql, 'id', 'mapperPlayer');
		
		return $playerList;
	}
	
	/***********************************************************************
	 * Renvoie la liste des résultats de tournois pour lequel le joueur a participé
	 * */
	function getParticipationList($id_player) {
		$sql = "select ts.* 
		from tournament_score ts
		where ts.id_player = $id_player
		order by ts.date_start desc ";

		$participationList = $this->fetch_array($sql);
		return $participationList;
	}
	
	/***********************************************************************
	 * Renvoie la liste des joueurs ne se trouvant pas dans le ranking du jeu
	 * */
	function getListNotRanked($id_game) {	
		$sql = "select p.* 
		from player p
		left outer join ranking r
		  on r.id_player = p.id
		 and r.id_game = $id_game
		where r.id_player is null 
		  and p.status is null
		order by p.pseudo, p.prenom, p.nom ";

		$playerList = $this->fetch_map($sql, 'id');
		
		return $playerList;
	}
}

/*######################################################################
        Ranking
 #######################################################################*/

class PlayerGameDao extends AbstractDao {
	
	/***********************************************************************
	 * Renvoie la liste des joueurs ne se trouvant pas dans le ranking du jeu
	 * */
	function getList($id_player) {	
		$sql = "select * 
		from player_game pg
		join game g
		  on g.id = pg.id_game
		where id_player = $id_player
		order by g.name";

		$gameList = $this->fetch_map($sql, 'id');
		
		return $gameList;
	}
	
	function remove($id_player, $id_game) {
		$sql = "delete from player_game
		where id_player = $id_player
		  and id_game 	= $id_game";

		$out = $this->exec_query($sql, "delete player game OK : g=$id_game, p=$id_player");
		return $out;
	}
	
	function insert($id_player, $id_game, $id_char=null) {
		$sql = " insert into player_game
		(id_player, id_game, id_character)
		values
		($id_player, $id_game, $id_char)";
		
		$this->exec_query($sql, "Insert Player Game OK : $id_player, $id_game, $id_char");
	}

	/***********************************************************************
	 * sauvegarde le personnage joué par le joueur
	 */
	function save($id_player, $id_game, $id_character) {
		
		$sql = " update player_game
		  set id_character  = $id_character
		where id_player     = $id_player
		  and id_game       = $id_game";

		$this->exec_query($sql, "Save Player Game OK : $id_player, $id_game, $id_character");
	}

}

/*######################################################################
        Ranking
 #######################################################################*/

class RankingDao extends AbstractDao {
	 
	/***********************************************************************
	 * insert un nouveau joueur dans le ranking
	 * */
	function insert($id_game, $id_player, $id_char, $points) {		
		$sql = "insert into ranking  
		(id_game,  id_player,  id_char,  points, new_points)
		values
		($id_game, $id_player, $id_char, $points, $points)";
		
		$out = $this->exec_query($sql, "insert player ranking OK : g=$id_game, p=$id_player, c=$id_char, s=$points");
		return $out;
	}

	/***********************************************************************
	 * retire un nouveau joueur dans le ranking
	 * */
	function remove($id_game, $id_player) {		
		$sql = "delete from ranking
		where id_game = $id_game
		  and id_player = $id_player";

		$out = $this->exec_query($sql, "delete player ranking OK : g=$id_game, p=$id_player");
		return $out;
	}

	/***********************************************************************
	 * Sauvegarde uniquement new_points
	 * valeur attendu :
	 * $playerList :  map($id_player => $player)
	 * $player['new_points']
	 * */
	function saveNewPoint($id_game, $playerList) {
		$mysqli = $this->open();

		foreach ($playerList as $id => $player) {
			$rank 		= $player['rank'];
			$new_points	= $player['new_points'];
			$id_char 	= $player['id_char'];
			
			$sql = "update ranking r
			set r.current_rank 	= $rank,
				r.new_points 	= $new_points, 
				r.id_char 		= $id_char 
			where r.id_player 	= $id 
			  and r.id_game		= $id_game";
			
			$result = $mysqli->query($sql);
			
			$this->check($result, $mysqli, "Save score OK : i=$id, r=$rank, c=$id_char, np=$new_points");
		}
		
		$this->close($mysqli);
	}


	/***********************************************************************
	 * Met à jour le score points et new_points 
	 * */
	function updateScore($id_game, $playerList) {
		$mysqli = $this->open();

		foreach ($playerList as $id => $player) {
			$rank 		= $player['rank'];
			$point		= $player['points'];
			$new_points	= $player['new_points'];
			$id_char 	= $player['id_char'];

			$sql = "update ranking r
			set r.current_rank 	= $rank,
				r.new_points 	= $new_points, 
				r.points 		= $new_points, 
				r.id_char 		= $id_char
			where r.id_player 	= $id
			and r.id_game 		= $id_game ";
			
			//echo " $id => $score";
			$result = $mysqli->query($sql);

			$this->check($result, $mysqli, "Update Score OK : i=$id, r=$rank, c=$id_char, np=$new_points");
		}

		$this->close($mysqli);
		return true;
	}

		
	/***********************************************************************
	 * Met à jour le rank et le previous rank à partir du classement 
	 * */
	function doUpdatePreviousRank($mysqli, $id, $id_game, $rank) {				
		$sql = " update ranking r
		set r.previous_rank = $rank
		where r.id_player = $id
		  and r.id_game = $id_game";
		
		$result = $mysqli->query($sql);
		
		$this->check($result, $mysqli, "Update Previous ranking OK : $id => $rank");
		return true;
	}
}

/*######################################################################
        Scoring
 #######################################################################*/

class ScoringDao extends AbstractDao {
	/***********************************************************************
	 * insert un nouveau scoring
	 */
	function insert($id_type_score, $rank_top, $rank_bottom, $score) {
		
		$sql = " insert into scoring
		(id_type_score, rank_top, rank_bottom, score)
		values
		($id_type_score, $rank_top, $rank_bottom, $score)";
		
		$this->exec_query($sql, "Insert scoring OK : $id_type_score, $rank_top, $rank_bottom, $score");
	}

	/***********************************************************************
	 * Renvoie la liste complete des scoring pour le type_score donnée
	 * */
	function getList($id_type_score) {
		if(LibTools::isBlank($id_type_score)) {
			return array();
		}
		$sql = "select s.* 
		from scoring s
		where s.id_type_score = $id_type_score
		order by s.rank_top asc, s.rank_bottom asc";
		
		$arr = $this->fetch_array($sql, 'mapperScoring');
		
		return $arr;
	}

	/***********************************************************************
	 * Renvoie le score dans le type_score pour le rank donné
	 * */
	function getScore($id_type_score, $rank) {
		$sql = "select s.* 
		from `scoring` s
		where s.id_type_score = $id_type_score
		  and s.rank_top <= $rank
		  and s.rank_bottom >= $rank
		order by s.rank_top asc, s.rank_bottom asc";
		
		$scoring = $this->fetch_one($sql, "mapperScoring");
		
		return $scoring;
	}
	
	/***********************************************************************
	 * Renvoie la liste complete des type score
	 * */
	function getLastRank($idTypeScore) {
		$sql = "select max(rank_bottom) rank_last 
		from `scoring` 
		where id_type_score = $idTypeScore ";
		LibTools::setLog($sql);
		$row = $this->fetch_one($sql);
		if($row) {
			return $row['rank_last'];
		}
		return null;
	}


	
	/***********************************************************************
	 * supprimer la liste de scoring pour le id type score donné
	 */
	function deleteScoringList($id_type_score) {
		if(LibTools::isBlank($id_type_score)) {
			return false;
		}
		$sql = " delete from scoring
		where id_type_score=$id_type_score";
		
		return $this->exec_query($sql, "Delete scoring list OK : $id_type_score");
	}
}

/*######################################################################
        Season
 #######################################################################*/

class SeasonDao extends AbstractDao {
	/***********************************************************************
	 * insert un nouveau scoring
	 */
	function insert($name, $date_start, $date_end) {
		
		$sql = " insert into season
		(`name`, date_start, date_end)
		values
		('$name', '$date_start', '$date_end')";
		
		$this->exec_query($sql, "Insert season OK : $name, $date_start, $date_end");
	}

	/***********************************************************************
	 * Renvoie la liste complete des seasons
	 * */
	function getList() {
		$sql = "select * 
		from season 
		order by date_end desc, date_start asc";
		
		$arr = $this->fetch_map($sql, 'id', 'mapperSeason');
		
		return $arr;
	}

	
	/***********************************************************************
	 * supprimer la liste de scoring pour le id type score donné
	 */
	function deleteSeason($id) {
		if(LibTools::isBlank($id)) {
			return false;
		}
		$sql = " delete from season
		where id=$id";
		
		return $this->exec_query($sql, "Delete season OK : $id");
	}
}

/*######################################################################
        Tournament 
 #######################################################################*/

class TournamentDao extends AbstractDao {
	
	/***********************************************************************
	 * insert un nouveau tournoi
	 */
	function insert($id_game, $group_name, $name, $id_type_score, $date_start, $date_end) {
		if(LibTools::isBlank($group_name)) {
			LibTools::setLog("insert Tournament : group name is empty !!");
			return $g;
		}
		if(LibTools::isBlank($name)) {
			LibTools::setLog("insert Tournament : name is empty !!");
			return $g;
		}
		if(LibTools::isBlank($id_type_score)) {
			LibTools::setLog("insert Tournament : type score is empty !!");
			return $g;
		}
		$mysqli = $this->open();
		$group_name = stripslashes($group_name);
		$name 		= stripslashes($name);
		$date_start = stripslashes($date_start);
		$date_end 	= stripslashes($date_end);
		$group_name = $mysqli->real_escape_string($group_name);
		$name 		= $mysqli->real_escape_string($name);
		$date_start = $mysqli->real_escape_string($date_start);
		$date_end 	= $mysqli->real_escape_string($date_end);
		$this->close($mysqli);
		
		// LibTools::setLog("Insert tournament : id_game=$id_game, group_name=$group_name, name=$name, id_type_score=$id_type_score, date_start=$date_start, date_end=$date_end");
		$sql = " insert into tournament
		(id_game, group_name, `name`, id_type_score, date_start, date_end)
		values
		($id_game, '$group_name', '$name', $id_type_score, '$date_start', '$date_end')";

		return $this->exec_query($sql, "Insert tournament OK : id_game=$id_game, group_name=$group_name, name=$name, id_type_score=$id_type_score, date_start=$date_start, date_end=$date_end");
	}
	
	/***********************************************************************
	 * update le tournoi
	 */
	function save($id, $id_game, $group_name, $name, $id_type_score, $date_start, $date_end) {
		if(LibTools::isBlank($group_name)) {
			LibTools::setLog("update Tournament : group name is empty !!");
			return $g;
		}
		if(LibTools::isBlank($name)) {
			LibTools::setLog("update Tournament : name is empty !!");
			return $g;
		}
		if(LibTools::isBlank($id_type_score)) {
			LibTools::setLog("update Tournament : type score is empty !!");
			return $g;
		}
		$mysqli = $this->open();
		$group_name = stripslashes($group_name);
		$name 		= stripslashes($name);
		$date_start = stripslashes($date_start);
		$date_end 	= stripslashes($date_end);
		$group_name = $mysqli->real_escape_string($group_name);
		$name 		= $mysqli->real_escape_string($name);
		$date_start = $mysqli->real_escape_string($date_start);
		$date_end 	= $mysqli->real_escape_string($date_end);
		$this->close($mysqli);
		
		// LibTools::setLog("update tournament : id=$id, id_game=$id_game, group_name=$group_name, name=$name, id_type_score=$id_type_score, date_start=$date_start, date_end=$date_end");
		$sql = " update tournament
		set id_game=$id_game, group_name='$group_name', name='$name', id_type_score=$id_type_score, date_start='$date_start', date_end='$date_end'
		where id=$id
		";

		return $this->exec_query($sql, "update tournament OK :  id=$id, id_game=$id_game, group_name=$group_name, name=$name, id_type_score=$id_type_score, date_start=$date_start, date_end=$date_end");
	}
	
	
	/***********************************************************************
	 * Renvoie la liste de tout les tournois classé par date décroissante
	 */
	function getList($id_game) {
		$sql = "select *
		from tournament 
		where id_game = $id_game
		order by date_start desc";
		
		LibTools::setLog("Tournament.getList");
		$arr = $this->fetch_map($sql, 'id', "mapperTournament");
		return $arr;
	}
	
	/***********************************************************************
	 * Renvoie le tournois pour l'id donné
	 */
	function get($id) {
		$sql = "select *
		from tournament 
		where id = $id";
		
		$tournament = $this->fetch_one($sql, "mapperTournament");
		
		return $tournament;
	}
	
	/***********************************************************************
	 * supprime le tournament pour l'ID donné
	 */
	function deleteTournament($id) {
		$sql = " delete from tournament
		where id=$id";
		
		return $this->exec_query($sql, "Delete tournament OK : $id");
	}

}

/*######################################################################
        Type Score
 #######################################################################*/

class TypeScoreDao extends AbstractDao {
 
	/***********************************************************************
	 * insert un nouveau type score
	 */
	function insert($type_name) {
		$mysqli = $this->open();
		$type_name = stripslashes($type_name);
		$type_name = $mysqli->real_escape_string($type_name);
		$this->close($mysqli);
		
		$sql = " insert into type_score
		(type_name)
		values
		('$type_name')";
		
		$ret = $this->exec_query($sql, "Insert type_score OK : $type_name");
		if(!$ret) {
			return false;
		}
		$sql = "select max(id) new_id from type_score";
		$row = $this->fetch_one($sql);
		if($row) {
			return $row['new_id'];
		}
		return false;
	}


	/***********************************************************************
	 * Renvoie la liste complete des type score
	 * */
	function getList() {
		$sql = "select ts.* 
		from `type_score` ts
		order by ts.type_name ";
		
		LibTools::setLog("Tournament.getList");
		$arr = $this->fetch_map($sql, 'id');		
		return $arr;
	}
	
	/***********************************************************************
	 * supprime le type score pour l'ID donné
	 */
	function deleteTypeScore($id) {
		$sql = " delete from type_score
		where id=$id";
		
		return $this->exec_query($sql, "Delete type_score OK : $id");
	}

}

/*######################################################################
        User
 #######################################################################*/

class UserDao extends AbstractDao {
	/***********************************************************************
	 * Renvoie un tablea contenant les user vérifier les infos données
	 * $username nom de l'utilisateur
	 * $password le mot de passe associé
	 * */
	function get($username, $password) {
		$mysqli = $this->open();
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = $mysqli->real_escape_string($username);
		$password = $mysqli->real_escape_string($password);
		$this->close($mysqli);

		$sql = "select u.login, u.password, u.right
			from user u
			where u.login   ='$username'
			  and u.password='$password'";

		$arr = $this->fetch_array($sql);
		return $arr;
	}
}


/*######################################################################
        OTHERS
 #######################################################################*/
 
class OtherDao extends AbstractDao {
	public $histoRankingDao;
	public $rankingDao;
	
	/***********************************************************************
	 *  Renvoie les informations de classement des joueurs
	 * */
	function getInfoRanking($id_game) {

		$sql = " select 
			p.id, p.pseudo, p.nom, p.prenom,  
			r.id_game, g.name as game, 
			r.id_char, c.name as `character`, 
			r.points, r.new_points, 
			r.current_rank, r.previous_rank
		 from ranking r 
		 join player p on p.id = r.id_player
		 join game g on g.id = r.id_game
		 join `character` c on c.id = r.id_char 
		 where g.id = $id_game
		 order by r.points desc, p.pseudo asc ";
		
		$rankingList = $this->fetch_array($sql, "mapperPlayerWrapper");
		return $rankingList;
	}

		
	/***********************************************************************
	 *  Renvoie les informations de classement des joueurs
	 * */
	function getInfoRankingFull($id_game, $date_min, $date_max) {
		$mysqli = $this->open();
		$sql="set @current_rank:=0";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "ini @variable OK");
		$sql="set @previous_rank:=0";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "ini @variable OK");
		$sql="set @current_rank_display:=0";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "ini @variable OK");
		$sql="set @previous_rank_display:=0";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "ini @variable OK");
		$sql="set @current_points:=0";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "ini @variable OK");
		$sql="set @previous_points:=0";
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, "ini @variable OK");

		
		$sql = "
select p.id, p.pseudo, p.nom, p.prenom,  
	crs.id_game, g.name as game,
	pg.id_character as id_char, c.name as `character`,
	crs.points, 0 as new_points, prs.previous_points, 
	crs.current_rank, prs.previous_rank
from (
	select (@current_rank:=@current_rank + 1) as current_rank_true, 
			if(@current_points = c2.points, 
				@current_rank_display:=@current_rank_display,
				@current_rank_display:=@current_rank) as current_rank,
			(@current_points := c2.points) as current_points,
			c2.id_game, c2.id_player, 
			c2.points
	from (
		select c.id_game, c.id_player, 
			sum(c.score) as points
		from (
			select ts.id_game, ts.id_player, ts.score
			 from tournament_score ts
			where ts.date_start >= '$date_min'
			  and ts.date_start <= '$date_max'
		) c
		where c.id_game = $id_game
		group by c.id_game, c.id_player 
		order by sum(c.score) desc
	) c2
) crs
left outer join (
	select (@previous_rank:=@previous_rank + 1) as previous_rank_true, 
			if(@previous_points = pr2.previous_points, 
				@previous_rank_display:=@previous_rank_display,
				@previous_rank_display:=@previous_rank) as previous_rank,
			(@previous_points := pr2.previous_points) as previous_points2,
			pr2.id_game, pr2.id_player, 
			pr2.previous_points
	from (
		select  pr.id_game, pr.id_player, sum(pr.score) as previous_points
		from (
			select ts.id_game, ts.id_player, ts.score
			from tournament_score ts
			where ts.date_start < (
			select 
				max(tt.date_start) last_date
			 from tournament tt
			where tt.date_start >= '$date_min'
			  and tt.date_start <= '$date_max'
			  and tt.id_game = ts.id_game
			)
		) pr
		where pr.id_game = $id_game
		group by pr.id_game, pr.id_player 
		order by sum(pr.score) desc
	) pr2
) prs
   on prs.id_game 	= crs.id_game
  and prs.id_player = crs.id_player
 join player p
   on p.id 			= crs.id_player
  and p.status is null
 join game g
   on g.id 			= crs.id_game
 left outer join player_game pg
   on pg.id_player	= crs.id_player
  and pg.id_game	= crs.id_game
 left outer join `character` c
   on c.id 			= pg.id_character
where crs.id_game = $id_game
order by crs.points desc, crs.current_rank asc
		 ";
		// LibTools::setLog($sql);
		$rankingList = $this->fetch_array($sql, "mapperPlayerWrapper",$mysqli);
		$this->close($mysqli);
		return $rankingList;
	}

	/***********************************************************************
	 * Met à jour le rank et le previous rank à partir du classement 
	 * */
	function updateRank($id_game) {
		$mysqli = open();

		$playerList = $_POST["player"];
		foreach ($playerList as $id => $player) {
					
			if(!$this->$histoRankingDao->doInsertHistoRanking($mysqli, $id, $id_game, $player['id_char'], $player['points'], $player['previous_rank'])) {
				continue;
			}
			if(!$this->$rankingDao->doUpdatePreviousRank($mysqli, $id, $id_game, $player['rank'])) {
				continue;
			}		
		}
			
		close($mysqli);
		return true;
	}

}


/*######################################################################
        Dao Bundle 
 #######################################################################*/

class Dao {
	public $characterDao;
	public $gameDao;
	public $histoRankingDao;
	public $paramDao;
	public $participantDao;
	public $playerDao;
	public $playerGameDao;
	public $rankingDao;	
	public $scoringDao;
	public $seasonDao;
	public $typeScoreDao;
	public $tournamentDao;
	public $userDao;
	public $otherDao;	
	
	function __construct() {
		$this->characterDao 		= new CharacterDao();
		$this->gameDao 				= new GameDao();
		$this->histoRankingDao		= new HistoRankingDao();
		$this->paramDao				= new ParamDao();
		$this->participantDao		= new ParticipantDao();
		$this->playerDao 			= new PlayerDao();
		$this->playerGameDao 		= new PlayerGameDao();
		$this->rankingDao			= new RankingDao();
		$this->scoringDao			= new ScoringDao();
		$this->seasonDao			= new SeasonDao();
		$this->typeScoreDao			= new TypeScoreDao();
		$this->tournamentDao		= new TournamentDao();
		$this->userDao				= new UserDao();
		
		$this->otherDao				= new OtherDao();
		$this->otherDao->histoRankingDao 	= $this->histoRankingDao;
		$this->otherDao->rankingDao 		= $this->rankingDao;
	}
}


?>

