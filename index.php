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

$id_game = 1;

Ss::init($id_game);
$page = new MasterPage();

$page->printPage();

} catch (Exception $e) {
	echo 'Exception reçue : ',  $e->getMessage(), "\n";
	
}

?>
