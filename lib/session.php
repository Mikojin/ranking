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
			// il ne faut initialiser qu'une fois par session
			return;
		}
		
		LibTools::setLog("Ss.init IN");
		$sess->init = true;
		$sess->user = new User();
		$sess->dao = new Dao();
		
		$sess->page = $page;

		$sess->gameMap 			= $sess->dao->gameDao->getList();

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
	static function loadGameData($id_game) {
		$sess 					= Ss::get();
		
		$sess->game 			= $sess->gameMap[$id_game];
		
		$sess->characterMap 	= $sess->dao->characterDao->getList($id_game);

		$id_char_unknown		= $sess->dao->paramDao->load("CHAR_UNKNOWN", $sess->game->code);
		$sess->char_unknown		= $sess->characterMap[$id_char_unknown];
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