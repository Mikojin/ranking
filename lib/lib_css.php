<?php
?>
<?php 
// require_once "./lib/lib_tools.php";
// require_once "./lib/lib_dao.php";
// require_once "./lib/dao.php";

/*********************************************************
 * lib_css.php
 * Genere le CSS pour les Character Background 
 *********************************************************/

class LibCSS {

	static function writeCharacterClass($default_path, $classs, $filename) {
		$cssLine = <<<EOS
.$classs {
	background-image: url("$default_path/$filename");
}

EOS;
		return $cssLine;
	}

	static function writeCharacterCSS($charPath, $charList) {
		$out = '';
		foreach ($charList as $id => $char) {
			if($char->css_class && $char->filename) {
				$out .= LibCSS::writeCharacterClass($charPath, $char->css_class, $char->filename);
			}
		}
		
		return $out;
	}
	
		/***********************************************************************
	 * Déclare le css pour la font donnée
	 */ 
	static function printFontCss($familly) {
		$file = LibTools::get($familly);
		// chemin relatif a la racine
		if(!file_exists("./css/font/$file.woff2")) {
			return;
		}
		// dans le css, chemin relatif au fichier css
		$out = <<<EOT
@font-face {
	font-family: '$familly';
	src: url('./font/$file.woff2') format('woff2'),
		 url('./font/$file.woff') format('woff');
	font-weight: normal;
	font-style: normal;
}

EOT;
		return $out;
	}

}