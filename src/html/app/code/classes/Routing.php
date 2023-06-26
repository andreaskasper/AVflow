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
                exit();
            case "/allfiles.html":
                require_once(__DIR__."/../../design/page_listindex.php");
                exit();
        }

        if (preg_match("@^/f/h/((?P<md5>[a-f0-9]+).*)$@", $_SERVER["REQUEST_URIpure"], $m)) { self::file_by_hash($m); exit(); }
        if (preg_match("@^(/explorer|/files)(?P<path>/.*)$@", $_SERVER["REQUEST_URIpure"], $m)) { self::authentication1(); require_once(__DIR__."/../../design/page_fileexplorer.php"); exit(); }

        self::senderror404();
    }

    public static function file_by_hash($param) {
        $file = new \File("/out/".$param[1]);
        $md5 = $param["md5"];

        if (!empty($_ENV["TOKEN_SALT"])) {
            if (empty($_GET["token"]) OR empty($_GET["until"])) self::senderror403("Missing Parameter");
            if ($_GET["until"] < time()) self::senderror403("Too late");
            $token = md5($param[0].$_ENV["TOKEN_SALT"].$_GET["until"]);
            if ($_GET["token"] != $token) self::senderror403("wrong token");
        }

        if (!$file->exists()) self::senderror404();

        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$file->modified()->getTimestamp() /*|| $etagHeader == $md5*/) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }

        switch ($file->extension()) {
            case "mp4": header("Content-Type: video/mp4"); break;
            case "mkv": header("Content-Type: video/mkv"); break;
	    case "jpg": header("Content-Type: image/jpg"); break;
        }
        header("Last-Modified: ".gmdate('D, d M Y H:i:s \G\M\T', $file->modified()->getTimestamp()));
        header("Etag: ".$md5);
        header("Cache-Control: public, max-age=3600, s-maxage=3600, stale-while-revaliddate=86400000, stale-if-error=86400000,immutable");
        $fs = new \FileStream($file);
        $fs->start();
        exit();
    }

    public static function senderror404() {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
        echo("404");
        exit();
    }

    public static function senderror403(string $txt) {
        header($_SERVER["SERVER_PROTOCOL"]." 403 Not Authenticated", true, 404);
        echo($txt);
        exit();
    }

    public static function authentication1() {
        if (!empty($_ENV["USER_NAME"])) {
            if ((($_SERVER['PHP_AUTH_USER'] ?? "") != $_ENV["USER_NAME"]) OR (($_SERVER['PHP_AUTH_PW'] ?? "") != ($_ENV["USER_PASSWORD"] ?? ""))) {
                header('WWW-Authenticate: Basic realm="AVflow"');
                header('HTTP/1.0 401 Unauthorized');
                echo "Please enter username and password to enter this function";
                exit;
            }
        }
    }


}
