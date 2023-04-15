<?php
/*
 * AVFlow Server
 * 
 * Entrypoint for most requests
 *
 * https://github.com/andreaskasper/AVFlow/
 *
 */

header("Cache-Control: no-cache, no-store, must-revalidate");
 
define("asi_allowed_entrypoint", true);

$_ENV["basepath"] = __DIR__;

/* Stardardkonfigurationen */

/*
 * Mit dieser Funktion werden Klassen anhand ihres Namens automatisch geladen. Das Ergebnis spiegelt den Erfolg der Ausführung
 * @param string $class_name Name der Klasse, die geladen werden muss
 * @return boolean
 */
spl_autoload_register(function($class_name) {
	$prio = array();
	if (substr($class_name,0,4) == "API_") {
		require_once(__DIR__."/app/api/0.1/classes/".substr($class_name,4,999).".php");
		return true;
	}
	
	//$prio[] = __DIR__."/app/code/helper/default/".$class_name.".php";
	$prio[] = __DIR__."/app/code/classes/".str_replace(chr(92), "/", $class_name).".php";
	//print_r($prio);

	foreach ($prio as $file) {
		if (file_exists($file)) {
			require($file);
			return true;
		}
	}
	//if (isset($_GET["debug"])) throw new Exception("Klasse ".$class_name." kann nicht gefunden werden!");
	return false;
});
//require_once(__DIR__."/app/code/vendor/autoload.php");

date_default_timezone_set("Europe/Berlin");

\Routing::start();

function html($txt) : string {
	if (is_null($txt)) return "";
	return str_replace("‐", "&shy;", htmlentities($txt, 3, "UTF-8"));
}

function htmlattr($txt) {
	return str_replace(array('"'),array(''),html($txt));
}

function htmlhref($txt) {
	$txt = str_replace(array(" ","ä","ö","ü","ß","Ä","Ö","Ü"),array("_","ae","oe","ue","ss","Ae","Oe","Ue"),$txt);
	$txt = preg_replace("@[^a-zA-Z0-9\_\-]@iU","",$txt);
	return $txt;
}