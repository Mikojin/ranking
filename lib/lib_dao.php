<?php 
?>
<?php 
/*****************************************************************************
 * lib_dao.php
 * Contient les fonction utilitaires pour accèder à la base de donnée
 *****************************************************************************/

require_once("./lib/security.php");
require_once("./lib/lib_tools.php");

class AbstractDao {
	/* __________________________________________________________________ */


	/***********************************************************************
	 * Initialise la connexion à la base de donnée de Ranking
	 * */
	function open() {
		$mysqli = $this->doOpenDataBase($GLOBALS['host'],$GLOBALS['login'],$GLOBALS['password'], $GLOBALS['name']);
		return $mysqli;
	}

	/***********************************************************************
	 * Ouvre une connexion sur une base de donnée
	 * $host l'adresse de la base de donnée
	 * $user le nom de l'utilisateur
	 * $password le password
	 * $database le nom de la base
	 * */
	function doOpenDataBase($host, $user, $password, $database) {
		$mysqli = new mysqli($host, $user, $password, $database);
		if($mysqli->connect_errno) {
			  echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		// mysql_select_db("SFV_DB", $conn);
		return $mysqli;
	}

	/***********************************************************************
	 * Ferme la connexion à la base de donnée
	 * */
	function close($mysqli) {
		$mysqli->close();
	}


	/***********************************************************************
	 * Renvoie le message d'erreur renvoyé par la base
	 * $mysqli la connexion ayant exécuté la requete
	 * */
	function sqlError($mysqli) {
		return 'SQL Query Failed (' . $mysqli->errno . ") " . $mysqli->error;
	}

	/***********************************************************************
	 * Controle le résultat et affiche un message en fonction de ce dernier
	 * $result le résultat d'une requete ou un booleen
	 * $mysqli la connexion ayant exécuté la requete
	 * $msg le message à afficher en cas de resultat OK
	 * affiche l'erreur stoqué dans $mysqli en cas de KO
	 * */
	function check($result, $mysqli, $msg) {
		if($result) {
			LibTools::setLog($msg);
			return true;
		} else {
			LibTools::setLog($this->sqlError($mysqli));
			return false;
		}
	}

	/***********************************************************************
	 * execute la requete update, insert, delette donnée (pas de résultat)
	 * $sql la requete à exécuter
	 * $msg le message à afficher en cas de résultat OK
	 * returns true si le traitement s'est passé correctement.
	 * */
	function exec_query($sql, $msg) {
		$mysqli = $this->open();
		$result = $mysqli->query($sql);
		$out = $this->check($result, $mysqli, $msg);
		$this->close($mysqli);
		return $out;
	}

	/***********************************************************************
	 * Renvoie le resultat de la requete donnée dans une liste de map
	 * $sql la requete à executer
	 * return array(map[column])
	 * */
	function fetch_array($sql, callable $mapper=null, $mysqli=null) {	
		$create = false;
		if(!isset($mysqli)) {
			$mysqli = $this->open();
			$create = true;
		} else {
			LibTools::setLog("mysqli already created");
		}

		$mysqli->real_query($sql);
		$result = $mysqli->use_result();

		if(!$result) {
			LibTools::setLog($this->sqlError($mysqli));
			return false;
		}

		$arr = array();
		if($mapper) {
			while( $row = $result->fetch_assoc()){
				// LibTools::setLog(LibTools::mapToString($row));
				$obj = $mapper($row);
				$arr[] = $obj;
			}
		} else {
			while( $row = $result->fetch_assoc()){
				$arr[] = $row;
			}
		}
		if($create) {
			$this->close($mysqli);
		}
		LibTools::setLog("fetch_array count = ".count($arr));
		return $arr;
	}

	/***********************************************************************
	 * Renvoie le résultat de la requete donnée dans une map de map.
	 * $sql la requete select à executer
	 * $key le nom de la colonne servant de clé principale
	 * return map[$key]=>map[column]=>value
	 * */
	function fetch_map($sql, $key, callable $mapper=null) {	
		$mysqli = $this->open();

		$mysqli->real_query($sql);
		$result = $mysqli->use_result();

		$arr = array();
		if(!$result) {
			LibTools::setLog($this->sqlError($mysqli));
			return false;
		}
		
		if($mapper) {
			while( $row = $result->fetch_assoc()){
				$obj = $mapper($row);
				$arr[$row[$key]] = $obj;
			}
		} else {
			while( $row = $result->fetch_assoc()){
				$arr[$row[$key]] = $row;
			}
		}
		$this->close($mysqli);
		LibTools::setLog("fetch_map count = ".count($arr));
		return $arr;
	}


	/***********************************************************************
	 * Renvoie le resultat de la requete donnée dans une map
	 * $sql la requete à executer
	 * return map[column], 
	 * prend le premier résultat de la liste si plusieur objet sont renvoyés
	 * */
	function fetch_one($sql, callable $mapper=null) {	
		$mysqli = $this->open();
		
		$result = $mysqli->query($sql);
		if(!$result) {
			LibTools::setLog($this->sqlError($mysqli));
			return false;
		}
		if($result->num_rows == 0) {
			return false;
		}
		
		$row = $result->fetch_assoc();

		if($mapper) {
			$obj = $mapper($row);
		} else {
			$obj = $row;
		}
		$this->close($mysqli);
		return $obj;
	}

}


?>
