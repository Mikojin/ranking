<?php 
?>
<?php 
/*****************************************************************************
 * iPanel.php
 * Represente l'interface d'un panel 
 *****************************************************************************/

require_once "./lib/lib_tools.php";

/*****************************************************************************
 * Toutes les méthodes prennent et doivent renvoyer $g : variable globale
 * */
interface IPanel {
	
	public function init($g);
	
	public function treatAction($g);
	
	public function printHeader($g);

	public function printBody($g);
	
	public function printFooter($g);
}

/*

	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		return $g;
	}
	
	public function treatAction($g){
		return $g;
	}
	
	public function printHeader($g){
		return $g;
	}

	public function printBody($g){
		$loginPanel->printLoginForm();
		return $g;
	}
	
	public function printFooter($g){
		return $g;
	}
	
	//#########################################################################
	//#########################################################################

*/