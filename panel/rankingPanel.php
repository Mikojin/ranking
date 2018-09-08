<?php 
?>
<?php 
/*****************************************************************************
 * ranking.php
 * Contient les fonction lié à la gestion du ranking
 *****************************************************************************/

require_once "./lib/lib_tools.php";
require_once "./lib/character_css.php";

require_once "./panel/listPanel.php";
require_once "./panel/adminPanel.php";


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
	
	public function printHeader($g){
		$g = parent::printHeader($g);
		$this->printRankingStyle($g);
		return $g;
	}
	
	//#########################################################################
	//#########################################################################


	/***********************************************************************
	 * Initialisation des varaibles globales du ranking
	 * */
	function initRanking($g) {
		$id_game = $g['id_game'];
		$g['charPath'] 		= $this->dao->paramDao->load("PATH","character");
		$g['charList'] 		= $this->dao->characterDao->getList($id_game);
		$g['fontList'] 		= $this->adminPanel->getListFont();
		// recupere la liste de classement des joueurs
		//$g['rankingList'] 	= $this->dao->otherDao->getInfoRanking($id_game);
		$g = $this->prepareRankingList($g);

		return $g;
	}
	
	/***********************************************************************
	 * imprime les styles lié au ranking
	 * */
	function printRankingStyle($g) {
		CharacterCSS::writeCharacterCSS($g['charPath'], $g['charList']);
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
		// recupere la liste de classement des joueurs
		$rankingList = $this->dao->otherDao->getInfoRanking($g['id_game']);
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
			if($player->rank > $player->previous_rank) {
				$player->rank_classe="rankdown";
			} elseif($player->rank < $player->previous_rank) {
				$player->rank_classe="rankup";
			} else {
				$player->rank_classe="ranksame";
			}
			//$player->classe = getClasseCharacter($g['id_game'], $player->character);	
			$player->classe = $g['charList'][$player->id_char]['css_class'];

		}
		$g['rankingList'] = $rankingList;
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
	function printElement($g, $player) {
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell progress <?php echo $player->rank_classe;?>">&nbsp;</div>
				<div class="divTableCell rank" > <?php echo $player->rank_display; ?></div>
				<div class="divTableCell pseudoNom">
					<div class="pseudo"><?php echo $player->pseudo;?></div>
					<div class="nom"><?php echo "$player->prenom $player->nom"; ?></div>
				</div>
	<?php
		$this->adminPanel->printPlayerCharacter($g, $player);
	?>	
				<div class="points divTableCell "><?php echo $player->points ?></div>
	<?php
		$this->adminPanel->printPlayerScoreUpdater($player);
	?>	
			</div>
			<div class="divTableRow spaceRow"><div class="divTableCell">&nbsp;</div></div>
	<?php
	}

	/***********************************************************************
	 * affiche le header du block Ranking
	 * */
	function printPageHeader($g) {
		$g = parent::printPageHeader($g);
		?>
			<div class="divTitle rankingTitle">&nbsp;</div>		
			<div class="<?php echo LibTools::isAdmin()?'rankingAdmin':'rankingPublic'; ?> ranking">
			<div class="spaceRow">&nbsp;</div>
			<div class="spaceRow">&nbsp;</div>
		<?php
		
		$this->adminPanel->printAdminVar($g);
		$this->adminPanel->printAdminBar($g);
		
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

