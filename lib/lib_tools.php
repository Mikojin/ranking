<?php 
?>
<?php 
/*****************************************************************************
 * lib_tools.php
 * Contient divers classes et fonctions utilitaires.
 *****************************************************************************/

class LibTools {
 

	/***********************************************************************
	 * Imprime les javascripts générique
	 * */
	static function printToolJS() {
	?>
	<script>
		function toggleDisplay(id, hiddenClass) {
			if(!hiddenClass) {
				hiddenClass = 'hiddenDiv';
			}
			var e = document.getElementById(id);
			e.classList.toggle(hiddenClass);
		}		

		function setVar(id, val) {
			var v = document.getElementById(id);
			v.value=val;
		}
		
		function setAction(ac) {
			var action = document.getElementById('action');
			action.value=ac;
			//alert(action.value);
			var form = document.getElementById('formGlobal');
			form.submit();
			return true;
		}
		
		function setActionTest(ac) {
			var b = confirm('Confirm action : '+ac);
			if(b) {
				setAction(ac);
			}
			return b;
		}
	</script>
	<?php 
	}

	static function isBlank($var) {
		if(!isset($var) || $var == '') {
			return true;
		}
		return false;
	}
	
	/*
	 * recupere la variable $var de la session
	 * */
	static function get($var) {
		return $_SESSION[$var];
	}

	/*
	 * Affecte $value à la variable de session $var
	 * */
	static function set($var, $value) {
		$_SESSION[$var] = $value;
	}
	static function issession($var) {
		return isset($_SESSION) && isset($_SESSION[$var]);
	}

	static function setLog($msg) {
		if(!LibTools::issession('msg_error')) {
			LibTools::initLog();
		}
		$errors = LibTools::get('msg_error');
		$errors[] = $msg;
		LibTools::set('msg_error',$errors);
	}

	static function initLog() {
		LibTools::set('msg_error', array());
	}

	static function getLog() {
		$msg = '';
		$errors = LibTools::get('msg_error');
		foreach ($errors as $e) {
			if($msg === '') {
				$msg = $e;
			} else {
				$msg .= '<br>'.$e;
			}
		} 
		return $msg;
	}

	/*
	 * test si l'action du menu correspond à la valeur $value
	 * */
	static function isAction($value) {
		return isset($_POST['action']) && ($_POST['action'] == $value);
	}

	static function clearAction() {
		if(isset($_POST['action'])) {
			unset($_POST['action']);
		}
	}


	/*
	 * initialisation de l'application
	 * */
	static function init() {
		if(!isset($_SESSION)) {
			session_start();
			LibTools::setLog('');
		}
		if(!LibTools::issession('user_right')) {
			LibTools::set('user_right', '');	
		}
	}

	/***********************************************************************
	 * fermeture de la session
	 * */
	static function closeSession() {
		if(isset($_SESSION)) {
			session_destroy();
		}
	}


	/***********************************************************************
	 * Test si l'utilisateur connecté est admin
	 */
	static function isAdmin() {
		if('admin' == LibTools::get('user_right')) {
			return true;
		}
		return false;
	}
	
	/***********************************************************************
	 * Affiche une combobox 
	 * $id_elem : l'id et le name de la combobox (variable résultante)
	 * $class : la classe CSS
	 * $arr : la map $id => $value contenant les éléments de la combobox 
	 * $id_select : la cle de l'element pré-selectionné de la liste
	 * $callback : function affichant le libellé ou propriété du libellé
	 * */
	static function printCombobox($id_elem, $class, $arr, $id_select, $callback) {
		?>
		<select <?php echo (isset($class)? "class=\"$class\"":''); ?> id="<?php echo $id_elem; ?>" name="<?php echo $id_elem;?>">
			<option value=""></option>
	<?php 
		if(isset($callback)) {
			if(is_callable($callback)) {
				foreach ($arr as $id => $value) {
				?>
				<option value="<?php echo $id;?>" <?php LibTools::printIsSelected($id, $id_select);?>><?php echo $callback($value);?></option>
				<?php 
				}
			} else {
				foreach ($arr as $id => $value) {
				?>
				<option value="<?php echo $id;?>" <?php LibTools::printIsSelected($id, $id_select);?>><?php echo $value[$callback];?></option>
				<?php 
				}
				
			}
		} else {
			foreach ($arr as $id => $value) {
			?>
			<option value="<?php echo $id;?>" <?php LibTools::printIsSelected($id, $id_select);?>><?php echo $value;?></option>
			<?php 
			}
		}
		?>

		</select>
		<?php
	}

	/***********************************************************************
	 * imprime le bloc de selection si $current_id == $id_select
	 */
	static function printIsSelected($current_id, $id_select) {
		if(isset($id_select)) {
			if($id_select == $current_id) {
				echo 'selected="selected"';
			}
		}
	}

	/***********************************************************************
	 * Déclare le css pour la font donnée
	 */ 
	static function printFontCss($familly) {
		$file = LibTools::get($familly);
		
		if(!file_exists("./font/$file.woff2")) {
			return;
		}
	?>
	<style type="text/css">
		@font-face {
			font-family: '<?php echo $familly ?>';
			src: url('./font/<?php echo $file ?>.woff2') format('woff2'),
				 url('./font/<?php echo $file ?>.woff') format('woff');
			font-weight: normal;
			font-style: normal;
		}
	</style>
	<?php 

	}

}


class Combobox {
	public $id_elem;
	public $cssClass;
	public $arr;
	public $id_select;
	public $libelleCallback;
	public $title;
	public $onchange;
	
	function doPrint() {
		?>
		<select 
			<?php echo (isset($this->cssClass)? 'class="'.$this->cssClass.'"':''); ?> 
			id="<?php echo $this->id_elem; ?>"  name="<?php echo $this->id_elem;?>"
			<?php echo (isset($this->title)? 'title="'.$this->title.'"':''); ?>
			<?php echo (isset($this->onchange)? 'onchange="'.$this->onchange.'"':''); ?>
			><option value=""></option>
		<?php 
		$libelleCallback = $this->libelleCallback;
		if(isset($libelleCallback)) {
			if(is_callable($libelleCallback)) {
				foreach ($this->arr as $id => $value) {
				?>
				<option value="<?php echo $id;?>" <?php LibTools::printIsSelected($id, $this->id_select);?>><?php echo $libelleCallback($value);?></option>
				<?php 
				}
			} else {
				foreach ($this->arr as $id => $value) {
				?>
				<option value="<?php echo $id;?>" <?php LibTools::printIsSelected($id, $this->id_select);?>><?php echo $value[$libelleCallback];?></option>
				<?php 
				}
				
			}
		} else {
			foreach ($this->arr as $id => $value) {
			?>
			<option value="<?php echo $id;?>" <?php LibTools::printIsSelected($id, $this->id_select);?>><?php echo $value;?></option>
			<?php 
			}
		}
		?>

		</select>
		<?php
	}
	
}
?>
<?php ?>
<?php 
?>
