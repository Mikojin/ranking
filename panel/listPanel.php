<?php 
?>
<?php 
/*****************************************************************************
 * ranking.php
 * Contient les fonction lié à la gestion du ranking
 *****************************************************************************/

require_once "./lib/lib_tools.php";
require_once "./lib/dao.php";
require_once "./lib/character_css.php";

require_once "./panel/iPanel.php";
// require_once "./lib/lib_player.php";


class ListPanel implements IPanel {
	public $combobox;
	public $dao;
		
	function __construct() {
		$this->dao = new Dao();
		$this->combobox = new Combobox();
	}

	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		$this->initFont();
		return $g;
	}
	
	public function treatAction($g){
		return $g;
	}
	
	public function printHeader($g){
		LibTools::printFontCss('font_rank' 		);
		LibTools::printFontCss('font_score' 	);
		LibTools::printFontCss('font_pseudo' 	);
		LibTools::printFontCss('font_name' 		);
		return $g;
	}

	public function printBody($g){
		$this->printPageBody($g);
		return $g;
	}
	
	public function printFooter($g){
		return $g;
	}
	
	//#########################################################################
	//#########################################################################


	/***********************************************************************
	 * Initialise les fonts de la page
	 * */ 
	function initFont() {
		$mapFont = $this->dao->paramDao->loadGroup('FONT');
		foreach ($mapFont as $var => $param) {
			if(!LibTools::issession($var)) {
				LibTools::set($var, $param['value']);	
			}
		}
	}
	
	/***********************************************************************
	 * Affichage du div Ranking
	 * */ 
	function printPageBody($g) {
		$g = $this->printPageHeader($g);
		
		$g = $this->printListHeader($g);
		$g = $this->printListBody($g);
		$g = $this->printListFooter($g);
			
		$g = $this->printPageFooter($g);
		
		return $g;
	}

	/***********************************************************************
	 * affiche le block ranking
	 * */
	function printListHeader($g) {
		?>	
			<div class="spaceRow">&nbsp;</div>
			<div class="divTable">
				<div class="divTableBody">
		<?php
		return $g;
	}

	/***********************************************************************
	 * affiche le corps de la liste
	 * */
	function printListBody($g) {
		// recupere la liste de classement des joueurs
		$displayerList = $this->getDisplayedList($g);
		$i = 0;
		foreach ($displayerList as $element) {
			$this->printElement($g, $element, $i);
			$i++;
		}
		return $g;
	}
	
	/***********************************************************************
	 * affiche le footer de la liste
	 * */
	function printListFooter($g) {
		?>	
					<div class="divTableRow spaceRow">&nbsp;</div>
				</div> <!-- divTableBody -->
			</div> <!-- divTabl -->
		<?php
		return $g;
	}
	
	/***********************************************************************
	 * renvoie la liste à afficher
	 * */
	function getDisplayedList($g) {
		$arr = ["Panel List"];
		return $arr;
	}

	/***********************************************************************
	 * affiche un element de la liste 
	 * */
	function printElement($g, $element, $i) {
	?>	
			<div class="divTableRow characterRow" >
				<div class="divTableCell "><?php echo $element ?></div>
			</div>
			<div class="divTableRow spaceRow">&nbsp;</div>
	<?php
	}

	/***********************************************************************
	 * affiche le header du block Ranking
	 * */
	function printPageHeader($g) {
	?>	
	<div class="masterDiv">
	<?php
		return $g;
	}

	/***********************************************************************
	 * TODO gerer le header
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
	</div><!-- masterDiv-->
	<?php
		return $g;
	}

}


?>

