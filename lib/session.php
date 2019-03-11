<?php 
?>
<?php 
/*****************************************************************************
 * session.php
 * Classe manipulant le context utilisateur d'une session.
 *****************************************************************************/
require_once "./lib/lib_tools.php";
require_once "./lib/dao.php";

class Ss {
	
	static function init($id_game=1, $page='ranking') {
		LibTools::init();
		$sess = Ss::get();
		if($sess->init) {
			// si deja init, on ne fait rien
			/*
			if($sess->game->id != $id_game) {
				// changement de jeu, on recharge
				Ss::loadGameData($id_game);
			}
			*/
			return;
		}
		
		LibTools::setLog("Ss.init IN");
		$sess->init = true;
		$sess->user = new User();
		$sess->dao = new Dao();
		
		$sess->page = $page;
		
		Ss::loadAllGame();
		Ss::loadGameData($id_game);
		
		LibTools::setLog("Ss.init OUT");
	}
	
	static function get() {
		return LibTools::getSession();
	}
	
	static function set($sess) {
		LibTools::setSession($sess);
	}
	

	/***********************************************************************
	 * charge les données relative à un jeu
	 * */
	static function loadAllGame() {
		$sess = Ss::get();
		$sess->gameMap 			= $sess->dao->gameDao->getList();
		
		foreach( $sess->gameMap as $id_game => $game) {
			$game->cssFile 			= "./css/game/".($game->code).".css";
			$game->characterMap 	= $sess->dao->characterDao->getList($id_game);
			
			$id_char_unknown 		= $game->id_char_unknown;
			if(isset($id_char_unknown) && array_key_exists($id_char_unknown, $game->characterMap)) {
				$game->char_unknown = $game->characterMap[$id_char_unknown];
			}
			LibTools::setLog("game code=$game->code ; cssFile = ".$game->cssFile." ; id_char_unknown = $id_char_unknown");

		}

	}
	
	/***********************************************************************
	 * charge les données relative à un jeu
	 * */
	static function loadGameData($id_game) {
		LibTools::setLog("Load game = $id_game");
		$sess 					= Ss::get();
		
		$sess->game 			= $sess->gameMap[$id_game];
		
		$gameCode 				= $sess->game->code;
		LibTools::setLog("gameCode = $gameCode");

		$charPath				= $sess->dao->paramDao->load("PATH","character");
		$sess->characterPath 	= $charPath."/".$gameCode;
		LibTools::setLog("characterPath = ".$sess->characterPath);

	}
	
	/***********************************************************************
	 * defini la page affichée dans la session
	 * */
	static function setPage($page) {
		LibTools::setLog("setPage = $page");
		$sess = Ss::get();
		$sess->page = $page;
	}

}





?>