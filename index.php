<?php
?>
<?php 
/*****************************************************************************
 * index.php
 * page d'accueil représentant l'écran de ranking
 *****************************************************************************/

require_once "./panel/loginPanel.php";
require_once "./panel/menuPanel.php";
require_once "./panel/adminPanel.php";
require_once "./panel/rankingPanel.php";
require_once "./panel/page.php";


// Init accessor
$loginPanel 	= new LoginPanel();
$menuPanel 		= new MenuPanel();
$adminPanel 	= new AdminPanel();
$rankingPanel 	= new RankingPanel($adminPanel);

$g = array();
$g['id_game'] = 1;

$page = new Page($g, [$loginPanel, $menuPanel, $adminPanel, $rankingPanel]);

$page->printPage();


?>
