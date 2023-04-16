<?php

class Routing {

    public static function start() {
        $p = strpos($_SERVER["REQUEST_URI"],"?");
		if (!$p) $_SERVER["REQUEST_URIpure"] = $_SERVER["REQUEST_URI"]; else $_SERVER["REQUEST_URIpure"] = substr($_SERVER["REQUEST_URI"],0, $p);

		if (preg_match ("@^\/api\/(?P<namespace>[A-Za-z0-9]+)(\.|\/)(?P<method>[A-Za-z0-9]+)(\.|\/)(?P<format>[a-z]+)@", $_SERVER["REQUEST_URIpure"], $m)) {
			\API::run($m["namespace"], $m["method"], $m["format"], $_REQUEST);
			exit(1);
        }

        switch ($_SERVER["REQUEST_URIpure"]) {
            case "/":
                echo('HOMEpage');
                exit();;
        }

        if (preg_match("@^/f/h/(.+)$@", $_SERVER["REQUEST_URIpure"], $m)) { self::file_by_hash($m); exit(); }

        echo('404');
        print_r($_SERVER);
        exit();
    }

    public static function file_by_hash($param) {
        $fn = "/out/".$param[1];
        echo($fn);
        if (file_exists($fn)) {
            readfile($fn);
            exit();
        }
        print_r($param);
        exit();
    }


}