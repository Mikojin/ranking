<?php 
?>
<?php 
/*****************************************************************************
 * tournamentListPanel.php
 * Page de gestion de la liste des tournois
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";


class TournamentListPanel extends ListPanel {
	public $g;
	function __construct() {
		parent::__construct();
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
				case "addTournament" :
					$g = $this->doAddTournament($g);
					break;
				case "editTournament" :
					$g = MenuPanel::doEditTournament($g);
					break;
				case "deleteTournament" :
					$g = $this->doDeleteTournament($g);
					break;
			}
		}
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
		$id_game = Ss::get()->game->id;
		$g['typeScoreList'] 	= Ss::get()->dao->typeScoreDao->getList();
		$g['tournamentList'] 	= Ss::get()->dao->tournamentDao->getList($id_game);
		// $out = json_encode($g['tournamentList']);
		// LibTools::setLog("Tournament List : $out");
		return $g;
	}
	
	/***********************************************************************
	 * Ajoute un Tournament à la liste
	 * */
	function doAddTournament($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$id_game = Ss::get()->game->id;
		$group_name 	= $_POST['tournament_group_name'];
		$name 			= $_POST['tournament_name'];
		$id_type_score	= $_POST['tournament_id_type_score'];
		$date_start 	= $_POST['tournament_date_start'];
		$date_end 		= $_POST['tournament_date_end'];
		if(!isset($date_end) || $date_end == '') {
			$date_end = $date_start;
		}

		Ss::get()->dao->tournamentDao->insert($id_game, $group_name, $name, $id_type_score, $date_start, $date_end);
		return $g;
	}
	
	/***********************************************************************
	 * supprime un Tournament à la liste
	 * */
	function doDeleteTournament($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournament = $_POST['selectIdTournament'];
		if(LibTools::isBlank($idTournament)) {
			LibTools::setLog("Delete Tournament KO : idTournament is blank");
			return $g;
		}
		$r = Ss::get()->dao->tournamentDao->deleteTournament($idTournament);
		return $g;
	}
	
	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste de classement des joueurs
		return $g['tournamentList'];
	}

	function printListHeader($g) {
		$g = parent::printListHeader($g)
		?>
			<div class="divTableRow characterRow listHeader" >
				<div class="divTableCell scoringHeader"	title=""	>&nbsp;</div>
				<div class="divTableCell scoringHeader"	title="Group"		>Group</div>
				<div class="divTableCell scoringHeader" title="Name"		>Name</div>
				<div class="divTableCell scoringHeader"	title="Type Score"	>Type Score</div>
				<div class="divTableCell scoringHeader"	title="Date Start"	>Start</div>
				<div class="divTableCell scoringHeader"	title="Date End"	>End</div>
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
	function printElement($g, $tournament, $i) {
		if(LibTools::isAdmin()) {
			$this->printElementAdmin($g, $tournament, $i);
		} else {
			$this->printElementPublic($g, $tournament);
		}
	}

	/***********************************************************************
	 * affiche le block d'un scoring PUBLIC
	 * */
	function printElementPublic($g, $tournament) {
		$typeScoreList = $g['typeScoreList'];
		$id = $tournament->id;
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue" >
					<input type="button" 
						title="Open this tournament"
						value="Open" onclick="setVar('selectIdTournament', <?php echo $id; ?>);setAction('editTournament')">
				</div>
				<input type="hidden" name="tournament_id_<?php echo $id; ?>" />
				<div class="divTableCell rowValue" 	title="Group"		><?php echo $tournament->group_name;?></div>
				<div class="divTableCell rowValue clickable" 	title="Name"		
					title="Open this tournament"
					onclick="setVar('selectIdTournament', <?php echo $id; ?>);setAction('editTournament')"><?php echo $tournament->name; ?></div>
				<div class="divTableCell rowValue" 	title="Type Score"	><?php echo $typeScoreList[$tournament->id_type_score]['type_name']; ?></div>
				<div class="divTableCell rowValue date" 	title="Date Start"	><?php echo $tournament->date_start; ?></div>
				<div class="divTableCell rowValue date" 	title="Date End"	><?php echo $tournament->date_end; ?></div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le block d'un scoring ADMIN
	 * */
	function printElementAdmin($g, $tournament, $i) {
		$typeScoreList = $g['typeScoreList'];
		$id = $tournament->id;
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue" >
					<input type="button" 
						title="Edit this tournament"
						value="Edit" onclick="setVar('selectIdTournament', <?php echo $id; ?>);setAction('editTournament')">
				</div>
				<input type="hidden" name="tournament_id_<?php echo $id; ?>" />
				<div class="divTableCell rowValue" 	title="Group"		><?php echo $tournament->group_name;?></div>
				<div class="divTableCell rowValue clickable" 	title="Name"		
					title="Open this tournament"
					onclick="setVar('selectIdTournament', <?php echo $id; ?>);setAction('editTournament')"><?php echo $tournament->name; ?></div>
				<div class="divTableCell rowValue" 	title="Type Score"	><?php echo $typeScoreList[$tournament->id_type_score]['type_name']; ?></div>
				<div class="divTableCell rowValue date" 	title="Date Start"	><?php echo $tournament->date_start; ?></div>
				<div class="divTableCell rowValue date" 	title="Date End"	><?php echo $tournament->date_end; ?></div>
				<div class="divTableCell rowValue" >
					<input type="button" class="delete"
						title="Delete this tournament (cannot be undone !!)"
						value="X" onclick="setVar('selectIdTournament', <?php echo $id; ?>);setActionTest('deleteTournament')">
				</div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le menu de type score ADMIN
	 * */
	function printAdminTournament($g) {
	?>	
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">

		<div class="group">
		<div class="row">
			<div>
				<input type="text" placeholder="Group name"
					title="Group for the tournament"
					id="tournament_group_name" name="tournament_group_name" value="" />
			</div>
			<div>
				<input type="text" placeholder="Name"
					title="Name for the new tournament"
					id="tournament_name" name="tournament_name" value="" />
			</div>
			<div>
				<?php 
				$this->combobox->id_elem 			= "tournament_id_type_score";
				$this->combobox->arr 				= $g['typeScoreList'];
				$this->combobox->libelleCallback	= 'type_name';
				$this->combobox->title				= 'Select the Type of the scoring for the new tournament';
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<input type="text" placeholder="date start AAAA-MM-DD"
					title="Starting date for the new tournament (AAAA-MM-DD)"
					id="tournament_date_start" name="tournament_date_start" value="" />
			</div>
			<div>
				<input type="text" placeholder="date end AAAA-MM-DD"
					title="Ending date for the new tournament (AAAA-MM-DD)"
					id="tournament_date_end" name="tournament_date_end" value="" />
			</div>
			<div>
				<input type="button" 
					title="insert a new tournament"
					value="Add" onclick="setActionTest('addTournament')">
			</div>
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
			<input type="hidden" id="selectIdTournament" name="selectIdTournament" value=""/>
			<div class="divTitle scoring"><div class="divTableCell">Tournament History</div></div>		
			<div id="tournamentList" class="ranking">
			<div class="spaceRow">&nbsp;</div>
		<?php
			if(LibTools::isAdmin()) {
				$this->printAdminTournament($g);
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

