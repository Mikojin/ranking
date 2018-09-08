<?php
?>
<?php 
/*****************************************************************************
 * index.php
 * page d'accueil représentant l'écran de ranking
 *****************************************************************************/

require_once "./panel/MasterPage.php";

$id_game = 1;

$page = new MasterPage($id_game);

$page->printPage();

?>
