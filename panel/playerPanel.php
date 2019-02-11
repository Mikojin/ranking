<?php 
?>
<?php 
/*****************************************************************************
 * playerListPanel.php
 * Page de la liste des joueurs
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";


/**
 * callback d'affichage du libellé d'une saison
 */
function libelleCharacter($s) {
	return "&nbsp;$s->name&nbsp;";
}


class PlayerPanel extends ListPanel {
	public $g;
	public $id;
	public $player;
	
	function __construct($id_player) {
		parent::__construct();
		$this->id = $id_player;
	}

	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		$this->g = $g;
		$g = parent::init($g);
		$g = $this->doInit($g);
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
				$this->doSavePlayerGame();
				$this->doAddGamePlayed($g);
				break;
			case "toggleStatusPlayer" :
				$this->doToggleStatusPlayer();
				break;
			case "deletePlayer" :
				$this->doDeletePlayer();
				Ss::setPage('playerList');
				break;
			case "deleteGamePlayed" :
				$this->doDeleteGamePlayed();
				break;
			case "editTournament" :
				$g = MenuPanel::doEditTournament($g);
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
	function doInit($g) {
		$sess 						= Ss::get();
		$this->player 				= $sess->dao->playerDao->get($this->id);
		$g['player'] 				= $this->player;
		$g['gamePlayed']			= $sess->dao->playerGameDao->getList($this->id);
		$g['participationList']		= $sess->dao->playerDao->getParticipationList($this->id);
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
		$pseudo = $savePlayer["pseudo"];
		$prenom = $savePlayer["prenom"];
		$nom 	= $savePlayer["nom"];
		$mail	= $savePlayer["mail"];
		$tel	= $savePlayer["tel"];
		
		Ss::get()->dao->playerDao->save($this->id, $pseudo, $prenom, $nom, $mail, $tel);
	}
	
	/***********************************************************************
	 * sauvegarde les informations du joueur
	 * */
	function doSavePlayerGame() {
		if(!LibTools::isAdmin()) {
			return;
		}
		$gamePlayed = $_POST['gamePlayed'];
		if(!isset($gamePlayed)) {
			return;
		}
		foreach($gamePlayed as $id_game => $id_char) {
			Ss::get()->dao->playerGameDao->save($this->id, $id_game, $id_char);
		}
		
	}	
	
	/***********************************************************************
	 * masque le joueur
	 * */
	function doToggleStatusPlayer() {
		if(!LibTools::isAdmin()) {
			return;
		}
		Ss::get()->dao->playerDao->toggleStatus($this->id);
	}
	
	/***********************************************************************
	 * supprime le joueur
	 * */
	function doDeletePlayer() {
		if(!LibTools::isAdmin()) {
			return;
		}
		Ss::get()->dao->playerDao->deletePlayer($this->id);
	}
	
	/***********************************************************************
	 * supprime le jeu selectionné
	 * */
	function doDeleteGamePlayed() {
		if(!LibTools::isAdmin()) {
			return;
		}
		$id_game = $_POST['selectIdGame'];
		Ss::get()->dao->playerGameDao->remove($this->id, $id_game);
	}
	
