<?php 
?>
<?php 
/*****************************************************************************
 * tournementListPanel.php
 * Page de gestion de la liste des tournois
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";


class TournementListPanel extends ListPanel {
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
				case "addTournement" :
					$g = $this->doAddTournement($g);
					break;
				case "editTournement" :
					$g = $this->doEditTournement($g);
					break;
				case "deleteTournement" :
					$g = $this->doDeleteTournement($g);
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
		$id_game = $g['id_game'];
		$g['typeScoreList'] 	= $this->dao->typeScoreDao->getList();
		$g['tournementList'] 	= $this->dao->tournementDao->getList($id_game);
		// $out = json_encode($g['tournementList']);
		// LibTools::setLog("Tournement List : $out");
		return $g;
	}
	
	/***********************************************************************
	 * Ajoute un Tournement à la liste
	 * */
	function doAddTournement($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$id_game = $g['id_game'];
		$group_name 	= $_POST['tournement_group_name'];
		$name 			= $_POST['tournement_name'];
		$id_type_score	= $_POST['tournement_id_type_score'];
		$date_start 	= $_POST['tournement_date_start'];
		$date_end 		= $_POST['tournement_date_end'];

		$this->dao->tournementDao->insert($id_game, $group_name, $name, $id_type_score, $date_start, $date_end);
		return $g;
	}

	/***********************************************************************
	 * Edit le Tournement selectionne
	 * */
	function doEditTournement($g) {
		$idTournement = $_POST['selectIdTournement'];
		if(LibTools::isBlank($idTournement)) {
			LibTools::setLog("Edit Tournement KO : idTournement is blank");
			return $g;
		}
		LibTools::setLog("Edit Tournement OK : idTournement=$idTournement");
		LibTools::set("page", 'tournement');
		LibTools::set("idTournement", $idTournement);
		//$r = $this->dao->tournementDao->deleteTournement($idTournement);
		return $g;
	}
	
	/***********************************************************************
	 * supprime un Tournement à la liste
	 * */
	function doDeleteTournement($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTournement = $_POST['selectIdTournement'];
		if(LibTools::isBlank($idTournement)) {
			LibTools::setLog("Delete Tournement KO : idTournement is blank");
			return $g;
		}
		$r = $this->dao->tournementDao->deleteTournement($idTournement);
		return $g;
	}
	
	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste de classement des joueurs
		return $g['tournementList'];
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
	function printElement($g, $tournement, $i) {
		if(LibTools::isAdmin()) {
			$this->printElementAdmin($g, $tournement, $i);
		} else {
			$this->printElementPublic($g, $tournement);
		}
	}

	/***********************************************************************
	 * affiche le block d'un scoring PUBLIC
	 * */
	function printElementPublic($g, $tournement) {
		$typeScoreList = $g['typeScoreList'];
		$id = $tournement->id;
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue" >
					<input type="button" 
						title="Open this tournement"
						value="Open" onclick="setVar('selectIdTournement', <?php echo $id; ?>);setAction('editTournement')">
				</div>
				<input type="hidden" name="tournement_id_<?php echo $id; ?>" />
				<div class="divTableCell rowValue" 	title="Group"		><?php echo $tournement->group_name;?></div>
				<div class="divTableCell rowValue" 	title="Name"		><?php echo $tournement->name; ?></div>
				<div class="divTableCell rowValue" 	title="Type Score"	><?php echo $typeScoreList[$tournement->id_type_score]['type_name']; ?></div>
				<div class="divTableCell rowValue date" 	title="Date Start"	><?php echo $tournement->date_start; ?></div>
				<div class="divTableCell rowValue date" 	title="Date End"	><?php echo $tournement->date_end; ?></div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le block d'un scoring ADMIN
	 * */
	function printElementAdmin($g, $tournement, $i) {
		$typeScoreList = $g['typeScoreList'];
		$id = $tournement->id;
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue" >
					<input type="button" 
						title="Edit this tournement"
						value="Edit" onclick="setVar('selectIdTournement', <?php echo $id; ?>);setAction('editTournement')">
				</div>
				<input type="hidden" name="tournement_id_<?php echo $id; ?>" />
				<div class="divTableCell rowValue" 	title="Group"		><?php echo $tournement->group_name;?></div>
				<div class="divTableCell rowValue" 	title="Name"		><?php echo $tournement->name; ?></div>
				<div class="divTableCell rowValue" 	title="Type Score"	><?php echo $typeScoreList[$tournement->id_type_score]['type_name']; ?></div>
				<div class="divTableCell rowValue date" 	title="Date Start"	><?php echo $tournement->date_start; ?></div>
				<div class="divTableCell rowValue date" 	title="Date End"	><?php echo $tournement->date_end; ?></div>
				<div class="divTableCell rowValue" >
					<input type="button" class="delete"
						title="Delete this tournement (cannot be undone !!)"
						value="X" onclick="setVar('selectIdTournement', <?php echo $id; ?>);setActionTest('deleteTournement')">
				</div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le menu de type score ADMIN
	 * */
	function printAdminTournement($g) {
	?>	
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">

		<div class="group">
		<div class="row">
			<div>
				<input type="text" placeholder="Group name"
					title="Group for the tournement"
					id="tournement_group_name" name="tournement_group_name" value="" />
			</div>
			<div>
				<input type="text" placeholder="Name"
					title="Name for the new tournement"
					id="tournement_name" name="tournement_name" value="" />
			</div>
			<div>
				<?php 
				$this->combobox->id_elem 			= "tournement_id_type_score";
				$this->combobox->arr 				= $g['typeScoreList'];
				$this->combobox->libelleCallback	= 'type_name';
				$this->combobox->title				= 'Select the Type of the scoring for the new tournement';
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<input type="text" placeholder="date start AAAA-MM-DD"
					title="Starting date for the new tournement (AAAA-MM-DD)"
					id="tournement_date_start" name="tournement_date_start" value="" />
			</div>
			<div>
				<input type="text" placeholder="date end AAAA-MM-DD"
					title="Ending date for the new tournement (AAAA-MM-DD)"
					id="tournement_date_end" name="tournement_date_end" value="" />
			</div>
			<div>
				<input type="button" 
					title="insert a new tournement"
					value="Add" onclick="setActionTest('addTournement')">
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
			<input type="hidden" id="selectIdTournement" name="selectIdTournement" value=""/>
			<div class="divTitle scoring"><div class="divTableCell">Tournement Management</div></div>		
			<div id="tournementList">
			<div class="spaceRow">&nbsp;</div>
		<?php
			if(LibTools::isAdmin()) {
				$this->printAdminTournement($g);
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

