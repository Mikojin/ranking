<?php 
?>
<?php 
/*****************************************************************************
 * loginPanel.php
 * Contient les fonction lié au panel de login
 *****************************************************************************/

require_once "./lib/lib_tools.php";
require_once "./lib/dao.php";

require_once "./panel/iPanel.php";

class LoginPanel implements IPanel {
		
	public $dao;
	
	function __construct() {
		$this->dao = new Dao();
	}
	
	//#########################################################################
	// Implements
	//#########################################################################
	
	public function init($g) {
		return $g;
	}
	
	public function treatAction($g){
		$this->treatLoginAction();
		return $g;
	}
	
	public function printHeader($g){
		return $g;
	}

	public function printBody($g){
		$this->printPanel();
		return $g;
	}
	
	public function printFooter($g){
		return $g;
	}
	
	//#########################################################################
	//#########################################################################

	/***********************************************************************
	 * traitement des action du menu
	 * */
	function treatLoginAction() {
		$action = $_POST['action'];
		switch($action) {
			case 'login' :
				$username= $_POST['username'];
				$password= md5($_POST['password']);
				$this->checkLogin($username, $password);
				return true;
			case 'logout' :
				LibTools::set('user_right', '');
				LibTools::closeSession();
				return true;
		}
		return false;
	}
	
	/***********************************************************************
	 * vérifie la validite du login/password et met à jour les droits de l'utilisateur 
	 * */
	function checkLogin($username, $password) {
		
		$arr = $this->dao->userDao->get($username, $password);
		
		$nbRows = count($arr);
		if($nbRows==1) {
			$row = $arr[0];
			LibTools::set('user_right', $row['right']);
		} else {
			LibTools::set('user_right', '');
			LibTools::setLog('user error');
		}
	}

	
	/***********************************************************************
	 * si admin, imprime le panel menu, sinon de login.
	 * */
	function printPanel() {
		?>
	<div class="menuPanel loginPanel" id="loginPanel" >
		<div class="buttonMenuLogin noselect" onclick="javascript:toggleDisplay('login');">&gt;</div>
		<?php
		if(LibTools::isAdmin()) {
			$this->printMenuForm();
		} else {
			$this->printLoginForm();
		}
		?>
		</div>
		<?php 
	}

	/***********************************************************************
	 * imprime le panel de login
	 * */
	function printLoginForm() {
	?>
		<div id="login" class="divTabMenu divTabLogin hiddenDiv">
			<div class="divCellLogin">
				<label for="username">UserName :</label>
				<input id="username" name="username" placeholder="username" type="text"/>
			</div>
			<div class="divCellLogin">
				<label for="password">Password :</label>
				<input id="password" name="password" placeholder="**********" type="password"/>
			</div>
			<div class="divCellLogin buttonMenu">
				<input name="login" id="login" type="button" value="Login" onclick="setAction('login');"/>
			</div>
		</div>
	<?php 
	}

	/***********************************************************************
	 * imprime le panel du menu
	 * */
	function printMenuForm() {
	?>
		<div id="login" class="divTable divTabMenu hiddenDiv">
			<div class="divTableBody">
			<div class="divTableRow">
			<div class="divTableCell divCellMenu ">
				<input name="logout" id="logout" type="button" value="Logout" onclick="setAction('logout');"/>
			</div>
			</div>
			</div>
		</div>
	<?php 
	}

}
