<?php

namespace bots;

class indexer {

    public static function start() {
        if (!file_exists("/in/")) die("[ERROR] /in/ Verzeichnis fehlt!".PHP_EOL);
        if (!file_exists("/data/")) mkdir("/data/");
        if (!file_exists("/out/")) mkdir("/out/");

        $dirs = array("/in/");
        $i = 0;
        while (isset($dirs[$i])) {
            $dir = $dirs[$i];
            $i++;
            $files = scandir($dir);
            foreach ($files as $file) {
                if (substr($file,0,1) == ".") continue;
                if (!is_writable($dir.$file)) continue;
                if (is_dir($dir.$file)) { $dirs[] = $dir.$file."/"; continue; }
                echo("[💾] ".$dir.$file.PHP_EOL);
                $pi = pathinfo($dir.$file);
                if (!self::is_videofile($pi)) continue;
                $md5 = md5_file($dir.$file);
                echo($md5.PHP_EOL);
            }
        }
    }

    public static function is_videofile(Array $arr) : bool {
        if (strtolower($arr["extension"] ?? "") == "mp4") return true;
        return false;
    }

}