<?php
?>
<?php 
/*****************************************************************************
 * index.php
 * page d'accueil représentant l'écran de ranking
 *****************************************************************************/
try {

require_once "./lib/session.php";
require_once "./panel/masterPage.php";


//$id_game = 1;
/*
if($_GET) {
	if(isset($_GET['g'])) {
		$id_game = $_GET['g'];
		LibTools::setLog("get idgame = $id_game");
	} else {
		LibTools::setLog("def idgame = $id_game");
	}
}
*/

Ss::init();
$page = new MasterPage();

$page->printPage();

} catch (Exception $e) {
	echo 'Exception reçue : ',  $e->getMessage(), "\n";
	
}

?>
