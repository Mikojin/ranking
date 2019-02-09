<?php 
?>
<?php 
/*****************************************************************************
 * ranking.php
 * Contient les fonction lié à la gestion du ranking
 *****************************************************************************/

require_once "./lib/session.php";
require_once "./lib/lib_tools.php";
require_once "./panel/iPanel.php";

class Page implements IPanel {
	public $panels;
	public $g;
	
	
	
	/***********************************************************************
	 * $g : variable globale qui sera passé et mise à jour à chaque phase
	 * $panels : une liste de IPanel contenu dans la page
	 * */
	function __construct($g, $panels) {
		$this->g = $g;
		$this->panels = $panels;
	}

	/***********************************************************************
	 * Imprime la page / panel
	 * */
	function printPage() {
		$this->g = $this->init($this->g);
		
		LibTools::setLog("printPage : cssFile = ".Ss::get()->cssFile);

		
		if ($_POST) {
			// Execute code (such as database updates) here.
			$this->g = $this->treatAction($this->g);
			LibTools::clearAction();

			// Redirect to this page. pour netoyer les donnees en post
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit();
		}
		?>
		<html>
		<head>
			<title>Ranking</title>
			<link rel="stylesheet" type="text/css" href="./css/design.css">
			<link rel="stylesheet" type="text/css" href="./css/font.css">
			<link rel="stylesheet" type="text/css" href="<?php echo Ss::get()->cssFile; ?>">
		<?php
		
			$this->g = $this->printHeader($this->g);
		?>

		</head>
		<body>
		<form name="formGlobal" id="formGlobal" method="post">
		<input id="action" name="action" type="hidden"/>

		<?php 
			$this->g = $this->printBody($this->g);
		?>

		</form>
		<?php 
			$this->g = $this->printFooter($this->g);
		?>
		</body>
		</html>
		<?php 	
	}

	/***********************************************************************
	 * Initialise la page et les panels
	 * */
	function init($g) {
		// LibTools::init();
		foreach ($this->panels as $panel) {
			$g = $panel->init($g);
		}
		return $g;
	}
	
	/***********************************************************************
	 * traite les actions du panel
	 * */
	function treatAction($g) {
		foreach ($this->panels as $panel) {
			$g = $panel->treatAction($g);
		}
		return $g;
	}
	
	/***********************************************************************
	 * Imprime le header des panels
	 * */
	function printHeader($g) {
		LibTools::printToolJS(); 
		foreach ($this->panels as $panel) {
			$g = $panel->printHeader($g);
		}
		return $g;
	}
	
	/***********************************************************************
	 * Imprime le body des panels
	 * */
	function printBody($g) {
		foreach ($this->panels as $panel) {
			$g = $panel->printBody($g);
		}
		return $g;
	}
	
	
	/***********************************************************************
	 * Imprime le footer de la page
	 * */
	function printFooter($g) {
		foreach ($this->panels as $panel) {
			$g = $panel->printFooter($g);
		}
		$this->printCredit();
		$this->printLoggerPanel();
		return $g;
	}
	
	function printCredit() {
			?>
				<div class="credit" onclick="window.open('https://twitter.com/MikomiToyunda');">Powered by Mikomi</div>
			<?php
	}

	/***********************************************************************
	 * Imprime le panel de log (si admin uniquement)
	 * */
	function printLoggerPanel() {
		if(LibTools::isAdmin() || true) {		
			?>
			<div class="msg" onclick="toggleDisplay('spanMsg', 'hiddenDivBloc');">display log<br>
			<span id="spanMsg" class="hiddenDivBloc"><?php echo LibTools::getLog(); LibTools::initLog(); ?></span></div>
			<?php 
			// <script src="/libs/angular-1.7.4.min.js"></script>
		}
	}
	
}

