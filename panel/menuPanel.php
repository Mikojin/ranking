<?php 
?>
<?php 
/*****************************************************************************
 * menuPanel.php
 * Panel menu
 *****************************************************************************/

require_once "./lib/lib_tools.php";
require_once "./lib/dao.php";

require_once "./panel/iPanel.php";



/**
 * callback d'affichage du libellé d'un jeu
 */
function labelGame($s) {
	return "&nbsp;$s->name&nbsp;";
}


class MenuPanel implements IPanel {
		
	public $combobox;

	function __construct() {
		$this->combobox = new Combobox();
	}
	
	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		return $g;
	}
	
	public function treatAction($g){
		$this->treatMenuAction();
		return $g;
	}
	
	public function printHeader($g){
		return $g;
	}

	public function printBody($g){
		$this->printPanel();
		return $g;
	}
	
	public function printFooter($g){
		return $g;
	}
	
	//#########################################################################
	// Static
	//#########################################################################

				
	/***********************************************************************
	 * renvoie vers le profil du joueur
	 * */
	static function doEditPlayer() {
		$id_player = $_POST['select_id_player'];
		if(LibTools::isBlank($id_player)) {
			LibTools::setLog("Edit player KO : id_player is blank");
			return $g;
		}
		LibTools::setLog("Edit Player OK : id_player=$id_player");
		Ss::setPage('player');
		LibTools::set("id_player", $id_player);
		return $g;
	}
	
	/***********************************************************************
	 * Edit le Tournament selectionne
	 * */
	static function doEditTournament($g) {
		$idTournament = $_POST['selectIdTournament'];
		if(LibTools::isBlank($idTournament)) {
			LibTools::setLog("Edit Tournament KO : idTournament is blank");
			return $g;
		}
		LibTools::setLog("Edit Tournament OK : idTournament=$idTournament");
		Ss::setPage('tournament');
		LibTools::set("idTournament", $idTournament);
		//$r = $this->dao->tournamentDao->deleteTournament($idTournament);
		return $g;
	}
	
	//#########################################################################
	//#########################################################################

	/***********************************************************************
	 * traitement des action du menu
	 * */
	function treatMenuAction() {
		$action = $_POST['action'];
		LibTools::setLog("Menu Action : $action");
		switch($action) {
			case 'pageRanking' :
				Ss::setPage('ranking');
				return true;
			case 'pagePlayerList' :
				Ss::setPage('playerList');
				return true;
			case 'pageTournamentList' :
				Ss::setPage('tournamentList');
				return true;
			case 'pageScoring' :
				Ss::setPage('scoring');
				return true;
			case 'changeGame' : 
				Ss::loadGameData($_POST['idGame']);
				return true;
		}
		return false;
	}
	
	/***********************************************************************
	 * si admin, imprime le panel menu, sinon de login.
	 * */
	function printPanel() {
		?>
	<div class="menuPanel mainMenuPanel" id="menuPanel" >
		<div class="buttonMenuLogin noselect clickable" onclick="javascript:toggleDisplay('menu');">&gt;</div>
		<?php
			$this->printMenuForm();
		?>
		</div>
		<?php 
	}

	/***********************************************************************
	 * imprime le panel du menu
	 * */
	function printMenuForm() {
		$gameList = Ss::get()->gameMap;
	?>
		<div id="menu" class="divTable divTabMenu hiddenDiv">
			<div class="divTableBody">
			<div class="divTableRow">
			
			<div class="divTableCell divCellMenu ">
				<input name="ranking" 		id="ranking" 	type="button" value="Ranking" 
					onclick="setAction('pageRanking');"/>
			</div>
			<div class="divTableCell divCellMenu ">
				<input name="playerList" 	id="playerList" type="button" value="Player List" 
					onclick="setAction('pagePlayerList');"/>
			</div>
			<div class="divTableCell divCellMenu ">
				<input name="tournament" 	id="tournament" type="button" value="Tournament" 
					onclick="setAction('pageTournamentList');"/>
			</div>
			<div class="divTableCell divCellMenu ">
				<input name="scoring" 	id="scoring" type="button" value="Scoring" 
					onclick="setAction('pageScoring');"/>
			</div>
			
			<div class="divTableCell divCellMenu ">
			<?php	
				$this->combobox->emptyLine		 	= false;
				$this->combobox->id_select			= Ss::get()->game->id;
				$this->combobox->id_elem 			= "idGame";
				$this->combobox->arr 				= $gameList;
				$this->combobox->libelleCallback	= 'labelGame';
				$this->combobox->title				= 'Select the game';
				$this->combobox->cssClass 			= 'game_select';
				$this->combobox->onchange			= "setAction('changeGame');";
				$this->combobox->doPrint();
			?>
			</div>
			
			</div>
			</div>
		</div>
	<?php 
	}

}
