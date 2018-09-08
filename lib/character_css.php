<?php
?>
<?php 
// require_once "./lib/lib_tools.php";
// require_once "./lib/lib_dao.php";
// require_once "./lib/dao.php";

/*********************************************************
 * character_css.php
 * Genere le CSS pour les Character Background 
 *********************************************************/

class CharacterCSS {

	static function writeCharacterClass($default_path, $classs, $filename) {
		$cssLine = <<<EOS
	.$classs {
		background-image: url("$default_path/$filename");
	}

EOS;
		echo $cssLine;
	}

	static function writeCharacterCSS($charPath, $charList) {
		?>
		<style type="text/css">
		<?php
			foreach ($charList as $id => $char) {
				if($char['css_class'] && $char['filename']) {
					CharacterCSS::writeCharacterClass($charPath, $char['css_class'], $char['filename']);
				}
			}
		?>
		</style>
		<?php 
	}

}