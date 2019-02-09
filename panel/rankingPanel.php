<?php 
?>
<?php 
/*****************************************************************************
 * ranking.php
 * Contient les fonction lié à la gestion du ranking
 *****************************************************************************/

require_once "./lib/lib_tools.php";
require_once "./lib/lib_css.php";

require_once "./panel/listPanel.php";
require_once "./panel/adminPanel.php";

/**
 * callback d'affichage du libellé d'une saison
 */
function libelleSeason($s) {
	$name = str_pad($s->name, 15, '$');
	$name = str_replace('$', '&nbsp;', $name);
	return "&nbsp;$name : ".$s->date_start." =&gt; ".$s->date_end;
}

class RankingPanel extends ListPanel {

	public $adminPanel;
	// public $libPlayer;
	
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
		$g = $this->initRanking($g);
		return $g;
	}
	
	public function treatAction($g){
		$this->doTreatAction($g);
		return $g;
	}
	
	//#########################################################################
	//#########################################################################


	/***********************************************************************
	 * Initialisation des varaibles globales du ranking
	 * */
	function initRanking($g) {
		LibTools::setLog("initRanking IN");
		$sess = Ss::get();
		$id_game = $sess->game->id;
		// $g['charPath'] 		= $sess->dao->paramDao->load("PATH","character");
		// $g['charList'] 		= $sess->dao->characterDao->getList($id_game);
		$g['fontList'] 		= $this->adminPanel->getListFont();
		$gameCode 			= $sess->game->code;
		// $g['id_char_unknown'] = $this->dao->paramDao->load("CHAR_UNKNOWN", $gameCode);
		$g = $this->initSeason($g);
		// recupere la liste de classement des joueurs
		//$g['rankingList'] 	= $this->dao->otherDao->getInfoRanking($id_game);
		$g = $this->prepareRankingList($g);
		
		LibTools::setLog("initRanking OUT");
		return $g;
	}

	/***********************************************************************
	 * Initialisation de la season
	 * */
	function initSeason($g) {
		$seasonList			= Ss::get()->dao->seasonDao->getList();
		$g['seasonList'] 	= $seasonList;
		$idSeason 				= LibTools::get('idSeason');
		if(isset($idSeason)) {
			LibTools::setLog("Season : get from session : $idSeason");
			if(array_key_exists($idSeason, $g['seasonList'])) {
				$season 			= $g['seasonList'][$idSeason];
			} else {
				unset($season);
			}
		}
		if(isset($season)) {
			LibTools::setLog("Season : get session : ".$season->name);
			$g['season'] 		= $season;
		}
		if(!isset($season) && isset($seasonList) && count($seasonList)>0) {
			foreach($seasonList as $idSeason => $vSeason) {
				LibTools::setLog("Season : init to first of list : $idSeason");
				$g['season'] 	= $vSeason;
				break;
			}
		}
		return $g;
	}
	
	/***********************************************************************
	 * gestion des actions de ranking
	 * */
	function doTreatAction($g) {
		$action = $_POST['action'];
		if(!isset($action)) {
			return false;
		}
		LibTools::setLog("Ranking Action : ".$action);
		
		switch($action) {
			case 'selectSeason' : 
				$g = $this->doSelectSeason($g);
				return true;
			case "editPlayer" :
				$g = MenuPanel::doEditPlayer($g);
				break;
		}
		return false;
	}

	
	/***********************************************************************
	 * imprime les styles lié au ranking
	 * */
	function printRankingJS($g) {
		$jsonPlayerList = json_encode($g['rankingList']);
		$script = <<<EOS
		<script>
		var players =  $jsonPlayerList;

		</script>
		
EOS;
		echo $script;
	}

	/***********************************************************************
	 * prepare la liste de Player pour l'affichage
	 * */
	function prepareRankingList($g) {
		$sess	= Ss::get();
		$id_game = $sess->game->id;
		$season = $g['season'];
		if(!isset($season)) {
			LibTools::setLog("Init Default Season");
			$season = new Season();
			$season->name = "ALL TIME";
			$season->date_start = "2000-01-01";
			$season->date_end 	= "2100-12-31";
		}
		LibTools::setLog("Season id=".$season->id." - name=".$season->name);
		$rankingList = $sess->dao->otherDao->getInfoRankingFull($id_game, $season->date_start, $season->date_end);
		// $simpleList = array();
		// $rankingList = $g['rankingList'];
			
		$length = count($rankingList);
		$last_score = -1;
		$score = 0;
		$current_rank = 0;	
		for($i = 0; $i < $length; $i++) {
			$player = $rankingList[$i];
			$score = $player->points;
			
			// si le score est identique au precedent score
			if( $last_score == $score ){
				// alors on affiche un "-"
				//   et on garde le même rank que le précédent 
				$player->rank_display = '-';
				$player->rank = $current_rank;
			} else {
				// sinon on update tout avec la ligne en cours
				$r = $i+1;
				$player->rank_display = $r;
				$player->rank = $r;
				$current_rank = $r;
				$last_score = $score;
			}
			// affichage de l'icone de progression
			if(!isset($player->previous_rank)) {
				// pas de score, on initialise
				$player->previous_rank = 9999;
			}
			if($player->rank > $player->previous_rank) {
				$player->rank_classe="rankdown";
			} elseif($player->rank < $player->previous_rank) {
				$player->rank_classe="rankup";
			} else {
				$player->rank_classe="ranksame";
			}
			$char = $sess->char_unknown;
			if(isset($player->id_char)) {
				$id_char = $player->id_char;
				$char = $sess->characterMap[$id_char];
			}
			$player->characterCSS = $char->css_class;
			
		}
		$g['rankingList'] = $rankingList;
		return $g;
	}

	/***********************************************************************
	 * selectionne la season
	 * */
	function doSelectSeason($g) {
		$idSeason = '';
		if(isset($_POST['idSeason'])) {
			$idSeason = $_POST['idSeason'];
		}
		if(LibTools::isBlank($idSeason)) {
			return $g;
		}
		LibTools::setLog("select id Season = $idSeason");
		LibTools::set('idSeason', $idSeason);
		$g['season'] = $g['seasonList'][$idSeason];
		return $g;
	}
	
	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function getDisplayedList($g) {
		// recupere la liste de classement des joueurs
		return $g['rankingList'];
	}
	
	/***********************************************************************
	 * affiche le block d'un joueur
	 * */
	function printElement($g, $player, $i) {
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell progress noselect <?php echo $player->rank_classe;?>">&nbsp;</div>
				<div class="divTableCell rank" 
					title="Rank - previous : <?php echo $player->previous_rank; ?>"
					><?php echo $player->rank_display; ?></div>
				<div class="divTableCell pseudoNom clickable" title="Go to this player profile"
					onclick="setVar('select_id_player', <?php echo $player->id; ?>);setAction('editPlayer')" >
					<div class="pseudo"><?php echo $player->pseudo;?></div>
					<div class="nom"><?php echo "$player->prenom $player->nom"; ?></div>
				</div>
				<div class="character <?php echo $player->characterCSS; ?> divTableCell noselect" title="<?php echo $player->character; ?>">&nbsp;</div>
				<div class="points divTableCell "
					title="Points - previous : <?php echo $player->previous_points; ?>"
					><?php echo $player->points?$player->points:0; ?></div>
			</div>
			<div class="divTableRow spaceRow"><div class="divTableCell">&nbsp;</div></div>
	<?php
	}

	/***********************************************************************
	 * affiche le selecteur de season
	 * */
	function printSeasonSelect($g) {
		$season = $g['season'];
		$idSelect = '';
		if(isset($season)) {
			$idSelect = $season->id;
		}
		?>
			<div id="divSeasonSelect" class="divSeasonSelect">
				<?php 
				$this->combobox->id_select			= $idSelect;
				$this->combobox->id_elem 			= "idSeason";
				$this->combobox->arr 				= $g['seasonList'];
				$this->combobox->libelleCallback	= 'libelleSeason';
				$this->combobox->title				= 'Select the season for this ranking';
				$this->combobox->cssClass 			= 'season_select clickable';
				$this->combobox->onchange			= "setAction('selectSeason');";
				$this->combobox->doPrint();
				?>
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
			<div class="divTitle rankingTitle">&nbsp;</div>		
			<div class="<?php echo LibTools::isAdmin()?'rankingAdmin':'rankingPublic'; ?> ranking">
			<div class="spaceRow">&nbsp;</div>
		<?php
		
		$this->adminPanel->printAdminVar($g);
		$this->adminPanel->printAdminBar($g);

		$this->printSeasonSelect($g);
		
		return $g;
	}

	/***********************************************************************
	 * affiche la ligne "header"
	 * */
	function printTableHeader() {
	?>
		<div class="divTableHeading">
			<div class="divTableRow ">
				<div class="divTableHead">Progress</div>
				<div class="divTableHead">Rank</div>
				<div class="divTableHead">Informations</div>
				<div class="divTableHead">Character</div>
				<div class="divTableHead">Points</div>
			</div> 
		</div>
	<?php
	}

	/***********************************************************************
	 * affiche le footer du block ranking
	 * */
	function printPageFooter($g) {
	?>	
		</div><!-- ranking -->
	<?php
		$g = parent::printPageFooter($g);
		return $g;
	}

}


?>

