<?php
?>
<?php 
/*****************************************************************************
 * index.php
 * page d'accueil représentant l'écran de ranking
 *****************************************************************************/
try {
	
require_once "./panel/masterPage.php";

$id_game = 1;

$page = new MasterPage($id_game);

$page->printPage();

} catch (Exception $e) {
	echo 'Exception reçue : ',  $e->getMessage(), "\n";
}

?>
