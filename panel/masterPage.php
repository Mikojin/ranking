<?php 
?>
<?php 
/*****************************************************************************
 * masterPage.php
 * Page principale aiguillant vers les pages finale
 *****************************************************************************/

require_once "./lib/lib_tools.php";

require_once "./panel/loginPanel.php";
require_once "./panel/menuPanel.php";
require_once "./panel/adminPanel.php";

require_once "./panel/page.php";

class MasterPage {
	public $page;
	public $loginPanel;
	public $menuPanel;
	public $adminPanel;

	function __construct($id_game) {
		$this->g = array();
		$this->g['id_game'] = $id_game;
		$this->loginPanel 	= new LoginPanel();
		$this->menuPanel 	= new MenuPanel();
		$this->adminPanel 	= new AdminPanel();
	}
	
	public function init() {
		LibTools::init();
		if(!LibTools::issession('page')) {
			LibTools::set('page', 'ranking');
		}
		$this->initPage();
	}

	public function initPage() {
		$pageName = LibTools::get('page');
		switch ($pageName) {
			case 'ranking' :
				$this->initPageRanking();
				break;
			case 'playerList' :
				$this->initPagePlayerList();
				break;
			// case 'tournement' :
				// break;
			case 'scoring' :
				$this->initPageScoring();
				break;
			default :
				$this->initPageRanking();
		}
	}
	
	public function initPageRanking(){
		require_once "./panel/rankingPanel.php";
		$rankingPanel 	= new RankingPanel($this->adminPanel);
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $this->adminPanel, $rankingPanel]);
	}
	
	public function initPagePlayerList(){
		require_once "./panel/playerListPanel.php";
		$playerListPanel 	= new PlayerListPanel($this->adminPanel);
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $this->adminPanel, $playerListPanel]);
	}
	
	public function initPageScoring(){
		require_once "./panel/scoringPanel.php";
		$scoringPanel = new ScoringPanel();
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $scoringPanel]);
	}

	public function printPage() {
		$this->init();
		$this->page->printPage();
	}

}