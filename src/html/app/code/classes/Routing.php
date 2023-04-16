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

        if (preg_match("@^/f/h/((?P<md5>[a-f0-9]+).*)$@", $_SERVER["REQUEST_URIpure"], $m)) { self::file_by_hash($m); exit(); }

        echo('404');
        //print_r($_SERVER);
        exit();
    }

    public static function file_by_hash($param) {
        $file = new \File("/out/".$param[1]);
        $md5 = $param["md5"];

        //print_r($_SERVER);

        if (!$file->exists()) die(404);

        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$file->modified()->getTimestamp() /*|| $etagHeader == $md5*/) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }

        switch ($file->extension()) {
            case "mp4":
                header("Content-Type: video/mp4");
        }
        header("Last-Modified: ".gmdate('D, d M Y H:i:s \G\M\T', $file->modified()->getTimestamp()));
        header("Etag: ".$md5);
        header("Cache-Control: public, max-age=3600, s-maxage=3600, stale-while-revaliddate=86400000, stale-if-error=86400000,immutable");
        $fs = new \FileStream($file);
        $fs->start();
        exit();
    }


}