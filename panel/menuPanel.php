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

class MenuPanel implements IPanel {
		
	public $dao;
	
	function __construct() {
		$this->dao = new Dao();
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
	//#########################################################################

	/***********************************************************************
	 * traitement des action du menu
	 * */
	function treatMenuAction() {
		$action = $_POST['action'];
		switch($action) {
			case 'pageRanking' :
				LibTools::set('page', 'ranking');
				return true;
			case 'pagePlayerList' :
				LibTools::set('page', 'playerList');
				return true;
			case 'pageTournement' :
				LibTools::set('page', 'tournement');
				return true;
			case 'pageScoring' :
				LibTools::set('page', 'scoring');
				return true;
		}
		return false;
	}
	
	/***********************************************************************
	 * vérifie la validite du login/password et met à jour les droits de l'utilisateur 
	 * */
	function checkLogin($username, $password) {
		
		$arr = $this->dao->userDao->get($username, $password);
		
		$nbRows = count($arr);
		if($nbRows==1) {
			$row = $arr[0];
			LibTools::set('user_right', $row['right']);
		} else {
			LibTools::set('user_right', '');
			LibTools::setLog('user error');
		}
	}

	
	/***********************************************************************
	 * si admin, imprime le panel menu, sinon de login.
	 * */
	function printPanel() {
		?>
	<div class="menuPanel mainMenuPanel" id="menuPanel" >
		<div class="buttonMenuLogin noselect" onclick="javascript:toggleDisplay('menu');">&gt;</div>
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
	?>
		<div id="menu" class="divTable divTabMenu hiddenDiv">
			<div class="divTableBody">
			<div class="divTableRow">
			<div class="divTableCell divCellMenu ">
				<input name="Ranking" 		id="ranking" 	type="button" value="Ranking" 
					onclick="setAction('pageRanking');"/>
			</div>
			<div class="divTableCell divCellMenu ">
				<input name="PlayerList" 	id="playerList" type="button" value="Player List" 
					onclick="setAction('pagePlayerList');"/>
			</div>
			<div class="divTableCell divCellMenu ">
				<input name="Tournement" 	id="tournement" type="button" value="Tournement" 
					onclick="setAction('pageTournement');"/>
			</div>
			<div class="divTableCell divCellMenu ">
				<input name="Tournement" 	id="scoring" type="button" value="Scoring" 
					onclick="setAction('pageScoring');"/>
			</div>
			</div>
			</div>
		</div>
	<?php 
	}

}
