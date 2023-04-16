#!/usr/bin/env php
<?php

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

switch ($argv[2] ?? $argv[1] ?? "") {
    case "bash":
        system("bash");
        exit();
    case "indexer":
        \bots\indexer::start();
        echo("[*] warten".PHP_EOL);
        sleep(3600);
        break;
    case "conv":
        \bots\converter::start();
        echo("[*] warten".PHP_EOL);
        sleep(3600);
        break;
    default:
        echo("[ERROR] Unknown entrypoint or bot...".PHP_EOL);
}