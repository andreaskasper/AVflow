<?php

namespace bots;

class indexer {

    public static $json_index = [];

    public static function start() {
        if (!file_exists("/in/")) die("[ERROR] /in/ Verzeichnis fehlt!".PHP_EOL);
        if (!file_exists("/data/")) mkdir("/data/");
        if (!file_exists("/out/")) mkdir("/out/");

        if (!file_exists("/data/index.json")) file_put_contents("/data/index.json","[]");
        self::$json_index = json_decode(file_get_contents("/data/index.json"),true);

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
                $file2 = substr($dir.$file, 3, 9999);
                $row = self::indexdata_by_filename($file2);
                if ($row == false) {
                    $md5 = md5_file($dir.$file);
                    self::$json_index[] = array(
                        "filename" => $file2,
                        "filesize" => filesize($dir.$file),
                        "md5" => $md5
                    );
                    file_put_contents("/data/index.json", json_encode(self::$json_index));
                }
            }
        }
    }

    public static function is_videofile(Array $arr) : bool {
        if (strtolower($arr["extension"] ?? "") == "mp4") return true;
        return false;
    }

    public static function indexdata_by_filename($filename) : Array|false {
        for ($i = 0; $i < count(self::$json_index); $i++) {
            $row = self::$json_index[$i];
            if ($row["filename"] == $filename) return array($row, $i);
        }
        return false;
    }

}