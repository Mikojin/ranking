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

	function __construct() {
		$this->g = array();
		$this->loginPanel 	= new LoginPanel();
		$this->menuPanel 	= new MenuPanel();
		$this->adminPanel 	= new AdminPanel();
	}
	
	public function init() {
		$this->initPage();
	}

	public function initPage() {
		// $pageName = LibTools::get('page');
		$sess = LibTools::getSession();
		$pageName = $sess->page;
		LibTools::setLog("MasterPage.initPage = $pageName");
		switch ($pageName) {
			case 'ranking' :
				$this->initPageRanking();
				break;
			case 'playerList' :
				$this->initPagePlayerList();
				break;
			case 'player' :
				$this->initPagePlayer();
				break;
			case 'tournamentList' :
				$this->initPageTournamentList();
				break;
			case 'tournament' :
				$this->initPageTournament();
				break;
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
	
	public function initPagePlayer(){
		require_once "./panel/playerPanel.php";
		$id_player	= LibTools::get('id_player');
		$panel 		= new PlayerPanel($id_player);
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $panel]);
	}

	public function initPageScoring(){
		require_once "./panel/scoringPanel.php";
		$scoringPanel = new ScoringPanel();
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $scoringPanel]);
	}

	public function initPageTournamentList(){
		require_once "./panel/tournamentListPanel.php";
		$panel = new TournamentListPanel();
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $panel]);
	}

	public function initPageTournament(){
		require_once "./panel/tournamentPanel.php";
		$idTournament = LibTools::get("idTournament");
		$panel = new TournamentPanel($idTournament);
		$this->page = new Page($this->g, [$this->loginPanel, $this->menuPanel, $panel]);
	}

	public function printPage() {
		$this->init();
		$this->page->printPage();
	}

}