	/***********************************************************************
	 * Ajoute les jeux joués
	 * */
	function doAddGamePlayed($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$new_game 	= $_POST['new_game'];
		if(!isset($new_game) || count($new_game)==0) {
			return;
		}
		$s = Ss::get();
		$gameList 	= $s->gameMap;
		
		foreach( $gameList as $id_game => $game) {
			LibTools::setLog("game $game->name : id=$game->id ; code=$game->code ");
		}
		
		foreach( $new_game as $id_game) {
			$game 		= $gameList[$id_game];
			$gameCode 	= $game->code;
			$id_char	= $game->id_char_unknown;
			LibTools::setLog("insert game : id_game=$id_game ; game_code=$gameCode ; id_char=$id_char");
			Ss::get()->dao->playerGameDao->insert($this->id, $id_game, $id_char);
		}
		
	}
	
	
	
	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste de classement des joueurs
		return $g['participationList'];
		// return array();
	}

	/***********************************************************************
	 * affiche le block d'un joueur
	 * */
	function printElement($g, $e, $i) {
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue game" 	title="Game"><?php echo Ss::get()->gameMap[$e['id_game']]->code;?></div>
				<div class="divTableCell rowValue" 			title="Tournament Group"><?php echo $e['tournament_group_name'];?></div>
				<div class="divTableCell rowValue clickable" 			title="Tournament Name - Go to this tournament"	
					onclick="setVar('selectIdTournament', <?php echo $e['id_tournament']; ?>);setAction('editTournament')"><?php echo $e['tournament_name']; ?></div>
				<div class="divTableCell rowValue date"		title="Starting Date"	><?php echo $e['date_start']; ?></div>
				<div class="divTableCell rowValue rank" 	title="Ranking"			><?php echo $e['ranking']; ?></div>
				<div class="divTableCell rowValue points" 	title="Points"			><?php echo $e['score']; ?></div>
			</div>
	<?php
	}

		
	/***********************************************************************
	 * affiche le block de modification d'un joueur
	 * */
	function printPageHeaderPublic($g) {
		$player = $this->player;
	?>	
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
			<div class="row title">
				<div class="divTableCell pseudo" 	title="Pseudo"	><?php echo $player->pseudo;?></div>
				<div class="divTableCell prenom" 	title="Prenom"	><?php echo $player->prenom; ?></div>
				<div class="divTableCell nom" 		title="Nom"		><?php echo $player->nom; ?></div>
			</div>
		</div>
		</div>
	<?php
	}
	
	/***********************************************************************
	 * affiche le block de modification d'un joueur
	 * */
	function printPageHeaderAdmin($g) {
		$player = $this->player;
	?>	
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
			<div class="row">
				<input type="text" name="savePlayer[pseudo]"	placeholder="pseudo"	value="<?php echo $player->pseudo ;?>"/>
				<input type="text" name="savePlayer[prenom]"	placeholder="prenom"	value="<?php echo $player->prenom ;?>"/>
				<input type="text" name="savePlayer[nom]"		placeholder="nom"		value="<?php echo $player->nom ;?>"/>
				<input type="text" name="savePlayer[mail]"		placeholder="email"		value="<?php echo $player->email ;?>"/>
				<input type="text" name="savePlayer[tel]"		placeholder="telephone"	value="<?php echo $player->telephone ;?>"/>
			</div>
			<div class="row">
				<input type="button" class="button"
					title="update player informations"
					value="Save" onclick="setActionTest('savePlayer')">
				<input type="button" class="button"
					title="Hide or Show the player from the public list"
					value="<?php echo $player->status == 'H'?'Show':'Hide';?>" onclick="setActionTest('toggleStatusPlayer')">
				<input type="button" class="delete"
					title="delete this player : cannot be undone !!!"
					value="X" onclick="setActionTest('deletePlayer')">
				<input type="button" class="button"
					title="Add a game played by this player"
					value="Add Game" onclick="toggleDisplay('divGameList');">
			</div>
		</div>
		</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche la liste des jeux
	 * */
	function printGameList($g) {
		$gameList = Ss::get()->gameMap;
		$gamePlayed = $g['gamePlayed'];
		echo '<div id="divGameList" class="hiddenDiv">';
		echo '<select id="selectGameList" multiple="multiple" name="new_game[]">';
		foreach($gameList as $game) {
			if(!isset($gamePlayed[$game->id])) {
				// on ajoute le jeu dans la liste uniquement si on y joue pas déjà
				echo '<option value="'.$game->id.'">'.$game->name.'</option>';
			}
		}
		echo '</select>';
		echo '</div>';
		return $g;
	}
	
	/***********************************************************************
	 * affiche la liste des jeux joués par le joueur
	 * */
	function printGamePlayer($g) {
		$gamePlayedList = $g['gamePlayed'];
		?>
		<div class="spaceRow">&nbsp;</div>
		<div class="divTable">
			<div class="divTableBody">

		<?php
		$sess = Ss::get();
		$charDao = $sess->dao->characterDao;
		foreach($gamePlayedList as $id_game => $gamePlayed) {
			$charList = $charDao->getList($id_game);
		?>
			<div class="divTableRow characterRow" >
				<div class="divTableCell game" > <?php echo $gamePlayed['name']; ?></div>
				<?php
				$this->printPlayerCharacter($g, $gamePlayed, $charList);
				if(LibTools::isAdmin()) {
					?>
				<div class="divTableCell divDelete" >
					<input type="button" class="delete"
						title="Remove this game from this player"
						value="X" onclick="setVar('selectIdGame', <?php echo $id_game; ?>);setActionTest('deleteGamePlayed')">
				</div>
					<?php
				}
				?>
			</div>
		<?php
		}
		?>
		</div>
		</div>
		<?php
	}
	
	/***********************************************************************
	 * affiche la cellule character
	 * */
	function printPlayerCharacter($g, $gamePlayed, $charList) {
		$player 	= $this->player;
		$id_game	= $gamePlayed['id'];
		$char 		= $charList[$gamePlayed['id_character']];
		$id_char 	= $char->id;
		if(LibTools::isAdmin()) {
	?>	
			<div class="divTableCell rowValue characterCell noselect" >
				<div class="character <?php echo $char->css_class; ?> clickable"
					title="<?php echo $char->name; ?> : click to edit"
					onclick="toggleDisplay('<?php echo "gamePlayed[$id_game]";?>');">&nbsp;</div>
	<?php	
			$this->combobox->id_elem 			= "gamePlayed[$id_game]";
			$this->combobox->cssClass 			= 'selectPlayerChar hiddenDiv';
			$this->combobox->arr 				= $charList;
			$this->combobox->id_select			= $id_char;
			$this->combobox->libelleCallback	= 'libelleCharacter';
			$this->combobox->title				= 'Select the character used for '.$gamePlayed['name'];
			$this->combobox->doPrint();
			
	?>
			</div>
	<?php	
		} else {
	?>	
			<div class="character <?php echo $char->css_class; ?> divTableCell noselect" title="<?php echo $char->name; ?>">&nbsp;</div>
	<?php
		}
	}

	
	/***********************************************************************
	 * affiche le header du block Ranking
	 * */
	function printPageHeader($g) {
		$g = parent::printPageHeader($g);
		?>
			<input type="hidden" id="selectIdTournament" name="selectIdTournament" value=""/>
			<input type="hidden" id="selectIdGame" name="selectIdGame" value=""/>
			<div class="divTitle scoring"><div class="divTableCell">Player Profile</div></div>		
			<div id="tournamentList" class="ranking">
		<?php
			if(LibTools::isAdmin()) {
				$this->printPageHeaderAdmin($g);
				$this->printGameList($g);
			} else {
				$this->printPageHeaderPublic($g);
			}
			$this->printGamePlayer($g);

		return $g;
	}

	/***********************************************************************
	 * affiche le footer du block ranking
	 * */
	function printPageFooter($g) {
		?>	
			</div><!-- playerList -->
		<?php
		$g = parent::printPageFooter($g);
		return $g;
	}


}


?>

