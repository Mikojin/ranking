<?php 
?>
<?php 
/*****************************************************************************
 * tournamentListPanel.php
 * Page de gestion de la liste des tournois
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";


class TournamentPanel extends ListPanel {
	public $g;
	public $id;
	public $tournament;
	function __construct($idTournament) {
		parent::__construct();
		$this->id = $idTournament;
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
				case "saveTournament" :
					$g = $this->doSaveTournament($g);
					$g = $this->doSaveParticipant($g);
					$g = $this->doAddParticipant($g);
					break;
				case "deleteParticipant" :
					$g = $this->doDeleteParticipant($g);
					break;
				case "editPlayer" :
					$g = MenuPanel::doEditPlayer($g);
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
		$sess 					= Ss::get();
		
		$g['typeScoreList'] 	= $sess->dao->typeScoreDao->getList();
		$this->tournament 		= $sess->dao->tournamentDao->get($this->id);
		$g['tournament'] 		= $this->tournament;
		
		$id_game 				= $this->tournament->id_game;
		$gameCode 				= $sess->gameMap[$id_game]->code;

		
		$g['participantList'] 	= $sess->dao->participantDao->getList($this->id);
		$g['playerCharList']	= $sess->dao->participantDao->getPlayerCharacterList($this->id);
		$g['maxRank'] 			= count($g['participantList']);
		$g['playerList'] 		= $sess->dao->playerDao->getListTournament($this->id);


		// $out = json_encode($g['tournamentList']);
		// LibTools::setLog("Tournament List : $out");
		return $g;
	}
	
	/***********************************************************************
	 * Sauvegarde le Tournament
	 * */
	function doSaveTournament($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$id 			= $this->id;
		$id_game		= Ss::get()->game->id;
		if(isset($id)) {
			$id_game		= $this->tournament->id_game;	
		}
		$group_name 	= $_POST['tournament_group_name'];
		$name 			= $_POST['tournament_name'];
		$id_type_score	= $_POST['tournament_id_type_score'];
		$date_start 	= $_POST['tournament_date_start'];
		$date_end 		= $_POST['tournament_date_end'];

		Ss::get()->dao->tournamentDao->save($id, $id_game, $group_name, $name, $id_type_score, $date_start, $date_end);
		return $g;
	}

	/***********************************************************************
	 * Sauvegarde la liste des participant et leur ranking
	 * */
	function doSaveParticipant($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournament		= $this->id;
		$participantList 	= $_POST['participant'];
		if(!isset($participantList)) {
			return $g;
		}
		$participantDao = Ss::get()->dao->participantDao;
		foreach ($participantList as $id => $participant) {
			$participantDao->save($idTournament, $participant['id_player'], $participant['ranking']);
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
		$idTournament		= $this->id;
		$participantList 	= $_POST['new_participants'];
		
		if(!isset($participantList)) {
			return $g;
		}
		
		$participantDao = Ss::get()->dao->participantDao;
		foreach ($participantList as $id_player) {
			$participantDao->insert($idTournament, $id_player);
		}

		return $g;
	}
	
	/***********************************************************************
	 * supprime un Tournament à la liste
	 * */
	function doDeleteParticipant($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournament		= $this->id;
		$idParticipant = $_POST['selectIdParticipant'];
		if(LibTools::isBlank($idParticipant)) {
			LibTools::setLog("Delete Tournament KO : idParticipant is blank");
			return $g;
		}
		$r = Ss::get()->dao->participantDao->deleteParticipant($idTournament, $idParticipant);
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
		$g = parent::printListHeader($g);
		//$g = $this->printTabHeader($g);
		return $g;
	}

	function printTabHeader($g) {
		?>
			<div class="divTableRow characterRow listHeader" >
				<div class="divTableCell scoringHeader"	title="Rank"		>Rank</div>
				<div class="divTableCell scoringHeader" title="Pseudo"		>Pseudo</div>
				<div class="divTableCell scoringHeader"	title="Name"		>Name</div>
				<div class="divTableCell scoringHeader"	title="Surname"		>Surname</div>
				<div class="divTableCell scoringHeader"	title="Surname"		>Main Character</div>
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
		$sess = Ss::get();
		// on récupere le personnage joué
		$playerCharlist = $g['playerCharList'];
		$id_player = $participant->id_player;
		$game = $sess->gameMap[$participant->id_game];
		$mainchar = $game->char_unknown;
		if(array_key_exists($id_player, $playerCharlist)) {
			$id_main_char = $playerCharlist[$id_player]['id_char'];
			$mainchar = $game->characterMap[$id_main_char];
		} 

	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue rank" 	title="Ranking"	><?php echo $participant->ranking;	?></div>
				<div class="divTableCell pseudoNom clickable" title="Go to this player profile"
					onclick="setVar('select_id_player', <?php echo $participant->id; ?>);setAction('editPlayer')" >
					<div class="pseudo"><?php echo $participant->pseudo;	?></div>
					<div class="nom"><?php echo "$participant->prenom $participant->nom"; ?></div>
				</div>
				<div class="divTableCell rowValue characterCell noselect" title="MainChar">
					<div class="character <?php echo $mainchar->css_class; ?>" 
						title="<?php echo $mainchar->name; ?>">&nbsp;</div></div>
				<div class="divTableCell rowValue points" 	title="Points"	><?php echo $participant->score; 		?></div>
			</div>
	<?php
	/*
					<div class="divTableCell rowValue" 	title="Pseudo - Go to this player profile"	
					onclick="setVar('select_id_player', <?php echo $participant->id; ?>);setAction('editPlayer')" ><?php echo $participant->pseudo;	?></div>
				<div class="divTableCell rowValue" 	title="Name"	><?php echo $participant->prenom; 	?></div>
				<div class="divTableCell rowValue" 	title="Surname"	><?php echo $participant->nom; 		?></div>

	*/
	
	}
	
	
	/***********************************************************************
	 * affiche le block d'un scoring ADMIN
	 * */
	function printElementAdmin($g, $participant, $i) {
		$sess = Ss::get();

		$maxRank 		= $g['maxRank'];
		$id 			= $participant->id;
		// on récupere le personnage joué
		$playerCharlist = $g['playerCharList'];
		$id_player = $participant->id_player;
		$game = $sess->gameMap[$participant->id_game];
		$mainchar = $game->char_unknown;
		if(array_key_exists($id_player, $playerCharlist)) {
			$id_main_char = $playerCharlist[$id_player]['id_char'];
			$mainchar = $game->characterMap[$id_main_char];
		} 
	?>	
			<div class="divTableRow characterRow" >
				<input type="hidden" name="participant[<?php echo $id; ?>][id_player]" value="<?php echo $participant->id_player;?>" />
				<div class="divTableCell rowValue" 	title="Ranking"	>
				<input type="number" placeholder="0" title="Final ranking in this tournament"
					min="0" max="<?php echo $maxRank; ?>"
					name="participant[<?php echo $id; ?>][ranking]" 
					value="<?php echo $participant->ranking;?>" /></div>
				<div class="divTableCell pseudoNom clickable" title="Go to this player profile"
					onclick="setVar('select_id_player', <?php echo $participant->id; ?>);setAction('editPlayer')" >
					<div class="pseudo"><?php echo $participant->pseudo;	?></div>
					<div class="nom"><?php echo "$participant->prenom $participant->nom"; ?></div>
				</div>
				<div class="divTableCell rowValue characterCell noselect" title="MainChar">
					<div class="character <?php echo $mainchar->css_class; ?>" 
						title="<?php echo $mainchar->name; ?>">&nbsp;</div></div>
				<div class="divTableCell rowValue points" 	title="Points"	><?php echo $participant->score; 		?></div>
				<div class="divTableCell rowValue" >
					<input type="button" class="delete"
						title="Remove this participant from this Tournament"
						value="X" onclick="setVar('selectIdParticipant', <?php echo $participant->id_player; ?>);setActionTest('deleteParticipant')">
				</div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche l'entete du tournois en PUBLIC
	 * */
	function printTournamentPublic($g) {
		$id = $this->id;
		$tournament = $this->tournament;
		$typeScoreList = $g['typeScoreList'];
		?>
	<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
			<div class="row">
				<input type="hidden" name="tournament_id_<?php echo $id; ?>" />
				<div class="divTableCell rowValue" 	title="Group"		><?php echo $tournament->group_name;?></div>
				<div class="divTableCell rowValue" 	title="Name"		><?php echo $tournament->name; ?></div>
				<div class="divTableCell rowValue" 	title="Type Score"	><?php echo $typeScoreList[$tournament->id_type_score]['type_name']; ?></div>
				<div class="divTableCell rowValue date" 	title="Date Start"	><?php echo $tournament->date_start; ?></div>
				<div class="divTableCell rowValue date" 	title="Date End"	><?php echo $tournament->date_end; ?></div>
			</div>
		</div>
	</div>
		<?php
		return $g;
	}
	
	/***********************************************************************
	 * affiche l'entete du tournoi en ADMIN
	 * */
	function printTournamentAdmin($g) {
		$t = $this->tournament;
		if(!isset($t)) {
			LibTools::setLog("aucun tournois selectionné");
			return $g;
		}
	?>	
		<input type="hidden" id="selectIdTournament" name="selectIdTournament" value="<?php echo $t->id ;?>"/>
		<input type="hidden" id="selectIdParticipant" name="selectIdParticipant" value=""/>
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
		<div class="row">
			<div>
				<input type="text" placeholder="Group name"
					title="Group for the tournament"
					id="tournament_group_name" name="tournament_group_name" value="<?php echo $t->group_name ;?>" />
			</div>
			<div>
				<input type="text" placeholder="Name"
					title="Name for the new tournament"
					id="tournament_name" name="tournament_name" value="<?php echo $t->name ;?>" />
			</div>
			<div>
				<?php 
				$this->combobox->id_elem 			= "tournament_id_type_score";
				$this->combobox->arr 				= $g['typeScoreList'];
				$this->combobox->libelleCallback	= 'type_name';
				$this->combobox->title				= 'Select the Type of the scoring for the new tournament';
				$this->combobox->id_select			= $t->id_type_score;
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<input type="text" placeholder="date start AAAA-MM-DD"
					title="Starting date for the new tournament (AAAA-MM-DD)"
					id="tournament_date_start" name="tournament_date_start" value="<?php echo $t->date_start ;?>" />
			</div>
			<div>
				<input type="text" placeholder="date end AAAA-MM-DD"
					title="Ending date for the new tournament (AAAA-MM-DD)"
					id="tournament_date_end" name="tournament_date_end" value="<?php echo $t->date_end ;?>" />
			</div>
		</div>
		<div class="row">
			<div>
				<input type="button" 
					title="save modification of this tournament"
					value="Save" onclick="setActionTest('saveTournament')">
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

	/***********************************************************************
	 * affiche la liste des joueurs pouvant participer à ce tournoi
	 * */
	function printPlayerList($g) {
		$playerList = $g['playerList'];
		echo '<div id="divPlayerList" class="hiddenDiv">';
		echo '<select id="selectPlayerList" multiple="multiple" name="new_participants[]">';
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
			<input type="hidden" id="select_id_player" name="select_id_player" value=""/>
			<div class="divTitle scoring">
				<div class="divTableCell"><?php echo Ss::get()->gameMap[$this->tournament->id_game]->name ?></div>
				<div class="spaceRow">&nbsp;</div>
				<div class="divTableCell">Tournament</div>
			</div>		
			<div id="tournamentList" class="ranking">
			<div class="spaceRow">&nbsp;</div>
		<?php
			if(LibTools::isAdmin()) {
				$this->printTournamentAdmin($g);
				$g = $this->printPlayerList($g);
			} else {
				$this->printTournamentPublic($g);
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
			</div><!-- tournamentList -->
		<?php
		$g = parent::printPageFooter($g);
		return $g;
	}


}


?>

