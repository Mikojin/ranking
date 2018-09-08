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
		$mysqli = $this->doOpenDataBase($database_host,$database_login,$database_password, $database_name);
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
	 * Ouvre une connexion sur la base local de test
	 * */
	function openDataBaseLocal() {
		// local Tahar
		//return doOpenDataBase("localhost","root","123456a*", "SFV_DB");
		// local Sam
		return $this->doOpenDataBase("localhost","root","", "ranking");
	}

	/***********************************************************************
	 * Ouvre une connexion sur la base de prod
	 * */
	function openDataBaseProd() {
		return $this->doOpenDataBase("tesatnsimbtesadb.mysql.db","tesatnsimbtesadb", "F3OgJ6025uf9LrR", "tesatnsimbtesadb");
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
	function fetch_array($sql, callable $mapper=null) {	
		$mysqli = $this->open();

		$mysqli->real_query($sql);
		$result = $mysqli->use_result();

		if(!$result) {
			LibTools::setLog($this->sqlError($mysqli));
			return false;
		}

		$arr = array();
		if($mapper) {
			while( $row = $result->fetch_assoc()){
				$obj = $mapper($row);
				$arr[] = $obj;
			}
		} else {
			while( $row = $result->fetch_assoc()){
				$arr[] = $row;
			}
		}
		$this->close($mysqli);
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
				/*
				$data = array();
				foreach($row as $k => $value) {
					$data[$k] = $value;
				}
				*/
				$arr[$row[$key]] = $row;
			}
		}
		
		$this->close($mysqli);
		return $arr;
	}


	/***********************************************************************
	 * Renvoie le resultat de la requete donnée dans une map
	 * $sql la requete à executer
	 * return map[column], 
	 * prend le premier résultat de la liste si plusieur objet sont renvoyés
	 * */
	function fetch_one($sql) {	
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

		$this->close($mysqli);
		return $row;
	}

}


?>
