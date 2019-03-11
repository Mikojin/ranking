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

	/***********************************************************************
	 * Renvoie une String représentant le CSS du titre de la page ranking pour un jeu Game donné
	 * $game : le jeu pour lequel on écrit le CSS du titre
	 */ 
	static function writeRankingTitle($rootPath, $game) {
		$gameCode = $game->code;
		
		$cssLine = <<<EOS
.divTitle.rankingTitle.$gameCode {
	background-image: url("$rootPath/Images/Design/$gameCode/RankingTitle.png");
}		

EOS;
		return $cssLine;
	}
	
	static function writeCssImport($gameMap) {
		foreach( $gameMap as $game) {
			echo '<link rel="stylesheet" type="text/css" href="'.($game->cssFile).'">';
		}

	}
	
	/***********************************************************************
	 * Renvoie une String représentant le CSS de la classe permettant d'affiché l'image d'un personnage
	 * $path : le chemin des images des personnage pour ce jeu
	 * $gameCode : le code du jeu
	 * $classs : la classe CSS utilisé pour le personnage
	 * $filename : le nom du fichier de l'image du personnage
	 */ 
	static function writeCharacterClass($path, $gameCode, $classs, $filename) {
		$css = $gameCode.'_'.$classs;
		$cssLine = <<<EOS
.$css {
	background-image: url("$path/$filename");
}

EOS;
		return $cssLine;
	}

	/***********************************************************************
	 * Ecrit les fichiers CSS de chaque jeu de la map donnée.
	 * $gameMap : la map de chaque jeu Game pour lesquels on souhaite écrire le CSS
	 */ 
	static function writeGameCSS($gameMap) {
		$rootPath = '/ranking';
		foreach($gameMap as $game) {
			$out = LibCSS::writeRankingTitle( $rootPath, $game);
			$out .= LibCSS::writeCharacterCSS($rootPath, $game);
			LibTools::writeFile($game->cssFile, $out);
		}
	}
	
	/***********************************************************************
	 * Renvoie une String contenant les classes CSS de chaque personnage du jeu donné
	 * $rootPath : le chemin de racine du site (<host>/<rootPath>/<reste du site>
	 * $game : un objet Game
	 */ 
	static function writeCharacterCSS($rootPath, $game ) {
		$out = '';
		$gameCode = $game->code; 
		$charList = $game->characterMap;
		$charPath = "$rootPath/Images/Character/$gameCode";
		foreach ($charList as $id => $char) {
			if($char->css_class && $char->filename) {
				$out .= LibCSS::writeCharacterClass($charPath, $gameCode, $char->css_class, $char->filename);
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