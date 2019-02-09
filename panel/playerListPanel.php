<?php 
?>
<?php 
/*****************************************************************************
 * playerListPanel.php
 * Page de la liste des joueurs
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";
require_once "./panel/adminPanel.php";


class PlayerListPanel extends ListPanel {
	public $adminPanel;
	
	function __construct($adminPanel = null) {
		parent::__construct();
		if($adminPanel) {
			$this->adminPanel = $adminPanel;
		} else {
			$this->adminPanel = new AdminPanel();
		}
	}

	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		$g = parent::init($g);
		$g = $this->initPanelPlayerList($g);
		return $g;
	}
	
	public function treatAction($g){
		$action = $_POST['action'];
		if(!isset($action)) {
			return false;
		}
		
		switch($action) {
			case "savePlayer" :
				$this->doSavePlayer();
				break;
			case "editPlayer" :
				// $g = $this->doEditPlayer($g);
				$g = MenuPanel::doEditPlayer($g);
				break;
			case "toggleStatusPlayer" :
				$this->doToggleStatusPlayer();
				break;
			case "deletePlayer" :
				$this->doDeletePlayer();
				break;
		}
		return $g;
	}
	
	public function printHeader($g){
		$g = parent::printHeader($g);
		$g = $this->printJS($g);
		return $g;
	}
	
	//#########################################################################
	//#########################################################################

	/***********************************************************************
	 * Initialisation du panel PlayerList
	 * */
	function initPanelPlayerList($g) {
		if(LibTools::isAdmin()) {
			$g['playerList'] = Ss::get()->dao->playerDao->getListAll();
		} else {
			$g['playerList'] = Ss::get()->dao->playerDao->getList();
		}
		return $g;
	}
	
	/***********************************************************************
	 * imprime les styles lié au ranking
	 * */
	function printJS($g) {
		// $jsonPlayerList = json_encode($g['playerList']);
		
		//var players = $jsonPlayerList ;
		?>
		<script>
			function toggleDisplayEditPlayer() {
				toggleDisplay('editPlayer', 'hiddenDivBloc'); 
			}
			function displayPlayerEdit(id) {
				var pe 	= document.getElementById("editPlayer");
				var e 	= document.getElementById("div_player_"+id);
				var r 	= e.getBoundingClientRect();
				pe.style.top =  window.pageYOffset + r.top;
				pe.style.left = r.left;
				copyInfoPlayer(id);
				toggleDisplayEditPlayer();
			}
			function copyInfoPlayer(id) {
				var eid 	= document.getElementById("savePlayer[id]");
				var epseudo = document.getElementById("savePlayer[pseudo]");
				var eprenom = document.getElementById("savePlayer[prenom]");
				var enom 	= document.getElementById("savePlayer[nom]");
				var email 	= document.getElementById("savePlayer[mail]");
				var etel 	= document.getElementById("savePlayer[tel]");
				
				var ipseudo = document.getElementById("player_pseudo_"+id);
				var iprenom = document.getElementById("player_prenom_"+id);
				var inom 	= document.getElementById("player_nom_"+id);
				var imail 	= document.getElementById("player_mail_"+id);
				var itel 	= document.getElementById("player_tel_"+id);
				eid.value		= id;
				epseudo.value 	= ipseudo.innerHTML;
				eprenom.value 	= iprenom.innerHTML;
				enom.value 		= inom.innerHTML;
				email.value 	= imail.innerHTML;
				etel.value 		= itel.innerHTML;
			}
		</script>
		<?php
		
		return $g;
	}
	
	/***********************************************************************
	 * sauvegarde les informations du joueur
	 * */
	function doSavePlayer() {
		if(!LibTools::isAdmin()) {
			return;
		}
		$savePlayer = $_POST['savePlayer'];
		$id 	= $savePlayer["id"];
		$pseudo = $savePlayer["pseudo"];
		$prenom = $savePlayer["prenom"];
		$nom 	= $savePlayer["nom"];
		$mail	= $savePlayer["mail"];
		$tel	= $savePlayer["tel"];
		
		Ss::get()->dao->playerDao->save($id, $pseudo, $prenom, $nom, $mail, $tel);
	}
		
	/***********************************************************************
	 * masque le joueur
	 * */
	function doToggleStatusPlayer() {
		if(!LibTools::isAdmin()) {
			return;
		}
		$savePlayer = $_POST['savePlayer'];
		$id 	= $savePlayer["id"];
		
		Ss::get()->dao->playerDao->toggleStatus($id);
	}
	
	/***********************************************************************
	 * masque le joueur
	 * */
	function doDeletePlayer() {
		if(!LibTools::isAdmin()) {
			return;
		}
		$savePlayer = $_POST['savePlayer'];
		$id 	= $savePlayer["id"];
		
		Ss::get()->dao->playerDao->deletePlayer($id);
	}
	
	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste de classement des joueurs
		return $g['playerList'];
	}

	/***********************************************************************
	 * affiche le block d'un joueur
	 * */
	function printElement($g, $player, $i) {
		if(LibTools::isAdmin()) {
			$this->printElementAdmin($g, $player);
		} else {
			$this->printElementPublic($g, $player);
		}
	}

	
	/***********************************************************************
	 * affiche le block d'un joueur PUBLIC
	 * */
	function printElementPublic($g, $player) {
		$id = $player->id;
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell edit" 		title="Go to Player page"	>
					<input type="button" 
						onclick="setVar('select_id_player', <?php echo $id; ?>);setAction('editPlayer')" 
						value="See" /></div>
				<div class="divTableCell pseudo" 	title="Pseudo"	><?php echo $player->pseudo;?></div>
				<div class="divTableCell prenom" 	title="Prenom"	><?php echo $player->prenom; ?></div>
				<div class="divTableCell nom" 		title="Nom"		><?php echo $player->nom; ?></div>
			</div>
			<div class="divTableRow spaceRow"><div class="divTableCell">&nbsp;</div></div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le block d'un joueur ADMIN
	 * */
	function printElementAdmin($g, $player) {
		$id = $player->id;
	?>	
			<div id="<?php echo "div_player_$id";?>" class="divTableRow characterRow" >
				<div class="divTableCell edit" 		title="Modify this player"	>
					<input type="button" 
						onclick="displayPlayerEdit(<?php echo $id;?>)"
						value="<?php echo $id;?>" /></div>
				<div class="divTableCell clickable <?php echo $player->status == 'H'?'pseudo hidden':'pseudo';?>"
					onclick="setVar('select_id_player', <?php echo $id; ?>);setAction('editPlayer')" 	
					title="Pseudo - Go to Player profile"	id="<?php echo "player_pseudo_$id";?>"	><?php echo $player->pseudo;?></div>
				<div class="divTableCell prenom" 	title="Prenom"	id="<?php echo "player_prenom_$id";?>"	><?php echo $player->prenom; ?></div>
				<div class="divTableCell nom" 		title="Nom"		id="<?php echo "player_nom_$id";?>"		><?php echo $player->nom; ?></div>
				<div class="divTableCell email"		title="E-mail"	id="<?php echo "player_mail_$id";?>"	><?php echo $player->email;?></div>
				<div class="divTableCell tel" 		title="Tel"		id="<?php echo "player_tel_$id";?>"		><?php echo $player->telephone;?></div>
			</div>
			<div class="divTableRow spaceRow"><div class="divTableCell">&nbsp;</div></div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le block de modification d'un joueur
	 * */
	function printEditPlayer($g) {
	?>	
		<div id="editPlayer" class="hiddenDivBloc" >
			<div class="group">
				<div class="row">
					<input type="hidden"	id="savePlayer[id]"		name="savePlayer[id]"		value=""/>
					<input type="text" 		id="savePlayer[pseudo]"	name="savePlayer[pseudo]"	placeholder="pseudo"/>
					<input type="text" 		id="savePlayer[prenom]"	name="savePlayer[prenom]"	placeholder="prenom"/>
					<input type="text" 		id="savePlayer[nom]"	name="savePlayer[nom]"		placeholder="nom"/>
					<input type="text" 		id="savePlayer[mail]"	name="savePlayer[mail]"		placeholder="email"/>
					<input type="text" 		id="savePlayer[tel]"	name="savePlayer[tel]"		placeholder="telephone"/>
				</div>
				<div class="row">
					<input type="button" class="button"
						title="Cancel change"
						value="Cancel" onclick="toggleDisplayEditPlayer();;">
					<input type="button" class="button"
						title="update player informations"
						value="Save" onclick="setActionTest('savePlayer')">
					<input type="button" class="button"
						title="Hide or Show the player from the public list"
						value="Toggle Status" onclick="setActionTest('toggleStatusPlayer')">
					<input type="button" class="delete"
						title="delete player from the list : cannot be undone !!!"
						value="X" onclick="setActionTest('deletePlayer')">
				</div>
			</div>
		</div>
	<?php
	}
	
	/***********************************************************************
	 * affiche le header du block Ranking
	 * */
	function printPageHeader($g) {
		$g = parent::printPageHeader($g);
		?>
			<input type="hidden" id="select_id_player" name="select_id_player" value=""/>
			<div class="divTitle playerListTitle">&nbsp;</div>		
			<div class="playerList ">
		<?php
			if(LibTools::isAdmin()) {
		?>
			<div class="spaceRow">&nbsp;</div>
			<div class="spaceRow">&nbsp;</div>
			<div class="divAdminMenu divAdminTab">
		<?php
			$this->adminPanel->printInsertNewPlayer($g);
		?>
			</div>
		<?php
			}
		return $g;
	}

	/***********************************************************************
	 * affiche le footer du block ranking
	 * */
	function printPageFooter($g) {
		$this->printEditPlayer($g);
		?>	
			</div><!-- playerList -->
		<?php
		$g = parent::printPageFooter($g);
		return $g;
	}


}


?>

