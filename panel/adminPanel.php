<?php 
?>
<?php 
/*****************************************************************************
 * ranking.php
 * Contient les fonction lié à l'administration
 *****************************************************************************/

require_once "./lib/lib_tools.php";
require_once "./lib/dao.php";

require_once "./panel/iPanel.php";


//#########################################################################
// Callbacks 
//#########################################################################

/***********************************************************************
 * libelle d'un joueur pour la combobox
 * */
function libellePlayer($p) {
	return $p['pseudo']." | ".$p['prenom']." ".$p['nom'];
}

/***********************************************************************
 * libelle pour la combobox font
 * */
function libelleFont($value) {
	return $value;
}

//#########################################################################
// Class
//#########################################################################

/***********************************************************************
 * Classe regroupant les fonctions d'administration
 **/
class AdminPanel implements IPanel {
	public $dao;
	public $combobox;
	
	function __construct() {
		$this->dao = new Dao();
		$this->combobox = new Combobox();
	}

	
	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		$id_game = $g['id_game'];
		$g['playerList'] 	= $this->dao->playerDao->getListNotRanked($id_game);
		return $g;
	}
	
	public function treatAction($g){
		$this->treatAdminAction($g);
		return $g;
	}
	
	public function printHeader($g){
		$this->printAdminJS();
		return $g;
	}

	public function printBody($g){
		return $g;
	}
	
	public function printFooter($g){
		return $g;
	}
	
	//#########################################################################
	//#########################################################################

	/***********************************************************************
	 * gestion des actions de ranking
	 * */
	function treatAdminAction($g) {
		$id_game = $g['id_game'];
		$action = $_POST['action'];
		LibTools::setLog("Admin Action : ".$action);
		if(!isset($action)) {
			return false;
		}
		
		switch($action) {
			case "refresh" :
				return true;
			case 'save' : 
				$this->updateFont();
				$this->dao->rankingDao->saveNewPoint($id_game, $_POST["player"]);
				return true;
			case 'updateScore' :
				$this->dao->rankingDao->updateScore($id_game, $_POST["player"]);
				return true;
			case 'updateRank' :
				$this->dao->otherDao->updateRank($id_game);
				return true;
			case 'addPlayer' :
				$this->doAddPlayerRanking($id_game);
				return true;
			case 'addNewPlayer' :
				$this->doAddNewPlayer();
				return true;
			case 'deletePlayerRanking' :
				$this->dao->rankingDao->remove($_POST['id_game'], $_POST['deletePlayerRanking']);
				return true;
		}
		return false;
	}

	
	/***********************************************************************
	 * update de la font
	 * */
	function updateFont() {
		$this->saveParamIfChange('font_rank');
		$this->saveParamIfChange('font_score');
		$this->saveParamIfChange('font_pseudo');
		$this->saveParamIfChange('font_name');
	}

	/***********************************************************************
	 * sauvegarde les parametres si besoin
	 * */
	function saveParamIfChange($param) {
		if(LibTools::get($param) == $_POST[$param]) {
			return;
		}
		LibTools::set($param, $_POST[$param]);
		$this->dao->paramDao->save('FONT', $param, $_POST[$param]);

	}

	/***********************************************************************
	 * construction de la liste des font
	 * */
	function getListFont() {
		$fontDir = glob('./font/*.woff2');
		$fontList = array();
		foreach ($fontDir as $filename) {
			$name = basename($filename, '.woff2');
			$fontList[$name] = $name;
		}
		return $fontList;
	}


	/***********************************************************************
	 * ajoute un joueur dans le ranking
	 * */
	function doAddPlayerRanking($id_game) {
		$id_player = $_POST['insert_id_player'];
		$id_char = $_POST['insert_id_character'];
		$points = $_POST['insert_points'];
		
		$this->dao->rankingDao->insert($id_game, $id_player, $id_char, $points);
	}

	/***********************************************************************
	 * ajoute un nouveau joueur dans la base player
	 * */
	function doAddNewPlayer() {
		$test = trim($_POST['plantage']);
		$pseudo = $_POST['new_player_pseudo'];
		$prenom = $_POST['new_player_prenom'];
		$nom = $_POST['new_player_nom'];
		$mail = $_POST['new_player_mail'];
		$tel = $_POST['new_player_tel'];
		
		$this->dao->playerDao->insert($pseudo, $prenom, $nom, $mail, $tel);
	}

	function printAdminJS() {
		?>
		<script>
		function addScore(id, value) {
			var e = document.getElementById("player["+id+"][new_points]");
			e.value = parseInt(e.value) + parseInt(value);
			return false;
		}

		</script>
		<?php 
	}
	

	
	/***********************************************************************
	 * affiche le block de variable admin
	 * */
	function printAdminVar($g) {
		if(LibTools::isAdmin()) {
	?>
		<input type="hidden" name="id_game" value="<?php echo $g['id_game']; ?>"/>
		<input type="hidden" id="deletePlayerRanking" name="deletePlayerRanking" value=""/>
	<?php
		}
	}

	/***********************************************************************
	 * affiche le block d'administration
	 * */
	function printAdminBar($g) {
		if(LibTools::isAdmin()) {
	?>
		<div class="divAdminMenu divAdminTab">
			<div class="row">
				<div class="head">
					<input type="button" value="+" 
						title="open the full admin menu"
						onsubmit="return false;" onclick="toggleDisplay('divAdminMenu');"/>
				</div>
				<div>
					<input class="buttonMenuAdmin" type="button" 
						title="update the previous rank to the current rank (reset the progress)"
						value="Update Rank" onclick="return setActionTest('updateRank');"/>
				</div>
				<div>
					<input class="buttonMenuAdmin" 
						title="apply the temporary score to the current score and update the main character"
						type="button" value="Update Points" onclick="setAction('updateScore');"/>
				</div>
				<div>
					<input class="buttonMenuAdmin" 
						title="save the temporary score and update the main character"
						type="button" value="Save" onclick="setAction('save');"/>
				</div>
				<div>
					<input class="buttonMenuAdmin" 
						title="refresh the page"
						type="button" value="Refresh" onclick="setAction('refresh');"/>
				</div>
			</div>
			<div id="divAdminMenu" class="divAdminTab hiddenDiv">
	<?php
		$this->printFontSelector($g);
		$this->printInsertPlayerRanking($g);
		$this->printInsertNewPlayer($g);
	?>	
			</div>
		</div>
		<div class="spaceRow">&nbsp;</div>
	<?php
		}	
	}

	/***********************************************************************
	 * Affiche le block permettant de choisir la font des colonnes
	 * */
	function printFontSelector($g) {
		if(LibTools::isAdmin()) {
			unset($this->combobox->cssClass);
			$this->combobox->arr 				= $g['fontList'];
			$this->combobox->libelleCallback	= 'libelleFont';
	?>
		<div class="group">
		<div class="row">
			<div class="head" title="Select fonts then clic Save to update the display">
				<label><b>Font :</b></label> 
			</div>
			<div>
	<?php
			$this->combobox->id_elem 			= 'font_rank';
			$this->combobox->id_select			= LibTools::get('font_rank');
			$this->combobox->title				= 'Select the font for the Rank';
			$this->combobox->doPrint();
	?>
			</div>
			<div>
	<?php
			$this->combobox->id_elem 			= 'font_pseudo';
			$this->combobox->id_select			= LibTools::get('font_pseudo');
			$this->combobox->title				= 'Select the font for the Pseudo';
			$this->combobox->doPrint();
	?>
			</div>
			<div>
	<?php
			$this->combobox->id_elem 			= 'font_name';
			$this->combobox->id_select			= LibTools::get('font_name');
			$this->combobox->title				= 'Select the font for the Name';
			$this->combobox->doPrint();
	?>
			</div>
			<div>
	<?php
			$this->combobox->id_elem 			= 'font_score';
			$this->combobox->id_select			= LibTools::get('font_score');
			$this->combobox->title				= 'Select the font for the Score';
			$this->combobox->doPrint();
	?>
			</div>
		</div>
		</div>
	<?php
		}
	}

	
	/***********************************************************************
	 * affiche le bloc d'insertion d'un joueur dans ranking
	 * */
	function printInsertPlayerRanking($g) {
		if(!LibTools::isAdmin()) {
			return $g;
		}
		$this->combobox->cssClass 			= '';
		$this->combobox->id_select			= '';
	?>	
		<div class="group">
		<div class="row">
			<div class="head">
				<label title="insert the selected player into this ranking"><b>Add Ranking:</b></label> 
			</div>
			<div>
				<?php 
				$this->combobox->id_elem 			= "insert_id_player";
				$this->combobox->arr 				= $g['playerList'];
				$this->combobox->libelleCallback	= 'libellePlayer';
				$this->combobox->title				= 'Select the unranked player to insert into the ranking';
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<?php 
				$this->combobox->id_elem 			= 'insert_id_character';
				$this->combobox->arr 				=  $g['charList'];
				$this->combobox->libelleCallback	= 'name';
				$this->combobox->title				= 'Select the character used by this player';
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<label for="insert_points">points : </label>
				<input class="inputEditPoints" type="number" 
					title="base points for this player"
					id="insert_points" name="insert_points" value="0"/>
			</div>
			<div>
				<input type="button" 
					title="insert the selected player into this ranking"
					value="Add Ranking" onclick="setActionTest('addPlayer')">
			</div>
		</div>
		</div>
	<?php
	}

	/***********************************************************************
	 * Affiche le block permettant d'ajouter un joueur en base
	 * */
	function printInsertNewPlayer($g) {
		if(LibTools::isAdmin()) {
	?>	
		<div id="addNewPlayer" class="group">
			<div class="row">
				<input type="text" name="new_player_pseudo"	placeholder="pseudo"/>
				<input type="text" name="new_player_prenom"	placeholder="prenom"/>
				<input type="text" name="new_player_nom"	placeholder="nom"/>
				<input type="text" name="new_player_mail"	placeholder="email"/>
				<input type="text" name="new_player_tel"	placeholder="telephone"/>
			</div>
			<div class="row">
				<input type="button" 
					title="insert a new player into the player list"
					value="Insert New Player" onclick="setActionTest('addNewPlayer')">
				<div>
				</div>
			</div>
		</div>
	<?php
		}
	}

	/***********************************************************************
	 * affiche la cellule character
	 * */
	function printPlayerCharacter($g, $player) {
		if(LibTools::isAdmin()) {
			$id = $player->id;
			$id_char = $player->id_char;			
	?>	
			<div class="divTableCell character">
				<div class="character <?php echo $player->classe; ?>" 
					title="<?php echo $player->character; ?> : click to edit"
					onclick="toggleDisplay('<?php echo "player[$id][id_char]";?>');">&nbsp;</div>
	<?php	
			$this->combobox->id_elem 			= "player[$id][id_char]";
			$this->combobox->cssClass 			= 'selectPlayerChar hiddenDiv';
			$this->combobox->arr 				=  $g['charList'];
			$this->combobox->id_select			= $id_char;
			$this->combobox->libelleCallback	= 'name';
			$this->combobox->title				= 'Select the character used by '.$player->pseudo;
			$this->combobox->doPrint();
			
	?>
			</div>
	<?php	
		} else {
	?>	
			<div class="character <?php echo $player->classe; ?> divTableCell" title="<?php echo $player->character; ?>">&nbsp;</div>
	<?php
		}
	}


	/***********************************************************************
	 * affiche la cellule de mise à jour des points
	 * */
	function printPlayerScoreUpdater($player) {
		if(LibTools::isAdmin()) {
			$id = $player->id;
	?>	
			<div class="editPoints divTableCell ">
				<div class="addScore">
					<div class="divHoriz">+</div>
					<?php $this->printScoreButton($id, 1); ?> 
					<?php $this->printScoreButton($id, 5); ?> 
					<?php $this->printScoreButton($id, 10); ?> 
				</div>
				<input type="hidden" name="<?php echo "player[$id][id_game]";?>" value="<?php echo $player->id_game; ?>"/>
				<input type="hidden" name="<?php echo "player[$id][rank]";?>" value="<?php echo $player->rank; ?>"/>
				<input type="hidden" name="<?php echo "player[$id][previous_rank]";?>" value="<?php echo $player->previous_rank; ?>"/>
				<input type="hidden" name="<?php echo "player[$id][points]";?>" value="<?php echo $player->points; ?>"/>
				<input class="inputEditPoints" type="number" 
					id="<?php echo "player[$id][new_points]"; ?>" 
					name="<?php echo "player[$id][new_points]"; ?>" 
					value="<?php echo $player->new_points ?>"/>
				<div class="addScore">
					<div class="divHoriz">-</div>
					<?php $this->printScoreButton($id, -1); ?> 
					<?php $this->printScoreButton($id, -5); ?> 
					<?php $this->printScoreButton($id, -10); ?> 
				</div>
			</div>
			<div class="divDeletePlayer divTableCell " title="remove player from this ranking">
				<input class="delete" type="button" 
					value="X" 
					onclick="setVar('deletePlayerRanking', <?php echo $player->id; ?>);setActionTest('deletePlayerRanking');"
					/>
			</div>
	<?php
		}	
	}

	/***********************************************************************
	 * affiche un bouton de mise à jour des points
	 * */
	function printScoreButton($id, $value) {
	?>	
		<input type="button" 
			title="<?php echo ($value>0?'add':'substract')." ".abs($value)." to current points";?>"
			class="divHoriz addScoreButton addScore<?php echo $value>=0?'Up':'Down';?>" 
			onclick="addScore(<?php echo $id?>,<?php echo $value?>);" 
			value="<?php echo abs($value);?>"/>
	<?php	
	}


}

?>




