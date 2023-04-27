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
        $data_modified = false;
        while (isset($dirs[$i])) {
            $dir = $dirs[$i];
            $i++;
            $files = scandir($dir);
            foreach ($files as $file) {
                $file = new \File($dir.$file);
                echo(">>".$file->fullname().PHP_EOL);
                if (substr($file->name(),0,1) == ".") continue;
                if (is_dir($file->fullname())) { $dirs[] = $file->fullname()."/"; continue; }
                if (!$file->exists()) { continue; }
                echo("[💾] ".$file->fullname().PHP_EOL);
                if (!$file->is_video()) {echo("No VideoFile".PHP_EOL); continue; }

                $row = self::indexdata_by_filename($file);
                if ($row == false) {
                    self::$json_index[] = array(
                        "filename" => $file->fullname_as_id(),
                        "filesize" => $file->size(),
                        "modified" => $file->modified()->getTimestamp(),
                        "md5" => $file->md5()
                    );
                    $data_modified = true;
                    continue;
                }
                if ($row[0]["filesize"] != $file->size()) {
                    self::$json_index[$row[1]] = array(
                        "filename" => $file->fullname_as_id(),
                        "filesize" => $file->size(),
                        "modified" => $file->modified()->getTimestamp(),
                        "md5" => $file->md5()
                    );
                    $data_modified = true;
                    continue;
                }
            }
        }
        if ($data_modified) { file_put_contents("/data/index.json", json_encode(self::$json_index)); echo("index.json saved".PHP_EOL); }
    }

    public static function indexdata_by_filename(\File $file) : Array|false {
        for ($i = 0; $i < count(self::$json_index); $i++) {
            $row = self::$json_index[$i];
            if (!empty($row["filename"]) AND $row["filename"] == $file->fullname_as_id()) return array($row, $i);
        }
        return false;
    }

}