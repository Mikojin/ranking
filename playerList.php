<?php
?>
<?php 
/*****************************************************************************
 * playerList.php
 * Liste de joueur
 *****************************************************************************/

require_once "./panel/loginPanel.php";
require_once "./panel/menuPanel.php";
require_once "./panel/adminPanel.php";
require_once "./panel/playerListPanel.php";
require_once "./panel/page.php";


// Init accessor
$loginPanel 		= new LoginPanel();
$menuPanel 			= new MenuPanel();
$adminPanel 		= new AdminPanel();
$playerListPanel 	= new PlayerListPanel($adminPanel);

$g = array();
$g['id_game'] = 1;

$page = new Page($g, [$loginPanel, $menuPanel, $adminPanel, $playerListPanel]);

$page->printPage();


?>
