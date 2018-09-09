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
		$sql = "select c.* 
		from `character` c
		where c.id_game = $id_game
		order by c.name ";
		
		$arr = $this->fetch_map($sql, 'id');
		
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
	function insert($id_player, $id_tournement) {
		
		$sql = " insert into tournement
		(id_player, id_tournement)
		values
		($id_player, $id_tournement)";
		

		$this->exec_query($result, "Insert Participant OK : $id");
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
	 * insert un nouveau joueur dans le ranking
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
        Tournement 
 #######################################################################*/

class TournementDao extends AbstractDao {
	/***********************************************************************
	 * insert un nouveau tournoi
	 */
	function insert($id_game, $group_name, $name, $id_type_score, $date_start, $date_end) {
		$sql = " insert into tournement
		(id_game, group_name, name, id_type_score, date_start, date_end)
		values
		($id_game, $group_name, $name, $id_type_score, $date_start, $date_end)";

		$this->exec_query($sql, "Insert tournement OK : $id");
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
	public $histoRankingDao;
	public $paramDao;
	public $participantDao;
	public $playerDao;
	public $rankingDao;	
	public $scoringDao;
	public $typeScoreDao;
	public $tournementDao;
	public $userDao;
	public $otherDao;	
	
	function __construct() {
		$this->characterDao 		= new CharacterDao();
		$this->histoRankingDao		= new HistoRankingDao();
		$this->paramDao				= new ParamDao();
		$this->participantDao		= new ParticipantDao();
		$this->playerDao 			= new PlayerDao();
		$this->rankingDao			= new RankingDao();
		$this->scoringDao			= new ScoringDao();
		$this->typeScoreDao			= new TypeScoreDao();
		$this->tournementDao		= new TournementDao();
		$this->userDao				= new UserDao();
		
		$this->otherDao				= new OtherDao();
		$this->otherDao->histoRankingDao 	= $this->histoRankingDao;
		$this->otherDao->rankingDao 		= $this->rankingDao;
	}
}


?>

