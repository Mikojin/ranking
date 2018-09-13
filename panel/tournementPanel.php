<?php 
?>
<?php 
/*****************************************************************************
 * tournementListPanel.php
 * Page de gestion de la liste des tournois
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";


class TournementPanel extends ListPanel {
	public $g;
	public $id;
	public $tournement;
	function __construct($idTournement) {
		parent::__construct();
		$this->id = $idTournement;
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
		$g = parent::treatAction($g);
		$action = $_POST['action'];
		if(isset($action)) {
			switch($action) {
				case "saveTournement" :
					$g = $this->doSaveTournement($g);
					$g = $this->doSaveParticipant($g);
					$g = $this->doAddParticipant($g);
					break;
				case "deleteParticipant" :
					$g = $this->doDeleteParticipant($g);
					break;
			}
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

	function setIdTypeScore($g, $v) {
		// LibTools::setLog("setIdTypeScore : $v");
		LibTools::set('idTypeScore', $v);
		$g['idTypeScore'] = $v;
		return $g;
	}
	
	/***********************************************************************
	 * Initialisation du panel
	 * */
	function doInit($g) {
		$id_game 				= $g['id_game'];
		$g['typeScoreList'] 	= $this->dao->typeScoreDao->getList();
		$this->tournement 		= $this->dao->tournementDao->get($this->id);
		$g['tournement'] 		= $this->tournement;
		
		$g['participantList'] 	= $this->dao->participantDao->getList($this->id);
		$g['maxRank'] 			= count($g['participantList']);
		$g['playerList'] 		= $this->dao->playerDao->getListTournement($this->id);
		// $out = json_encode($g['tournementList']);
		// LibTools::setLog("Tournement List : $out");
		return $g;
	}
	
	/***********************************************************************
	 * Sauvegarde le Tournement
	 * */
	function doSaveTournement($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$id 			= $this->id;
		$id_game		= $g['id_game'];
		$group_name 	= $_POST['tournement_group_name'];
		$name 			= $_POST['tournement_name'];
		$id_type_score	= $_POST['tournement_id_type_score'];
		$date_start 	= $_POST['tournement_date_start'];
		$date_end 		= $_POST['tournement_date_end'];

		$this->dao->tournementDao->save($id, $id_game, $group_name, $name, $id_type_score, $date_start, $date_end);
		return $g;
	}

	/***********************************************************************
	 * Sauvegarde la liste des participant et leur ranking
	 * */
	function doSaveParticipant($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournement		= $this->id;
		$participantList 	= $_POST['participant'];
		if(!isset($participantList)) {
			return $g;
		}
		foreach ($participantList as $id => $participant) {
			$this->dao->participantDao->save($idTournement, $participant['id_player'], $participant['ranking']);
		}

		return $g;
	}

	/***********************************************************************
	 * Ajoute les participants à la liste
	 * */
	function doAddParticipant($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournement		= $this->id;
		$participantList 	= $_POST['new_participants'];
		
		if(!isset($participantList)) {
			return $g;
		}
		
		foreach ($participantList as $id_player) {
			$this->dao->participantDao->insert($idTournement, $id_player);
		}

		return $g;
	}
	
	/***********************************************************************
	 * supprime un Tournement à la liste
	 * */
	function doDeleteParticipant($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournement		= $this->id;
		$idParticipant = $_POST['selectIdParticipant'];
		if(LibTools::isBlank($idParticipant)) {
			LibTools::setLog("Delete Tournement KO : idParticipant is blank");
			return $g;
		}
		$r = $this->dao->participantDao->deleteParticipant($idTournement, $idParticipant);
		return $g;
	}
	
	/***********************************************************************
	 * imprime le javascript du panel
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
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste des participants au tournoi
		return $g['participantList'];
	}

	/***********************************************************************
	 * imprime le header de la liste
	 * */
	function printListHeader($g) {
		$g = parent::printListHeader($g)
		?>
			<div class="divTableRow characterRow listHeader" >
				<div class="divTableCell scoringHeader"	title="Rank"		>Rank</div>
				<div class="divTableCell scoringHeader" title="Pseudo"		>Pseudo</div>
				<div class="divTableCell scoringHeader"	title="Name"		>Name</div>
				<div class="divTableCell scoringHeader"	title="Surname"		>Surname</div>
				<div class="divTableCell scoringHeader"	title="Points"		>Points</div>
			<?php if(LibTools::isAdmin()) {
					echo '<div class="divTableCell scoringHeader"	title=""	>&nbsp;</div>';
			}?>
			</div>

		<?php
		return $g;
	}
	
	/***********************************************************************
	 * affiche le block d'un joueur
	 * */
	function printElement($g, $participant, $i) {
		if(LibTools::isAdmin()) {
			$this->printElementAdmin($g, $participant, $i);
		} else {
			$this->printElementPublic($g, $participant);
		}
	}
	

	/***********************************************************************
	 * affiche le block d'un scoring PUBLIC
	 * */
	function printElementPublic($g, $participant) {
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue rank" 	title="Ranking"	><?php echo $participant->ranking;	?></div>
				<div class="divTableCell rowValue" 	title="Pseudo"	><?php echo $participant->pseudo;	?></div>
				<div class="divTableCell rowValue" 	title="Name"	><?php echo $participant->prenom; 	?></div>
				<div class="divTableCell rowValue" 	title="Surname"	><?php echo $participant->nom; 		?></div>
				<div class="divTableCell rowValue points" 	title="Points"	><?php echo $participant->score; 		?></div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le block d'un scoring ADMIN
	 * */
	function printElementAdmin($g, $participant, $i) {
		$maxRank 		= $g['maxRank'];
		$id 			= $participant->id;
	?>	
			<div class="divTableRow characterRow" >
				<input type="hidden" name="participant[<?php echo $id; ?>][id_player]" value="<?php echo $participant->id_player;?>" />
				<div class="divTableCell rowValue" 	title="Ranking"	>
				<input type="number" placeholder="0" title="Final ranking in this tournement"
					min="0" max="<?php echo $maxRank; ?>"
					name="participant[<?php echo $id; ?>][ranking]" 
					value="<?php echo $participant->ranking;?>" /></div>
				<div class="divTableCell rowValue" 	title="Pseudo"	><?php echo $participant->pseudo; ?></div>
				<div class="divTableCell rowValue" 	title="Name"	><?php echo $participant->prenom; ?></div>
				<div class="divTableCell rowValue" 	title="Surname"	><?php echo $participant->nom; ?></div>
				<div class="divTableCell rowValue points" 	title="Points"	><?php echo $participant->score; 		?></div>
				<div class="divTableCell rowValue" >
					<input type="button" class="delete"
						title="Delete this tournement (cannot be undone !!)"
						value="X" onclick="setVar('selectIdParticipant', <?php echo $participant->id_player; ?>);setActionTest('deleteParticipant')">
				</div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche l'entete du tournois en PUBLIC
	 * */
	function printTournementPublic($g) {
		$id = $this->id;
		$tournement = $this->tournement;
		$typeScoreList = $g['typeScoreList'];
		?>
	<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
			<div class="row">
				<input type="hidden" name="tournement_id_<?php echo $id; ?>" />
				<div class="divTableCell rowValue" 	title="Group"		><?php echo $tournement->group_name;?></div>
				<div class="divTableCell rowValue" 	title="Name"		><?php echo $tournement->name; ?></div>
				<div class="divTableCell rowValue" 	title="Type Score"	><?php echo $typeScoreList[$tournement->id_type_score]['type_name']; ?></div>
				<div class="divTableCell rowValue date" 	title="Date Start"	><?php echo $tournement->date_start; ?></div>
				<div class="divTableCell rowValue date" 	title="Date End"	><?php echo $tournement->date_end; ?></div>
			</div>
		</div>
	</div>
		<?php
		return $g;
	}
	
	/***********************************************************************
	 * affiche l'entete du tournoi en ADMIN
	 * */
	function printTournementAdmin($g) {
		$t = $this->tournement;
		if(!isset($t)) {
			LibTools::setLog("aucun tournois selectionné");
			return $g;
		}
	?>	
		<input type="hidden" id="selectIdTournement" name="selectIdTournement" value="<?php echo $t->id ;?>"/>
		<input type="hidden" id="selectIdParticipant" name="selectIdParticipant" value=""/>
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
		<div class="row">
			<div>
				<input type="text" placeholder="Group name"
					title="Group for the tournement"
					id="tournement_group_name" name="tournement_group_name" value="<?php echo $t->group_name ;?>" />
			</div>
			<div>
				<input type="text" placeholder="Name"
					title="Name for the new tournement"
					id="tournement_name" name="tournement_name" value="<?php echo $t->name ;?>" />
			</div>
			<div>
				<?php 
				$this->combobox->id_elem 			= "tournement_id_type_score";
				$this->combobox->arr 				= $g['typeScoreList'];
				$this->combobox->libelleCallback	= 'type_name';
				$this->combobox->title				= 'Select the Type of the scoring for the new tournement';
				$this->combobox->id_select			= $t->id_type_score;
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<input type="text" placeholder="date start AAAA-MM-DD"
					title="Starting date for the new tournement (AAAA-MM-DD)"
					id="tournement_date_start" name="tournement_date_start" value="<?php echo $t->date_start ;?>" />
			</div>
			<div>
				<input type="text" placeholder="date end AAAA-MM-DD"
					title="Ending date for the new tournement (AAAA-MM-DD)"
					id="tournement_date_end" name="tournement_date_end" value="<?php echo $t->date_end ;?>" />
			</div>
		</div>
		<div class="row">
			<div>
				<input type="button" 
					title="insert a new tournement"
					value="Save" onclick="setActionTest('saveTournement')">
			</div>
			<div>
				<input type="button" 
					title="Add Participants"
					value="Add Participants" onclick="toggleDisplay('divPlayerList')">
			</div>
		</div> 
		</div> 
		</div> 
	<?php
	}

	function printPlayerList($g) {
		$playerList = $g['playerList'];
		echo '<div id="divPlayerList" class="hiddenDiv">';
		echo '<select id="selectPlayerList" multiple="multiple" name="new_participants[]"';
		foreach($playerList as $id => $player) {
			echo '<option value="'.$id.'">'.$player->pseudo.' - '.$player->prenom.' '.$player->nom.'</option>';
		}
		echo '</select>';
		echo '</div>';
		return $g;
	}
	
	/***********************************************************************
	 * affiche le header du block Ranking
	 * */
	function printPageHeader($g) {
		$g = parent::printPageHeader($g);
		?>
			<div class="divTitle scoring"><div class="divTableCell">Tournement</div></div>		
			<div id="tournementList">
			<div class="spaceRow">&nbsp;</div>
		<?php
			if(LibTools::isAdmin()) {
				$this->printTournementAdmin($g);
				$g = $this->printPlayerList($g);
			} else {
				$this->printTournementPublic($g);
			}
		?>
		<?php
		return $g;
	}

	/***********************************************************************
	 * affiche le footer du block ranking
	 * */
	function printPageFooter($g) {
		//$this->printEditPlayer($g);
		?>	
			</div><!-- tournementList -->
		<?php
		$g = parent::printPageFooter($g);
		return $g;
	}


}


?>

