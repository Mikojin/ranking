<?php 
?>
<?php 
/*****************************************************************************
 * ScoringPanel.php
 * Page de gestion des types de scoring et de leur répartition de points
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/listPanel.php";


class ScoringPanel extends ListPanel {
	public $g;
	function __construct() {
		parent::__construct();
	}

	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		// LibTools::setLog('init IN');

		$this->g = $g;
		$g = parent::init($g);
		$g = $this->initScoringList($g);

		// LibTools::setLog('init OUT');
		return $g;
	}
	
	public function treatAction($g){
		$g = parent::treatAction($g);
		$action = $_POST['action'];
		// LibTools::setLog("treatAction IN $action");
		if(isset($action)) {
			switch($action) {
				case "selectTypeScore" : 
					$idTypeScore = $_POST['idTypeScore'];
					$g = $this->doSelectTypeScore($g, $idTypeScore);
					break;
				case "addTypeScore" :
					$g = $this->doAddTypeScore($g);
					break;
				case "saveTypeScore" :
					$g = $this->doSaveTypeScore($g);
					break;
				case "addScoringLine" :
					$g = $this->doAddScoringLine($g);
					break;
				case "deleteTypeScore" :
					$g = $this->doDeleteTypeScore($g);
					break;
			}
		}
		// LibTools::setLog('treatAction OUT');
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
	 * Initialisation du panel Scoring
	 * */
	function initScoringList($g) {
		// LibTools::setLog('initScoringList IN');
		$g['typeScoreList'] 	= $this->dao->typeScoreDao->getList();
		$idTypeScore			= LibTools::get('idTypeScore');
		// LibTools::setLog("LibTools::get idTypeScore => $idTypeScore");
		$g = $this->setIdTypeScore($g, $idTypeScore);
		// $g['idTypeScore'] 		= $idTypeScore;
		$g 						= $this->doSelectTypeScore($g, $idTypeScore);
		// LibTools::setLog('initScoringList OUT');
		return $g;
	}
	
	/***********************************************************************
	 * selectionne un TypeScore de la liste
	 * */
	function doSelectTypeScore($g, $idTypeScore) {
		// LibTools::setLog("doSelectTypeScore IN idTypeScore $idTypeScore");
		if(LibTools::isBlank($idTypeScore)) {
			LibTools::setLog("select Type Score is blank");
			// LibTools::set('idTypeScore', $idTypeScore);
			$g = $this->setIdTypeScore($g, $idTypeScore);
			$g['scoringList']	= array();
			return $g;
		}
		LibTools::setLog("select Type Score : $idTypeScore");
		$g = $this->setIdTypeScore($g, $idTypeScore);
		// LibTools::set('idTypeScore', $idTypeScore);
		
		$scoringList = $this->dao->scoringDao->getList($idTypeScore);
		if(count($scoringList) == 0 && LibTools::isAdmin()) {
			// on initialise une liste de scoring par défaut pour les admin
			$scoringList = $this->getNewScoringList($idTypeScore);
		}
		$g['scoringList']	= $scoringList;
		// LibTools::setLog('doSelectTypeScore OUT');
		return $g;
	}

	/***********************************************************************
	 * Renvoie un tableau de scoring par défaut
	 * */
	function getNewScoringList($idTypeScore) {
		LibTools::setLog("Init default scoring List : $idTypeScore");
		$scoringList = array();
		$scoringList[] = new Scoring($idTypeScore, 1, 1, 70);
		$scoringList[] = new Scoring($idTypeScore, 2, 2, 45);
		$scoringList[] = new Scoring($idTypeScore, 3, 3, 25);
		$scoringList[] = new Scoring($idTypeScore, 4, 4, 10);
		$scoringList[] = new Scoring($idTypeScore, 5, 6, 5);
		$scoringList[] = new Scoring($idTypeScore, 7, 8, 1);
		return $scoringList;
	}
	
	/***********************************************************************
	 * Ajoute un TypeScore à la liste
	 * */
	function doAddTypeScore($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$typeScoreName = $_POST['typeScore_name'];
		if(LibTools::isBlank($typeScoreName)) {
			LibTools::setLog("Add Type Score : Type Score name empty !!");
			return $g;
		}
		$idTypeScore = $this->dao->typeScoreDao->insert($typeScoreName);
		if($idTypeScore) {
			$g = $this->setIdTypeScore($g, $idTypeScore);
			// on initialise directement une liste de scoring
			$scoringList = $this->getNewScoringList($idTypeScore);
			$this->doSaveScoringList($idTypeScore, $scoringList);

		}
		return $g;
	}
	
	/***********************************************************************
	 * sauvegarde la liste de scoring pour ce typescore
	 * */
	function doSaveTypeScore($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTypeScore = $_POST['idTypeScore'];
		$scoringList = $_POST['scoringList'];
		LibTools::setLog("save Type Score : $idTypeScore");
		
		//$out = json_encode($scoringList);
		//LibTools::setLog("encode : $out");

		// on purge la liste existante (plus simple que de mettre à jour)
		$r = $this->dao->scoringDao->deleteScoringList($idTypeScore);
		// puis on sauvegarde la nouvelle liste
		$this->doSaveScoringList($idTypeScore, $scoringList);
		
		return $g;
	}
	
	function doSaveScoringList($idTypeScore, $scoringList) {
		foreach($scoringList as $scoring) {
			if(is_a($scoring, "Scoring")) {
				$top 	= $scoring->rank_top;
				$bottom	= $scoring->rank_bottom;
				$score 	= $scoring->score;
			} else {
				$top 	= $scoring["top"];
				$bottom	= $scoring["bottom"];
				$score 	= $scoring["score"];
			}
			if($score == 0) {
				LibTools::setLog(" - IGNORE scoring : idTypeScore=$idTypeScore ; top=$top ; bottom=$bottom ; score=$score ");
				continue;
			}
			// LibTools::setLog(" - Add scoring : idTypeScore=$idTypeScore ; top=$top ; bottom=$bottom ; score=$score ");
			$this->dao->scoringDao->insert($idTypeScore, $top, $bottom, $score);
		}
	}
	
	/***********************************************************************
	 * sauvegarde la liste de scoring pour ce typescore
	 * */
	function doAddScoringLine($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTypeScore = $_POST['idTypeScore'];
		LibTools::setLog("Add Scoring Line : $idTypeScore");
		
		$lastRank = $this->dao->scoringDao->getLastRank($idTypeScore);
		if(LibTools::isBlank($lastRank)) {
			$lastRank = 1;
			LibTools::setLog("Add Scoring Line : Last Rank Not found use = $lastRank");
		}
		LibTools::setLog("Add Scoring Line : Last Rank = $lastRank");
		
		$this->dao->scoringDao->insert($idTypeScore, $lastRank+1, $lastRank+1, 0);
		
		return $g;
	}
	
	/***********************************************************************
	 * supprime un TypeScore à la liste
	 * */
	function doDeleteTypeScore($g) {
		if(!LibTools::isAdmin()) {
			return;
		}
		$idTypeScore = $_POST['idTypeScore'];
		// on purge d'abord la liste de scoring 
		$r = $this->dao->scoringDao->deleteScoringList($idTypeScore);
		if($r) {
			$r = $this->dao->typeScoreDao->deleteTypeScore($idTypeScore);
		}		
		if($r) {
			$g = $this->setIdTypeScore($g, null);
		}
		return $g;
	}
	
	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste de classement des joueurs
		return $g['scoringList'];
	}

	function printListHeader($g) {
		$g = parent::printListHeader($g)
		?>
			<div class="divTableRow characterRow listHeader" >
				<div class="divTableCell scoringHeader"	title="Ranking top (included)"		>Top</div>
				<div class="divTableCell scoringHeader" title="Raning bottom (included)"	>Bottom</div>
				<div class="divTableCell scoringHeader"	title="Score for a ranking between Top and Bottom (included)"		>Score</div>
			</div>

		<?php
		return $g;
	}
	
	/***********************************************************************
	 * affiche le block d'un joueur
	 * */
	function printElement($g, $scoring, $i) {
		if(LibTools::isAdmin()) {
			$this->printElementAdmin($g, $scoring, $i);
		} else {
			$this->printElementPublic($g, $scoring);
		}
	}

	/***********************************************************************
	 * affiche le block d'un scoring PUBLIC
	 * */
	function printElementPublic($g, $scoring) {
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue" 	title="Top"		><?php echo $scoring->rank_top;?></div>
				<div class="divTableCell rowValue" 	title="Bottom"	><?php echo $scoring->rank_bottom; ?></div>
				<div class="divTableCell rowValue" 	title="Score"	><?php echo $scoring->score; ?></div>
			</div>
	<?php
	}
	
	
	/***********************************************************************
	 * affiche le block d'un scoring ADMIN
	 * */
	function printElementAdmin($g, $scoring, $i) {
		
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell rowValue" 	title="Ranking top (included)"		><input type="number" name="scoringList[<?php echo $i;?>][top]" 	value="<?php echo $scoring->rank_top;?>"	/></div>
				<div class="divTableCell rowValue" 	title="Raning bottom (included)"	><input type="number" name="scoringList[<?php echo $i;?>][bottom]" 	value="<?php echo $scoring->rank_bottom; ?>"/></div>
				<div class="divTableCell rowValue" 	title="Score"						><input type="number" name="scoringList[<?php echo $i;?>][score]" 	value="<?php echo $scoring->score; ?>"		/></div>
			</div>
	<?php
	}
	
	/***********************************************************************
	 * affiche le menu de type score
	 * */
	function printTypeScore($g) {
		if(LibTools::isAdmin()) {
			$this->printTypeScoreAdmin($g);
		} else {
			$this->printTypeScorePublic($g);
		}
	}
	
	/***********************************************************************
	 * affiche le menu de type score ADMIN
	 * */
	function printTypeScoreAdmin($g) {
	?>	
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">
		<div class="group">
		<div class="row">
			<div>
				<?php 
				$this->combobox->id_select			= $g['idTypeScore'];
				$this->combobox->id_elem 			= "idTypeScore";
				$this->combobox->arr 				= $g['typeScoreList'];
				$this->combobox->libelleCallback	= 'type_name';
				$this->combobox->title				= 'Select the Type of the scoring';
				$this->combobox->cssClass 			= '';
				$this->combobox->onchange			= "setAction('selectTypeScore');";
				$this->combobox->doPrint();
				?>
			</div>
			<div>
				<input type="button" class="delete"
					title="insert a new type of scoring"
					value="X" onclick="setActionTest('deleteTypeScore')">
			</div>
		</div> 
		<div class="row">
			<div>
				<label for="typeScore_name">add : </label>
				<input type="text" placeholder="New Type Score"
					title="Add a new type of scoring"
					id="typeScore_name" name="typeScore_name" value="" />
			</div>
			<div>
				<input type="button" 
					title="insert a new type of scoring"
					value="Add" onclick="setActionTest('addTypeScore')">
			</div>
		</div> 
		<div class="row">
			<div>
				<input type="button" 
					title="Save the scoring list for this type score"
					value="Save" onclick="setActionTest('saveTypeScore')">
			</div>
			<div>
				<input type="button" 
					title="Add a scoring line to this type score"
					value="Add Scoring Line" onclick="setActionTest('addScoringLine')">
			</div>
		</div> 
		</div> 
		</div> 
	<?php
	}
	
	/***********************************************************************
	 * affiche le menu de type score PUBLIC
	 * */
	function printTypeScorePublic($g) {
	?>	
		<div id="divAdminScoring" class="divAdminMenu divAdminTab">

		<div class="group">
		<div class="row">
			<div>
				<?php 
				$this->combobox->id_select			= $g['idTypeScore'];
				$this->combobox->id_elem 			= "idTypeScore";
				$this->combobox->arr 				= $g['typeScoreList'];
				$this->combobox->libelleCallback	= 'type_name';
				$this->combobox->title				= 'Select the Type of the scoring';
				$this->combobox->cssClass 			= '';
				$this->combobox->onchange			= "setAction('selectTypeScore');";
				$this->combobox->doPrint();
				?>
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
			<div class="divTitle scoring"><div class="divTableCell">Scoring Management</div></div>		
			<div id="typeScoreList">
			<div class="spaceRow">&nbsp;</div>
		<?php
			$this->printTypeScore($g);
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
			</div><!-- playerList -->
		<?php
		$g = parent::printPageFooter($g);
		return $g;
	}


}


?>

